<?php
/**
 * Media Upload REST Endpoints for SEO MCP Toolkit.
 *
 * Provides fast REST endpoints for direct binary file uploads to the
 * media library. Secured with a brand-specific token defined in wp-config.php.
 * Bypasses MCP for file uploads to avoid base64 overhead.
 *
 * Endpoints:
 *   POST /wp-json/seo-mcp/v1/upload        — single file upload
 *   POST /wp-json/seo-mcp/v1/upload-batch   — batch file upload
 *   POST /wp-json/seo-mcp/v1/store-content  — store large HTML content
 *
 * Authentication:
 *   Token via X-Upload-Token header or "token" form field.
 *   Token is read from wp-config.php constant:
 *     - Botphonic: BOTPHONIC_UPLOAD_TOKEN
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/* ═══════════════════════════════════════════════════════════════════
 * REGISTER REST ROUTES
 * ═══════════════════════════════════════════════════════════════════ */

add_action( 'rest_api_init', 'seo_mcp_media_register_routes' );

function seo_mcp_media_register_routes() {
    $namespace = 'seo-mcp/v1';

    // Single file upload.
    register_rest_route( $namespace, '/upload', array(
        'methods'             => 'POST',
        'callback'            => 'seo_mcp_media_upload_handler',
        'permission_callback' => 'seo_mcp_media_check_token',
    ) );

    // Batch file upload (multiple files in one request).
    register_rest_route( $namespace, '/upload-batch', array(
        'methods'             => 'POST',
        'callback'            => 'seo_mcp_media_upload_batch_handler',
        'permission_callback' => 'seo_mcp_media_check_token',
    ) );

    // Store large content in temp transient.
    register_rest_route( $namespace, '/store-content', array(
        'methods'             => 'POST',
        'callback'            => 'seo_mcp_media_store_content_handler',
        'permission_callback' => 'seo_mcp_media_check_token',
    ) );

    // Also register legacy routes for backwards compatibility.
    $legacy_prefix = SEO_MCP_ABILITY_PREFIX . '-mcp/v1';
    if ( $legacy_prefix !== $namespace ) {
        register_rest_route( $legacy_prefix, '/upload', array(
            'methods'             => 'POST',
            'callback'            => 'seo_mcp_media_upload_handler',
            'permission_callback' => 'seo_mcp_media_check_token',
        ) );
        register_rest_route( $legacy_prefix, '/upload-batch', array(
            'methods'             => 'POST',
            'callback'            => 'seo_mcp_media_upload_batch_handler',
            'permission_callback' => 'seo_mcp_media_check_token',
        ) );
        register_rest_route( $legacy_prefix, '/store-content', array(
            'methods'             => 'POST',
            'callback'            => 'seo_mcp_media_store_content_handler',
            'permission_callback' => 'seo_mcp_media_check_token',
        ) );
    }
}


/* ═══════════════════════════════════════════════════════════════════
 * TOKEN AUTHENTICATION
 * ═══════════════════════════════════════════════════════════════════ */

function seo_mcp_media_check_token( $request ) {
    $token_value = seo_mcp_get_upload_token();

    if ( empty( $token_value ) ) {
        return new WP_Error(
            'token_not_configured',
            sprintf(
                __( 'Upload token is not configured. Define %s in wp-config.php.', 'seo-mcp-toolkit' ),
                SEO_MCP_UPLOAD_TOKEN_CONSTANT
            ),
            array( 'status' => 500 )
        );
    }

    // Get token from header or form field.
    $token = $request->get_header( 'X-Upload-Token' );
    if ( empty( $token ) ) {
        $token = $request->get_param( 'token' );
    }

    if ( empty( $token ) ) {
        return new WP_Error(
            'missing_token',
            __( 'Upload token is required. Send via X-Upload-Token header or "token" form field.', 'seo-mcp-toolkit' ),
            array( 'status' => 401 )
        );
    }

    // Constant-time comparison to prevent timing attacks.
    if ( ! hash_equals( $token_value, $token ) ) {
        return new WP_Error(
            'invalid_token',
            __( 'Invalid upload token.', 'seo-mcp-toolkit' ),
            array( 'status' => 403 )
        );
    }

    // Rate limiting — max uploads per hour (default 50).
    $rate_limit = defined( 'SEO_MCP_UPLOAD_RATE_LIMIT' ) ? (int) SEO_MCP_UPLOAD_RATE_LIMIT : 50;
    $rate_key   = 'seo_mcp_upload_count_' . SEO_MCP_BRAND;
    $count      = (int) get_transient( $rate_key );

    if ( $count >= $rate_limit ) {
        return new WP_Error(
            'rate_limit_exceeded',
            sprintf( __( 'Rate limit exceeded. Maximum %d uploads per hour.', 'seo-mcp-toolkit' ), $rate_limit ),
            array( 'status' => 429 )
        );
    }

    return true;
}


/* ═══════════════════════════════════════════════════════════════════
 * SINGLE UPLOAD HANDLER
 * ═══════════════════════════════════════════════════════════════════ */

function seo_mcp_media_upload_handler( $request ) {
    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
    }
    if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }
    if ( ! function_exists( 'media_handle_upload' ) ) {
        require_once ABSPATH . 'wp-admin/includes/media.php';
    }

    $files = $request->get_file_params();

    if ( empty( $files['file'] ) ) {
        return new WP_Error(
            'no_file',
            __( 'No file uploaded. Send file as multipart form field named "file".', 'seo-mcp-toolkit' ),
            array( 'status' => 400 )
        );
    }

    $file = $files['file'];

    // Validate file upload.
    if ( $file['error'] !== UPLOAD_ERR_OK ) {
        $error_messages = array(
            UPLOAD_ERR_INI_SIZE   => 'File exceeds server upload_max_filesize.',
            UPLOAD_ERR_FORM_SIZE  => 'File exceeds MAX_FILE_SIZE.',
            UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded.',
            UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
        );
        $msg = $error_messages[ $file['error'] ] ?? 'Unknown upload error.';
        return new WP_Error( 'upload_error', $msg, array( 'status' => 400 ) );
    }

    // Validate MIME type — images only.
    $allowed_mimes = array(
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/svg+xml',
    );

    $finfo     = finfo_open( FILEINFO_MIME_TYPE );
    $real_mime = finfo_file( $finfo, $file['tmp_name'] );
    finfo_close( $finfo );

    if ( ! in_array( $real_mime, $allowed_mimes, true ) ) {
        return new WP_Error(
            'invalid_type',
            sprintf( 'File type "%s" is not allowed. Allowed: %s', $real_mime, implode( ', ', $allowed_mimes ) ),
            array( 'status' => 400 )
        );
    }

    // Optional parameters.
    $post_id = absint( $request->get_param( 'post_id' ) );
    $title   = sanitize_text_field( $request->get_param( 'title' ) ?? '' );
    $alt     = sanitize_text_field( $request->get_param( 'alt_text' ) ?? '' );
    $caption = sanitize_text_field( $request->get_param( 'caption' ) ?? '' );

    // Set the current user to an admin so we have upload capability.
    $admins = get_users( array( 'role' => 'administrator', 'number' => 1 ) );
    if ( ! empty( $admins ) ) {
        wp_set_current_user( $admins[0]->ID );
    }

    $_FILES['upload_file'] = $file;
    $attachment_id = media_handle_upload( 'upload_file', $post_id );

    if ( is_wp_error( $attachment_id ) ) {
        return $attachment_id;
    }

    // Set optional metadata.
    if ( ! empty( $title ) ) {
        wp_update_post( array( 'ID' => $attachment_id, 'post_title' => $title ) );
    }
    if ( ! empty( $alt ) ) {
        update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt );
    }
    if ( ! empty( $caption ) ) {
        wp_update_post( array( 'ID' => $attachment_id, 'post_excerpt' => $caption ) );
    }

    // Build response.
    $url      = wp_get_attachment_url( $attachment_id );
    $metadata = wp_get_attachment_metadata( $attachment_id );
    $width    = $metadata['width'] ?? 0;
    $height   = $metadata['height'] ?? 0;
    $alt_text = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
    $fname    = basename( get_attached_file( $attachment_id ) );
    $mime     = get_post_mime_type( $attachment_id );
    $img_tag  = sprintf(
        '<img src="%s" alt="%s" class="wp-image-%d" width="%d" height="%d" />',
        esc_url( $url ),
        esc_attr( $alt_text ),
        $attachment_id,
        $width,
        $height
    );

    // Increment rate limit counter.
    $rate_key = 'seo_mcp_upload_count_' . SEO_MCP_BRAND;
    $count    = (int) get_transient( $rate_key );
    set_transient( $rate_key, $count + 1, HOUR_IN_SECONDS );

    return rest_ensure_response( array(
        'success'       => true,
        'attachment_id' => $attachment_id,
        'url'           => $url,
        'filename'      => $fname,
        'mime_type'     => $mime,
        'width'         => (int) $width,
        'height'        => (int) $height,
        'html_img_tag'  => $img_tag,
        'message'       => sprintf( 'Image "%s" uploaded successfully.', $fname ),
    ) );
}


/* ═══════════════════════════════════════════════════════════════════
 * BATCH UPLOAD HANDLER
 * ═══════════════════════════════════════════════════════════════════ */

function seo_mcp_media_upload_batch_handler( $request ) {
    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
    }
    if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }
    if ( ! function_exists( 'media_handle_upload' ) ) {
        require_once ABSPATH . 'wp-admin/includes/media.php';
    }

    $files_params = $request->get_file_params();

    $files = array();
    if ( ! empty( $files_params['files'] ) && is_array( $files_params['files']['name'] ) ) {
        $count = count( $files_params['files']['name'] );
        for ( $i = 0; $i < $count; $i++ ) {
            $files[] = array(
                'name'     => $files_params['files']['name'][ $i ],
                'type'     => $files_params['files']['type'][ $i ],
                'tmp_name' => $files_params['files']['tmp_name'][ $i ],
                'error'    => $files_params['files']['error'][ $i ],
                'size'     => $files_params['files']['size'][ $i ],
            );
        }
    } elseif ( ! empty( $files_params['file'] ) ) {
        $files[] = $files_params['file'];
    }

    if ( empty( $files ) ) {
        return new WP_Error(
            'no_files',
            __( 'No files uploaded. Send files as "files[]" multipart form fields.', 'seo-mcp-toolkit' ),
            array( 'status' => 400 )
        );
    }

    $max_batch = defined( 'SEO_MCP_UPLOAD_BATCH_LIMIT' ) ? (int) SEO_MCP_UPLOAD_BATCH_LIMIT : 20;
    if ( count( $files ) > $max_batch ) {
        return new WP_Error(
            'batch_too_large',
            sprintf( __( 'Maximum %d files per batch. You sent %d.', 'seo-mcp-toolkit' ), $max_batch, count( $files ) ),
            array( 'status' => 400 )
        );
    }

    $post_id   = absint( $request->get_param( 'post_id' ) );
    $titles    = $request->get_param( 'titles' );
    $alt_texts = $request->get_param( 'alt_texts' );
    $captions  = $request->get_param( 'captions' );

    if ( ! is_array( $titles ) )    $titles    = array();
    if ( ! is_array( $alt_texts ) ) $alt_texts = array();
    if ( ! is_array( $captions ) )  $captions  = array();

    $admins = get_users( array( 'role' => 'administrator', 'number' => 1 ) );
    if ( ! empty( $admins ) ) {
        wp_set_current_user( $admins[0]->ID );
    }

    $allowed_mimes = array( 'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml' );

    $results  = array();
    $uploaded = 0;
    $failed   = 0;

    foreach ( $files as $index => $file ) {
        if ( $file['error'] !== UPLOAD_ERR_OK ) {
            $results[] = array(
                'index'    => $index,
                'filename' => $file['name'] ?? 'unknown',
                'success'  => false,
                'error'    => 'Upload error code: ' . $file['error'],
            );
            $failed++;
            continue;
        }

        $finfo     = finfo_open( FILEINFO_MIME_TYPE );
        $real_mime = finfo_file( $finfo, $file['tmp_name'] );
        finfo_close( $finfo );

        if ( ! in_array( $real_mime, $allowed_mimes, true ) ) {
            $results[] = array(
                'index'    => $index,
                'filename' => $file['name'] ?? 'unknown',
                'success'  => false,
                'error'    => sprintf( 'Type "%s" not allowed.', $real_mime ),
            );
            $failed++;
            continue;
        }

        $_FILES['batch_upload'] = $file;
        $attachment_id = media_handle_upload( 'batch_upload', $post_id );

        if ( is_wp_error( $attachment_id ) ) {
            $results[] = array(
                'index'    => $index,
                'filename' => $file['name'] ?? 'unknown',
                'success'  => false,
                'error'    => $attachment_id->get_error_message(),
            );
            $failed++;
            continue;
        }

        $title = isset( $titles[ $index ] ) ? sanitize_text_field( $titles[ $index ] ) : '';
        $alt   = isset( $alt_texts[ $index ] ) ? sanitize_text_field( $alt_texts[ $index ] ) : '';
        $cap   = isset( $captions[ $index ] ) ? sanitize_text_field( $captions[ $index ] ) : '';

        if ( ! empty( $title ) ) {
            wp_update_post( array( 'ID' => $attachment_id, 'post_title' => $title ) );
        }
        if ( ! empty( $alt ) ) {
            update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt );
        }
        if ( ! empty( $cap ) ) {
            wp_update_post( array( 'ID' => $attachment_id, 'post_excerpt' => $cap ) );
        }

        $url      = wp_get_attachment_url( $attachment_id );
        $metadata = wp_get_attachment_metadata( $attachment_id );
        $width    = $metadata['width'] ?? 0;
        $height   = $metadata['height'] ?? 0;
        $fname    = basename( get_attached_file( $attachment_id ) );
        $mime     = get_post_mime_type( $attachment_id );

        $results[] = array(
            'index'         => $index,
            'success'       => true,
            'attachment_id' => $attachment_id,
            'url'           => $url,
            'filename'      => $fname,
            'mime_type'     => $mime,
            'width'         => (int) $width,
            'height'        => (int) $height,
        );
        $uploaded++;
    }

    // Increment rate limit counter for all uploads.
    $rate_key = 'seo_mcp_upload_count_' . SEO_MCP_BRAND;
    $count    = (int) get_transient( $rate_key );
    set_transient( $rate_key, $count + $uploaded, HOUR_IN_SECONDS );

    return rest_ensure_response( array(
        'success'  => $failed === 0,
        'total'    => count( $files ),
        'uploaded' => $uploaded,
        'failed'   => $failed,
        'results'  => $results,
        'message'  => sprintf( '%d of %d image(s) uploaded successfully.', $uploaded, count( $files ) ),
    ) );
}


/* ═══════════════════════════════════════════════════════════════════
 * STORE CONTENT HANDLER
 * ═══════════════════════════════════════════════════════════════════ */

function seo_mcp_media_store_content_handler( $request ) {
    $content = '';

    // Method 1: JSON body.
    $json_params = $request->get_json_params();
    if ( ! empty( $json_params['content'] ) ) {
        $content = $json_params['content'];
    }

    // Method 2: Form field.
    if ( empty( $content ) ) {
        $content = $request->get_param( 'content' );
    }

    // Method 3: File upload.
    if ( empty( $content ) ) {
        $files = $request->get_file_params();
        if ( ! empty( $files['file'] ) && $files['file']['error'] === UPLOAD_ERR_OK ) {
            $content = file_get_contents( $files['file']['tmp_name'] );
        }
    }

    if ( empty( $content ) ) {
        return new WP_Error(
            'no_content',
            __( 'No content provided. Send as JSON {"content": "..."}, form field "content", or file upload "file".', 'seo-mcp-toolkit' ),
            array( 'status' => 400 )
        );
    }

    // Size limit: 2MB.
    $max_size = defined( 'SEO_MCP_CONTENT_MAX_SIZE' ) ? (int) SEO_MCP_CONTENT_MAX_SIZE : 2 * 1024 * 1024;
    if ( strlen( $content ) > $max_size ) {
        return new WP_Error(
            'content_too_large',
            sprintf( __( 'Content exceeds maximum size of %s.', 'seo-mcp-toolkit' ), size_format( $max_size ) ),
            array( 'status' => 400 )
        );
    }

    $token         = wp_generate_uuid4();
    $ttl           = defined( 'SEO_MCP_CONTENT_TTL' ) ? (int) SEO_MCP_CONTENT_TTL : 300;
    $transient_key = 'mcp_content_' . $token;

    $stored = set_transient( $transient_key, $content, $ttl );

    if ( ! $stored ) {
        return new WP_Error(
            'store_failed',
            __( 'Failed to store content in transient.', 'seo-mcp-toolkit' ),
            array( 'status' => 500 )
        );
    }

    $size_kb = round( strlen( $content ) / 1024, 1 );

    return rest_ensure_response( array(
        'success'        => true,
        'content_token'  => $token,
        'content_length' => strlen( $content ),
        'expires_in'     => $ttl,
        'message'        => sprintf( 'Content stored (%s KB). Use content_token in create-post or update-post.', $size_kb ),
    ) );
}
