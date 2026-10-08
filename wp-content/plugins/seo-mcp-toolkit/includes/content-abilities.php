<?php
/**
 * MCP Content Abilities for SEO MCP Toolkit.
 *
 * Registers WordPress abilities for content management via the MCP Adapter.
 * All ability names use the brand-specific prefix (botphonic/).
 * Supports brand-specific post types, taxonomies, and FAQ plugins (ACF).
 *
 * Abilities: create-post, update-post, get-post, list-posts, list-categories,
 *            list-tags, create-category, upload-media, upload-media-chunked,
 *            attach-media, list-taxonomy-terms, set-post-faqs, get-post-faqs,
 *            set-resource-cta, set-related-pages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/* ═══════════════════════════════════════════════════════════════════
 * PERMISSION CALLBACKS
 * ═══════════════════════════════════════════════════════════════════ */

function seo_mcp_can_publish() {
    return current_user_can( 'publish_posts' );
}

function seo_mcp_can_edit() {
    return current_user_can( 'edit_posts' );
}

function seo_mcp_can_upload() {
    return current_user_can( 'upload_files' );
}


/* ═══════════════════════════════════════════════════════════════════
 * HELPER: Resolve content token
 * ═══════════════════════════════════════════════════════════════════ */

function seo_mcp_resolve_content_token( $params ) {
    // If content is provided directly, use it as-is (check content FIRST, matching old behavior).
    if ( ! empty( $params['content'] ) ) {
        return $params['content'];
    }

    // If content_token is provided, fetch from transient.
    if ( ! empty( $params['content_token'] ) ) {
        $token = sanitize_text_field( $params['content_token'] );

        // Validate UUID format.
        if ( ! wp_is_uuid( $token ) ) {
            return new WP_Error( 'invalid_token', 'content_token must be a valid UUID.' );
        }

        $transient_key = 'mcp_content_' . $token;
        $content       = get_transient( $transient_key );

        if ( false === $content ) {
            return new WP_Error(
                'token_expired',
                __( 'Content token expired or invalid. Tokens expire after 5 minutes. Please re-upload content via /wp-json/seo-mcp/v1/store-content.', 'seo-mcp-toolkit' )
            );
        }

        // Delete after use — one-time token.
        delete_transient( $transient_key );
        return $content;
    }

    // Neither content nor content_token provided.
    return new WP_Error( 'no_content', 'Either "content" or "content_token" is required.' );
}


/* ═══════════════════════════════════════════════════════════════════
 * HELPER: Set Yoast SEO fields
 * ═══════════════════════════════════════════════════════════════════ */

function seo_mcp_set_yoast_seo( $post_id, $seo ) {
    if ( empty( $seo ) || ! is_array( $seo ) ) {
        return 0;
    }

    $count = 0;
    $map   = array(
        'title'           => '_yoast_wpseo_title',
        'description'     => '_yoast_wpseo_metadesc',
        'focus_keyphrase' => '_yoast_wpseo_focuskw',
        'canonical_url'   => '_yoast_wpseo_canonical',
        'og_title'        => '_yoast_wpseo_opengraph-title',
        'og_description'  => '_yoast_wpseo_opengraph-description',
        'og_image_url'    => '_yoast_wpseo_opengraph-image',
    );

    foreach ( $map as $key => $meta_key ) {
        if ( isset( $seo[ $key ] ) && '' !== $seo[ $key ] ) {
            update_post_meta( $post_id, $meta_key, sanitize_text_field( $seo[ $key ] ) );
            $count++;
        }
    }

    // Handle noindex separately (integer value).
    if ( isset( $seo['noindex'] ) ) {
        $value = $seo['noindex'] ? '1' : '0';
        update_post_meta( $post_id, '_yoast_wpseo_meta-robots-noindex', $value );
        $count++;
    }

    return $count;
}


/* ═══════════════════════════════════════════════════════════════════
 * HELPER: Set taxonomy terms
 * ═══════════════════════════════════════════════════════════════════ */

function seo_mcp_set_taxonomy_terms( $post_id, $taxonomy_terms ) {
    if ( empty( $taxonomy_terms ) || ! is_array( $taxonomy_terms ) ) {
        return;
    }

    $allowed = array_merge( array( 'category', 'post_tag' ), SEO_MCP_CUSTOM_TAXONOMIES );

    foreach ( $taxonomy_terms as $taxonomy => $terms ) {
        $taxonomy = sanitize_key( $taxonomy );

        if ( ! in_array( $taxonomy, $allowed, true ) ) {
            continue;
        }
        if ( ! taxonomy_exists( $taxonomy ) ) {
            continue;
        }
        if ( ! is_array( $terms ) ) {
            $terms = array( $terms );
        }

        $term_ids = array();
        foreach ( $terms as $term_name ) {
            $term_name = sanitize_text_field( $term_name );
            $term      = get_term_by( 'name', $term_name, $taxonomy );

            if ( $term ) {
                $term_ids[] = $term->term_id;
            } else {
                $new_term = wp_insert_term( $term_name, $taxonomy );
                if ( ! is_wp_error( $new_term ) ) {
                    $term_ids[] = $new_term['term_id'];
                }
            }
        }

        if ( ! empty( $term_ids ) ) {
            wp_set_object_terms( $post_id, $term_ids, $taxonomy );
        }
    }
}


/* ═══════════════════════════════════════════════════════════════════
 * HELPER: Process content images (download external → media library)
 * ═══════════════════════════════════════════════════════════════════ */

function seo_mcp_process_content_images( $post_id, $content ) {
    if ( empty( $content ) ) {
        return $content;
    }

    if ( ! function_exists( 'media_sideload_image' ) ) {
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }

    $site_host = wp_parse_url( home_url(), PHP_URL_HOST );

    if ( ! preg_match_all( '/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $content, $matches, PREG_SET_ORDER ) ) {
        return $content;
    }

    foreach ( $matches as $match ) {
        $original_tag = $match[0];
        $image_url    = $match[1];
        $image_host   = wp_parse_url( $image_url, PHP_URL_HOST );

        if ( $image_host === $site_host ) {
            continue;
        }
        if ( strpos( $image_url, 'data:' ) === 0 || strpos( $image_url, '//' ) === false ) {
            continue;
        }

        $attachment_id = media_sideload_image( $image_url, $post_id, '', 'id' );
        if ( is_wp_error( $attachment_id ) ) {
            continue;
        }

        $new_url = wp_get_attachment_url( $attachment_id );
        if ( $new_url ) {
            $new_tag = str_replace( $image_url, $new_url, $original_tag );
            if ( strpos( $new_tag, 'wp-image-' ) === false ) {
                $new_tag = str_replace( '<img ', '<img class="wp-image-' . $attachment_id . '" ', $new_tag );
            }
            $content = str_replace( $original_tag, $new_tag, $content );
        }
    }

    return $content;
}


/* ═══════════════════════════════════════════════════════════════════
 * REGISTER ABILITY CATEGORY
 * ═══════════════════════════════════════════════════════════════════ */

add_action( 'wp_abilities_api_categories_init', 'seo_mcp_register_category' );

function seo_mcp_register_category() {
    if ( ! function_exists( 'wp_register_ability_category' ) ) {
        return;
    }
    wp_register_ability_category( SEO_MCP_CATEGORY_SLUG, array(
        'label'       => sprintf( __( '%s Content', 'seo-mcp-toolkit' ), SEO_MCP_BRAND_LABEL ),
        'description' => sprintf(
            __( 'Content management abilities for %s — create, read, update posts across all content types, manage taxonomies, and upload media.', 'seo-mcp-toolkit' ),
            SEO_MCP_BRAND_LABEL
        ),
    ) );
}


/* ═══════════════════════════════════════════════════════════════════
 * REGISTER ABILITIES
 * ═══════════════════════════════════════════════════════════════════ */

add_action( 'wp_abilities_api_init', 'seo_mcp_register_abilities' );

function seo_mcp_register_abilities() {
    if ( ! function_exists( 'wp_register_ability' ) ) {
        return;
    }

    $prefix   = SEO_MCP_ABILITY_PREFIX;
    $category = SEO_MCP_CATEGORY_SLUG;
    $label    = SEO_MCP_BRAND_LABEL;

    $post_type_enum = array_values( SEO_MCP_POST_TYPES );
    $post_type_desc = implode( ', ', array_map( function( $slug ) {
        $lbl = SEO_MCP_POST_TYPE_LABELS[ $slug ] ?? $slug;
        return "\"$slug\" ($lbl)";
    }, SEO_MCP_POST_TYPES ) );

    $faq_plugin_name = ( SEO_MCP_FAQ_PLUGIN === 'scf' ) ? 'SCF' : 'ACF';

    // ── CREATE POST ──────────────────────────────
    wp_register_ability( $prefix . '/create-post', array(
        'label'       => __( 'Create Post', 'seo-mcp-toolkit' ),
        'description' => sprintf(
            __( 'Create a new post of any type with title, content, excerpt, status, categories, tags, custom taxonomy terms, custom slug, FAQs, and Yoast SEO fields. Use post_type to specify the type. Available types: %s. Categories/tags are for "post" type only. Use taxonomy_terms for custom taxonomies. Use faqs to populate the %s FAQ repeater. Use seo to set Yoast meta title, description, focus keyphrase, etc. Default status is "draft". Default post_type is "post".', 'seo-mcp-toolkit' ),
            $post_type_desc,
            $faq_plugin_name
        ),
        'category'    => $category,
        'input_schema' => array(
            'type'       => 'object',
            'required'   => array( 'title' ),
            'properties' => array(
                'title'              => array( 'type' => 'string', 'description' => 'The post title.' ),
                'content'            => array( 'type' => 'string', 'description' => 'The post content (HTML). For content over 50KB, use content_token instead.' ),
                'content_token'      => array( 'type' => 'string', 'description' => 'UUID token from /wp-json/seo-mcp/v1/store-content. Use for large content (>50KB).' ),
                'post_type'          => array( 'type' => 'string', 'enum' => $post_type_enum, 'default' => 'post', 'description' => 'The post type to create.' ),
                'status'             => array( 'type' => 'string', 'enum' => array( 'draft', 'publish', 'pending', 'private' ), 'default' => 'draft', 'description' => 'Post status.' ),
                'excerpt'            => array( 'type' => 'string', 'description' => 'A short excerpt / summary.' ),
                'categories'         => array( 'type' => 'array', 'items' => array( 'type' => 'string' ), 'description' => 'Category names (post type only). New categories auto-created.' ),
                'tags'               => array( 'type' => 'array', 'items' => array( 'type' => 'string' ), 'description' => 'Tag names (post type only).' ),
                'taxonomy_terms'     => array( 'type' => 'object', 'description' => 'Custom taxonomy assignments. {taxonomy_slug: [term_names]}.' ),
                'slug'               => array( 'type' => 'string', 'description' => 'Custom URL slug.' ),
                'parent'             => array( 'type' => 'integer', 'description' => 'Parent post ID for hierarchical types.' ),
                'featured_image_url' => array( 'type' => 'string', 'description' => 'URL of image to set as featured image.' ),
                'faqs'               => array(
                    'type'  => 'array',
                    'description' => sprintf( 'FAQ objects for %s repeater. {question, answer, position}.', $faq_plugin_name ),
                    'items' => array(
                        'type'       => 'object',
                        'required'   => array( 'question', 'answer' ),
                        'properties' => array(
                            'question' => array( 'type' => 'string' ),
                            'answer'   => array( 'type' => 'string' ),
                            'position' => array( 'type' => 'integer' ),
                        ),
                    ),
                ),
                'seo' => array(
                    'type'       => 'object',
                    'description' => 'Yoast SEO fields (all optional).',
                    'properties' => array(
                        'title'           => array( 'type' => 'string' ),
                        'description'     => array( 'type' => 'string' ),
                        'focus_keyphrase' => array( 'type' => 'string' ),
                        'canonical_url'   => array( 'type' => 'string' ),
                        'noindex'         => array( 'type' => 'boolean' ),
                        'og_title'        => array( 'type' => 'string' ),
                        'og_description'  => array( 'type' => 'string' ),
                        'og_image_url'    => array( 'type' => 'string' ),
                    ),
                ),
            ),
        ),
        'output_schema' => array(
            'type'       => 'object',
            'properties' => array(
                'success'        => array( 'type' => 'boolean' ),
                'post_id'        => array( 'type' => 'integer' ),
                'post_type'      => array( 'type' => 'string' ),
                'post_url'       => array( 'type' => 'string' ),
                'edit_url'       => array( 'type' => 'string' ),
                'status'         => array( 'type' => 'string' ),
                'faq_count'      => array( 'type' => 'integer' ),
                'seo_fields_set' => array( 'type' => 'integer' ),
                'message'        => array( 'type' => 'string' ),
            ),
        ),
        'execute_callback'    => 'seo_mcp_create_post',
        'permission_callback' => 'seo_mcp_can_publish',
        'meta' => array( 'mcp' => array( 'public' => true ), 'show_in_rest' => true ),
    ) );

    // ── UPDATE POST ──────────────────────────────
    wp_register_ability( $prefix . '/update-post', array(
        'label'       => __( 'Update Post', 'seo-mcp-toolkit' ),
        'description' => __( 'Update an existing post by ID. Only provided fields are changed. Works with all post types.', 'seo-mcp-toolkit' ),
        'category'    => $category,
        'input_schema' => array(
            'type'       => 'object',
            'required'   => array( 'post_id' ),
            'properties' => array(
                'post_id'        => array( 'type' => 'integer', 'description' => 'The ID of the post to update.' ),
                'title'          => array( 'type' => 'string' ),
                'content'        => array( 'type' => 'string' ),
                'content_token'  => array( 'type' => 'string' ),
                'status'         => array( 'type' => 'string', 'enum' => array( 'draft', 'publish', 'pending', 'private' ) ),
                'excerpt'        => array( 'type' => 'string' ),
                'categories'     => array( 'type' => 'array', 'items' => array( 'type' => 'string' ) ),
                'tags'           => array( 'type' => 'array', 'items' => array( 'type' => 'string' ) ),
                'taxonomy_terms' => array( 'type' => 'object' ),
                'slug'           => array( 'type' => 'string' ),
                'faqs'           => array( 'type' => 'array', 'items' => array( 'type' => 'object' ) ),
                'seo'            => array( 'type' => 'object' ),
            ),
        ),
        'output_schema' => array(
            'type'       => 'object',
            'properties' => array(
                'success'        => array( 'type' => 'boolean' ),
                'post_id'        => array( 'type' => 'integer' ),
                'post_type'      => array( 'type' => 'string' ),
                'post_url'       => array( 'type' => 'string' ),
                'faq_count'      => array( 'type' => 'integer' ),
                'seo_fields_set' => array( 'type' => 'integer' ),
                'message'        => array( 'type' => 'string' ),
            ),
        ),
        'execute_callback'    => 'seo_mcp_update_post',
        'permission_callback' => 'seo_mcp_can_edit',
        'meta' => array( 'mcp' => array( 'public' => true ), 'show_in_rest' => true ),
    ) );

    // ── GET POST ─────────────────────────────────
    wp_register_ability( $prefix . '/get-post', array(
        'label'       => __( 'Get Post', 'seo-mcp-toolkit' ),
        'description' => __( 'Retrieve full details of a single post by ID.', 'seo-mcp-toolkit' ),
        'category'    => $category,
        'input_schema' => array(
            'type'       => 'object',
            'required'   => array( 'post_id' ),
            'properties' => array(
                'post_id' => array( 'type' => 'integer', 'description' => 'Post ID to retrieve.' ),
            ),
        ),
        'output_schema' => array(
            'type'       => 'object',
            'properties' => array(
                'id'             => array( 'type' => 'integer' ),
                'title'          => array( 'type' => 'string' ),
                'content'        => array( 'type' => 'string' ),
                'excerpt'        => array( 'type' => 'string' ),
                'status'         => array( 'type' => 'string' ),
                'post_type'      => array( 'type' => 'string' ),
                'date'           => array( 'type' => 'string' ),
                'modified'       => array( 'type' => 'string' ),
                'url'            => array( 'type' => 'string' ),
                'categories'     => array( 'type' => 'array' ),
                'tags'           => array( 'type' => 'array' ),
                'taxonomy_terms' => array( 'type' => 'object' ),
            ),
        ),
        'execute_callback'    => 'seo_mcp_get_post',
        'permission_callback' => 'seo_mcp_can_edit',
        'meta' => array( 'mcp' => array( 'public' => true ), 'show_in_rest' => true ),
    ) );

    // ── LIST POSTS ───────────────────────────────
    wp_register_ability( $prefix . '/list-posts', array(
        'label'       => __( 'List Posts', 'seo-mcp-toolkit' ),
        'description' => sprintf( __( 'List posts of any type. Available: %s, or "any".', 'seo-mcp-toolkit' ), $post_type_desc ),
        'category'    => $category,
        'input_schema' => array(
            'type'       => 'object',
            'properties' => array(
                'post_type' => array( 'type' => 'string', 'enum' => array_merge( $post_type_enum, array( 'any' ) ), 'default' => 'post' ),
                'status'    => array( 'type' => 'string', 'enum' => array( 'draft', 'publish', 'pending', 'private', 'any' ), 'default' => 'any' ),
                'per_page'  => array( 'type' => 'integer', 'default' => 10, 'description' => 'Max 100.' ),
                'page'      => array( 'type' => 'integer', 'default' => 1 ),
                'search'    => array( 'type' => 'string' ),
                'slug'      => array( 'type' => 'string' ),
                'category'  => array( 'type' => 'string' ),
                'orderby'   => array( 'type' => 'string', 'enum' => array( 'date', 'title', 'modified', 'ID' ), 'default' => 'date' ),
                'order'     => array( 'type' => 'string', 'enum' => array( 'ASC', 'DESC' ), 'default' => 'DESC' ),
            ),
        ),
        'output_schema' => array(
            'type'  => 'array',
            'items' => array(
                'type'       => 'object',
                'properties' => array(
                    'id'        => array( 'type' => 'integer' ),
                    'title'     => array( 'type' => 'string' ),
                    'status'    => array( 'type' => 'string' ),
                    'post_type' => array( 'type' => 'string' ),
                    'date'      => array( 'type' => 'string' ),
                    'url'       => array( 'type' => 'string' ),
                ),
            ),
        ),
        'execute_callback'    => 'seo_mcp_list_posts',
        'permission_callback' => 'seo_mcp_can_edit',
        'meta' => array( 'mcp' => array( 'public' => true ), 'show_in_rest' => true ),
    ) );

    // ── LIST CATEGORIES ──────────────────────────
    wp_register_ability( $prefix . '/list-categories', array(
        'label'       => __( 'List Categories', 'seo-mcp-toolkit' ),
        'description' => __( 'List all post categories.', 'seo-mcp-toolkit' ),
        'category'    => $category,
        'input_schema' => array(
            'type'       => 'object',
            'properties' => array(
                'hide_empty' => array( 'type' => 'boolean', 'default' => false ),
            ),
        ),
        'output_schema' => array(
            'type'  => 'array',
            'items' => array(
                'type'       => 'object',
                'properties' => array(
                    'id'    => array( 'type' => 'integer' ),
                    'name'  => array( 'type' => 'string' ),
                    'slug'  => array( 'type' => 'string' ),
                    'count' => array( 'type' => 'integer' ),
                ),
            ),
        ),
        'execute_callback'    => 'seo_mcp_list_categories',
        'permission_callback' => 'seo_mcp_can_edit',
        'meta' => array( 'mcp' => array( 'public' => true ), 'show_in_rest' => true ),
    ) );

    // ── LIST TAGS ────────────────────────────────
    wp_register_ability( $prefix . '/list-tags', array(
        'label'       => __( 'List Tags', 'seo-mcp-toolkit' ),
        'description' => __( 'List all post tags.', 'seo-mcp-toolkit' ),
        'category'    => $category,
        'input_schema' => array(
            'type'       => 'object',
            'properties' => array(
                'hide_empty' => array( 'type' => 'boolean', 'default' => false ),
            ),
        ),
        'output_schema' => array(
            'type'  => 'array',
            'items' => array(
                'type'       => 'object',
                'properties' => array(
                    'id'    => array( 'type' => 'integer' ),
                    'name'  => array( 'type' => 'string' ),
                    'slug'  => array( 'type' => 'string' ),
                    'count' => array( 'type' => 'integer' ),
                ),
            ),
        ),
        'execute_callback'    => 'seo_mcp_list_tags',
        'permission_callback' => 'seo_mcp_can_edit',
        'meta' => array( 'mcp' => array( 'public' => true ), 'show_in_rest' => true ),
    ) );

    // ── CREATE CATEGORY ──────────────────────────
    wp_register_ability( $prefix . '/create-category', array(
        'label'       => __( 'Create Category', 'seo-mcp-toolkit' ),
        'description' => __( 'Create a new post category.', 'seo-mcp-toolkit' ),
        'category'    => $category,
        'input_schema' => array(
            'type'       => 'object',
            'required'   => array( 'name' ),
            'properties' => array(
                'name'        => array( 'type' => 'string' ),
                'slug'        => array( 'type' => 'string' ),
                'description' => array( 'type' => 'string' ),
            ),
        ),
        'output_schema' => array(
            'type'       => 'object',
            'properties' => array(
                'success' => array( 'type' => 'boolean' ),
                'term_id' => array( 'type' => 'integer' ),
                'name'    => array( 'type' => 'string' ),
                'slug'    => array( 'type' => 'string' ),
                'message' => array( 'type' => 'string' ),
            ),
        ),
        'execute_callback'    => 'seo_mcp_create_category',
        'permission_callback' => 'seo_mcp_can_publish',
        'meta' => array( 'mcp' => array( 'public' => true ), 'show_in_rest' => true ),
    ) );

    // ── UPLOAD MEDIA ─────────────────────────────
    wp_register_ability( $prefix . '/upload-media', array(
        'label'       => __( 'Upload Media', 'seo-mcp-toolkit' ),
        'description' => __( 'Upload an image via URL or base64 data. Returns URL, attachment ID, and HTML img tag.', 'seo-mcp-toolkit' ),
        'category'    => $category,
        'input_schema' => array(
            'type'       => 'object',
            'properties' => array(
                'url'         => array( 'type' => 'string', 'description' => 'External image URL. Mutually exclusive with base64_data.' ),
                'base64_data' => array( 'type' => 'string', 'description' => 'Base64-encoded image data. Requires filename and mime_type.' ),
                'filename'    => array( 'type' => 'string' ),
                'mime_type'   => array( 'type' => 'string', 'enum' => array( 'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml' ) ),
                'post_id'     => array( 'type' => 'integer' ),
                'title'       => array( 'type' => 'string' ),
                'alt_text'    => array( 'type' => 'string' ),
                'caption'     => array( 'type' => 'string' ),
            ),
        ),
        'output_schema' => array(
            'type'       => 'object',
            'properties' => array(
                'success'       => array( 'type' => 'boolean' ),
                'attachment_id' => array( 'type' => 'integer' ),
                'url'           => array( 'type' => 'string' ),
                'filename'      => array( 'type' => 'string' ),
                'mime_type'     => array( 'type' => 'string' ),
                'width'         => array( 'type' => 'integer' ),
                'height'        => array( 'type' => 'integer' ),
                'html_img_tag'  => array( 'type' => 'string' ),
                'message'       => array( 'type' => 'string' ),
            ),
        ),
        'execute_callback'    => 'seo_mcp_upload_media',
        'permission_callback' => 'seo_mcp_can_upload',
        'meta' => array( 'mcp' => array( 'public' => true ), 'show_in_rest' => true ),
    ) );

    // ── UPLOAD MEDIA CHUNKED ─────────────────────
    wp_register_ability( $prefix . '/upload-media-chunked', array(
        'label'       => __( 'Upload Media (Chunked)', 'seo-mcp-toolkit' ),
        'description' => __( 'Upload large images in chunks. 3-step: start → append → finish.', 'seo-mcp-toolkit' ),
        'category'    => $category,
        'input_schema' => array(
            'type'       => 'object',
            'required'   => array( 'action' ),
            'properties' => array(
                'action'       => array( 'type' => 'string', 'enum' => array( 'start', 'append', 'finish' ) ),
                'upload_id'    => array( 'type' => 'string' ),
                'chunk_data'   => array( 'type' => 'string' ),
                'chunk_index'  => array( 'type' => 'integer' ),
                'total_chunks' => array( 'type' => 'integer' ),
                'filename'     => array( 'type' => 'string' ),
                'mime_type'    => array( 'type' => 'string', 'enum' => array( 'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml' ) ),
                'post_id'      => array( 'type' => 'integer' ),
                'title'        => array( 'type' => 'string' ),
                'alt_text'     => array( 'type' => 'string' ),
                'caption'      => array( 'type' => 'string' ),
            ),
        ),
        'output_schema' => array(
            'type'       => 'object',
            'properties' => array(
                'success'         => array( 'type' => 'boolean' ),
                'upload_id'       => array( 'type' => 'string' ),
                'chunks_received' => array( 'type' => 'integer' ),
                'total_chunks'    => array( 'type' => 'integer' ),
                'attachment_id'   => array( 'type' => 'integer' ),
                'url'             => array( 'type' => 'string' ),
                'filename'        => array( 'type' => 'string' ),
                'message'         => array( 'type' => 'string' ),
            ),
        ),
        'execute_callback'    => 'seo_mcp_upload_media_chunked',
        'permission_callback' => 'seo_mcp_can_upload',
        'meta' => array( 'mcp' => array( 'public' => true ), 'show_in_rest' => true ),
    ) );

    // ── ATTACH MEDIA ─────────────────────────────
    wp_register_ability( $prefix . '/attach-media', array(
        'label'       => __( 'Attach Media to Post', 'seo-mcp-toolkit' ),
        'description' => __( 'Attach one or more uploaded images to a post. Optionally insert as Gutenberg blocks and/or set as featured image.', 'seo-mcp-toolkit' ),
        'category'    => $category,
        'input_schema' => array(
            'type'       => 'object',
            'required'   => array( 'post_id' ),
            'properties' => array(
                'post_id'           => array( 'type' => 'integer', 'description' => 'Post to attach images to.' ),
                'attachment_id'     => array( 'type' => 'integer', 'description' => 'Single attachment ID (use attachments[] for bulk).' ),
                'attachments'       => array( 'type' => 'array', 'description' => 'Bulk: [{id, alt_text, caption, set_featured, insert_in_content}].' ),
                'insert_position'   => array( 'type' => 'string', 'enum' => array( 'append', 'prepend' ), 'default' => 'append' ),
                'alt_text'          => array( 'type' => 'string' ),
                'caption'           => array( 'type' => 'string' ),
                'set_featured'      => array( 'type' => 'boolean' ),
                'insert_in_content' => array( 'type' => 'boolean' ),
            ),
        ),
        'output_schema' => array(
            'type'       => 'object',
            'properties' => array(
                'success'        => array( 'type' => 'boolean' ),
                'post_id'        => array( 'type' => 'integer' ),
                'total_attached' => array( 'type' => 'integer' ),
                'featured_id'    => array( 'type' => 'integer' ),
                'inserted_count' => array( 'type' => 'integer' ),
                'results'        => array( 'type' => 'array' ),
                'message'        => array( 'type' => 'string' ),
            ),
        ),
        'execute_callback'    => 'seo_mcp_attach_media',
        'permission_callback' => 'seo_mcp_can_edit',
        'meta' => array( 'mcp' => array( 'public' => true ), 'show_in_rest' => true ),
    ) );

    // ── LIST TAXONOMY TERMS ──────────────────────
    $all_taxonomies = array_merge( array( 'category', 'post_tag' ), SEO_MCP_CUSTOM_TAXONOMIES );
    wp_register_ability( $prefix . '/list-taxonomy-terms', array(
        'label'       => __( 'List Taxonomy Terms', 'seo-mcp-toolkit' ),
        'description' => sprintf( __( 'List terms for a taxonomy. Available: %s', 'seo-mcp-toolkit' ), implode( ', ', $all_taxonomies ) ),
        'category'    => $category,
        'input_schema' => array(
            'type'       => 'object',
            'required'   => array( 'taxonomy' ),
            'properties' => array(
                'taxonomy'   => array( 'type' => 'string', 'description' => 'Taxonomy slug to list terms for.' ),
                'hide_empty' => array( 'type' => 'boolean', 'default' => false ),
            ),
        ),
        'output_schema' => array(
            'type'  => 'array',
            'items' => array(
                'type'       => 'object',
                'properties' => array(
                    'id'       => array( 'type' => 'integer' ),
                    'name'     => array( 'type' => 'string' ),
                    'slug'     => array( 'type' => 'string' ),
                    'count'    => array( 'type' => 'integer' ),
                    'parent'   => array( 'type' => 'integer' ),
                    'taxonomy' => array( 'type' => 'string' ),
                ),
            ),
        ),
        'execute_callback'    => 'seo_mcp_list_taxonomy_terms',
        'permission_callback' => 'seo_mcp_can_edit',
        'meta' => array( 'mcp' => array( 'public' => true ), 'show_in_rest' => true ),
    ) );

    // ── SET POST FAQs (ACF/SCF Repeater) ────────────
    wp_register_ability( $prefix . '/set-post-faqs', array(
        'label'       => __( 'Set Post FAQs', 'seo-mcp-toolkit' ),
        'description' => sprintf(
            __( 'Set or replace all FAQs on a post. Writes to the %s repeater field. Pass post_id and faqs array. This REPLACES all existing FAQs.', 'seo-mcp-toolkit' ),
            $faq_plugin_name
        ),
        'category'    => $category,
        'input_schema' => array(
            'type'       => 'object',
            'required'   => array( 'post_id', 'faqs' ),
            'properties' => array(
                'post_id' => array( 'type' => 'integer', 'description' => 'Post ID to set FAQs on.' ),
                'faqs'    => array(
                    'type'  => 'array',
                    'description' => 'Array of FAQ objects with question, answer, and optional position.',
                    'items' => array(
                        'type'       => 'object',
                        'required'   => array( 'question', 'answer' ),
                        'properties' => array(
                            'question' => array( 'type' => 'string' ),
                            'answer'   => array( 'type' => 'string' ),
                            'position' => array( 'type' => 'integer' ),
                        ),
                    ),
                ),
            ),
        ),
        'output_schema' => array(
            'type'       => 'object',
            'properties' => array(
                'success'   => array( 'type' => 'boolean' ),
                'post_id'   => array( 'type' => 'integer' ),
                'faq_count' => array( 'type' => 'integer' ),
                'message'   => array( 'type' => 'string' ),
            ),
        ),
        'execute_callback'    => 'seo_mcp_set_post_faqs',
        'permission_callback' => 'seo_mcp_can_edit',
        'meta' => array( 'mcp' => array( 'public' => true ), 'show_in_rest' => true ),
    ) );

    // ── GET POST FAQs (ACF/SCF Repeater) ────────────
    wp_register_ability( $prefix . '/get-post-faqs', array(
        'label'       => __( 'Get Post FAQs', 'seo-mcp-toolkit' ),
        'description' => sprintf(
            __( 'Retrieve all FAQs from a post. Reads the %s repeater field. Returns array of FAQ objects.', 'seo-mcp-toolkit' ),
            $faq_plugin_name
        ),
        'category'    => $category,
        'input_schema' => array(
            'type'       => 'object',
            'required'   => array( 'post_id' ),
            'properties' => array(
                'post_id' => array( 'type' => 'integer', 'description' => 'Post ID to read FAQs from.' ),
            ),
        ),
        'output_schema' => array(
            'type'       => 'object',
            'properties' => array(
                'success'   => array( 'type' => 'boolean' ),
                'post_id'   => array( 'type' => 'integer' ),
                'faq_count' => array( 'type' => 'integer' ),
                'faqs'      => array(
                    'type'  => 'array',
                    'items' => array(
                        'type'       => 'object',
                        'properties' => array(
                            'question' => array( 'type' => 'string' ),
                            'answer'   => array( 'type' => 'string' ),
                            'position' => array( 'type' => 'integer' ),
                        ),
                    ),
                ),
            ),
        ),
        'execute_callback'    => 'seo_mcp_get_post_faqs',
        'permission_callback' => 'seo_mcp_can_edit',
        'meta' => array( 'mcp' => array( 'public' => true ), 'show_in_rest' => true ),
    ) );

    // ── SET RESOURCE CTA (ACF/SCF Fields) ───────────
    wp_register_ability( $prefix . '/set-resource-cta', array(
        'label'       => __( 'Set Downloadable Resource CTA', 'seo-mcp-toolkit' ),
        'description' => sprintf(
            __( 'Set the "Resources Hero CTA" %s fields on a post. Pass post_id and resource_url. Auto-fetches resource title and featured image. Works on: post, download, pitch-deck.', 'seo-mcp-toolkit' ),
            $faq_plugin_name
        ),
        'category'    => $category,
        'input_schema' => array(
            'type'       => 'object',
            'required'   => array( 'post_id', 'resource_url' ),
            'properties' => array(
                'post_id'            => array( 'type' => 'integer', 'description' => 'Post/resource/pitch-deck ID to set CTA on.' ),
                'resource_url'       => array( 'type' => 'string', 'description' => 'URL of the downloadable resource page.' ),
                'heading'            => array( 'type' => 'string', 'description' => 'Override CTA heading. If omitted, auto-fetched from resource page title.' ),
                'resource_link_text' => array( 'type' => 'string', 'default' => 'Download Now', 'description' => 'Link text for the CTA URL field.' ),
                'cta_image_id'       => array( 'type' => 'integer', 'description' => 'Attachment ID for CTA image. If omitted, uses resource page featured image.' ),
            ),
        ),
        'output_schema' => array(
            'type'       => 'object',
            'properties' => array(
                'success'       => array( 'type' => 'boolean' ),
                'post_id'       => array( 'type' => 'integer' ),
                'heading'       => array( 'type' => 'string' ),
                'resource_url'  => array( 'type' => 'string' ),
                'cta_image_id'  => array( 'type' => 'integer' ),
                'fields_set'    => array( 'type' => 'integer' ),
                'message'       => array( 'type' => 'string' ),
            ),
        ),
        'execute_callback'    => 'seo_mcp_set_resource_cta',
        'permission_callback' => 'seo_mcp_can_edit',
        'meta' => array( 'mcp' => array( 'public' => true ), 'show_in_rest' => true ),
    ) );

    // ── SET RELATED PAGES (ACF/SCF Repeater) ────────
    wp_register_ability( $prefix . '/set-related-pages', array(
        'label'       => __( 'Set Related Pages', 'seo-mcp-toolkit' ),
        'description' => sprintf(
            __( 'Set the "Related Posts" %s repeater on a post. Pass post_id and related_posts array. Titles auto-fetched if omitted. REPLACES all existing related posts.', 'seo-mcp-toolkit' ),
            $faq_plugin_name
        ),
        'category'    => $category,
        'input_schema' => array(
            'type'       => 'object',
            'required'   => array( 'post_id', 'related_posts' ),
            'properties' => array(
                'post_id'       => array( 'type' => 'integer', 'description' => 'Post ID to set related posts on.' ),
                'related_posts' => array(
                    'type'  => 'array',
                    'description' => 'Array of {post_id, title?} objects. Titles auto-fetched if omitted.',
                    'items' => array(
                        'type'       => 'object',
                        'required'   => array( 'post_id' ),
                        'properties' => array(
                            'post_id' => array( 'type' => 'integer' ),
                            'title'   => array( 'type' => 'string' ),
                        ),
                    ),
                ),
            ),
        ),
        'output_schema' => array(
            'type'       => 'object',
            'properties' => array(
                'success'       => array( 'type' => 'boolean' ),
                'post_id'       => array( 'type' => 'integer' ),
                'related_count' => array( 'type' => 'integer' ),
                'related_posts' => array( 'type' => 'array' ),
                'message'       => array( 'type' => 'string' ),
            ),
        ),
        'execute_callback'    => 'seo_mcp_set_related_pages',
        'permission_callback' => 'seo_mcp_can_edit',
        'meta' => array( 'mcp' => array( 'public' => true ), 'show_in_rest' => true ),
    ) );
}


/* ═══════════════════════════════════════════════════════════════════
 * EXECUTE CALLBACKS
 * ═══════════════════════════════════════════════════════════════════ */

/* ── CREATE POST ────────────────────────────────── */
function seo_mcp_create_post( $params ) {
    $post_type = sanitize_text_field( $params['post_type'] ?? 'post' );

    if ( ! in_array( $post_type, SEO_MCP_POST_TYPES, true ) ) {
        return new WP_Error( 'invalid_post_type', 'Invalid post type: ' . $post_type );
    }

    $resolved_content = seo_mcp_resolve_content_token( $params );
    if ( is_wp_error( $resolved_content ) ) {
        return $resolved_content;
    }

    $post_data = array(
        'post_title'   => sanitize_text_field( $params['title'] ),
        'post_content' => wp_kses_post( $resolved_content ),
        'post_status'  => sanitize_text_field( $params['status'] ?? 'draft' ),
        'post_type'    => $post_type,
    );

    if ( ! empty( $params['excerpt'] ) )  $post_data['post_excerpt'] = sanitize_text_field( $params['excerpt'] );
    if ( ! empty( $params['slug'] ) )     $post_data['post_name']    = sanitize_title( $params['slug'] );
    if ( ! empty( $params['parent'] ) )   $post_data['post_parent']  = absint( $params['parent'] );

    $post_id = wp_insert_post( $post_data, true );
    if ( is_wp_error( $post_id ) ) {
        return $post_id;
    }

    // Categories (post type only).
    if ( 'post' === $post_type && ! empty( $params['categories'] ) && is_array( $params['categories'] ) ) {
        $cat_ids = array();
        foreach ( $params['categories'] as $cat_name ) {
            $cat_name = sanitize_text_field( $cat_name );
            $term     = get_term_by( 'name', $cat_name, 'category' );
            $cat_ids[] = $term ? $term->term_id : ( ( $new = wp_insert_term( $cat_name, 'category' ) ) && ! is_wp_error( $new ) ? $new['term_id'] : 0 );
        }
        $cat_ids = array_filter( $cat_ids );
        if ( ! empty( $cat_ids ) ) {
            wp_set_post_categories( $post_id, $cat_ids );
        }
    }

    // Tags (post type only).
    if ( 'post' === $post_type && ! empty( $params['tags'] ) && is_array( $params['tags'] ) ) {
        wp_set_post_tags( $post_id, array_map( 'sanitize_text_field', $params['tags'] ) );
    }

    // Custom taxonomy terms.
    if ( ! empty( $params['taxonomy_terms'] ) ) {
        seo_mcp_set_taxonomy_terms( $post_id, $params['taxonomy_terms'] );
    }

    // Process external images.
    $current_post      = get_post( $post_id );
    $processed_content = seo_mcp_process_content_images( $post_id, $current_post->post_content );
    if ( $processed_content !== $current_post->post_content ) {
        wp_update_post( array( 'ID' => $post_id, 'post_content' => $processed_content ) );
    }

    // Featured image from URL.
    if ( ! empty( $params['featured_image_url'] ) ) {
        if ( ! function_exists( 'media_sideload_image' ) ) {
            require_once ABSPATH . 'wp-admin/includes/media.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';
        }
        $thumb_id = media_sideload_image( esc_url_raw( $params['featured_image_url'] ), $post_id, '', 'id' );
        if ( ! is_wp_error( $thumb_id ) ) {
            set_post_thumbnail( $post_id, $thumb_id );
        }
    }

    // FAQs (ACF or SCF).
    $faq_count = seo_mcp_save_faqs( $post_id, $params['faqs'] ?? array() );

    // Yoast SEO.
    $seo_count = 0;
    if ( ! empty( $params['seo'] ) ) {
        $seo_count = seo_mcp_set_yoast_seo( $post_id, $params['seo'] );
    }

    $type_label = SEO_MCP_POST_TYPE_LABELS[ $post_type ] ?? $post_type;
    $msg = sprintf( '%s "%s" created successfully.', $type_label, $params['title'] );
    if ( $faq_count > 0 ) $msg .= sprintf( ' %d FAQ(s) added.', $faq_count );
    if ( $seo_count > 0 ) $msg .= sprintf( ' %d SEO field(s) set.', $seo_count );

    return array(
        'success'        => true,
        'post_id'        => $post_id,
        'post_type'      => $post_type,
        'post_url'       => get_permalink( $post_id ),
        'edit_url'       => admin_url( 'post.php?post=' . $post_id . '&action=edit' ),
        'status'         => get_post_status( $post_id ),
        'faq_count'      => $faq_count,
        'seo_fields_set' => $seo_count,
        'message'        => $msg,
    );
}


/* ── UPDATE POST ────────────────────────────────── */
function seo_mcp_update_post( $params ) {
    if ( empty( $params['post_id'] ) ) {
        return new WP_Error( 'missing_id', 'post_id is required.' );
    }

    $post = get_post( absint( $params['post_id'] ) );
    if ( ! $post ) {
        return new WP_Error( 'not_found', 'Post not found.' );
    }
    if ( ! in_array( $post->post_type, SEO_MCP_POST_TYPES, true ) ) {
        return new WP_Error( 'invalid_post_type', sprintf( 'Post type "%s" is not managed by this plugin.', $post->post_type ) );
    }

    $update_data = array( 'ID' => $post->ID );

    if ( isset( $params['title'] ) )   $update_data['post_title']   = sanitize_text_field( $params['title'] );
    if ( isset( $params['status'] ) )  $update_data['post_status']  = sanitize_text_field( $params['status'] );
    if ( isset( $params['excerpt'] ) ) $update_data['post_excerpt'] = sanitize_text_field( $params['excerpt'] );
    if ( isset( $params['slug'] ) )    $update_data['post_name']    = sanitize_title( $params['slug'] );

    // Resolve content.
    if ( ! empty( $params['content_token'] ) ) {
        $resolved_content = seo_mcp_resolve_content_token( $params );
        if ( is_wp_error( $resolved_content ) ) return $resolved_content;
        $update_data['post_content'] = wp_kses_post( $resolved_content );
        $params['content'] = $resolved_content;
    } elseif ( isset( $params['content'] ) ) {
        $update_data['post_content'] = wp_kses_post( $params['content'] );
    }

    $result = wp_update_post( $update_data, true );
    if ( is_wp_error( $result ) ) return $result;

    // Categories.
    if ( 'post' === $post->post_type && ! empty( $params['categories'] ) && is_array( $params['categories'] ) ) {
        $cat_ids = array();
        foreach ( $params['categories'] as $cat_name ) {
            $cat_name = sanitize_text_field( $cat_name );
            $term     = get_term_by( 'name', $cat_name, 'category' );
            $cat_ids[] = $term ? $term->term_id : ( ( $new = wp_insert_term( $cat_name, 'category' ) ) && ! is_wp_error( $new ) ? $new['term_id'] : 0 );
        }
        $cat_ids = array_filter( $cat_ids );
        if ( ! empty( $cat_ids ) ) wp_set_post_categories( $post->ID, $cat_ids );
    }

    // Tags.
    if ( 'post' === $post->post_type && ! empty( $params['tags'] ) && is_array( $params['tags'] ) ) {
        wp_set_post_tags( $post->ID, array_map( 'sanitize_text_field', $params['tags'] ) );
    }

    // Custom taxonomy terms.
    if ( ! empty( $params['taxonomy_terms'] ) ) {
        seo_mcp_set_taxonomy_terms( $post->ID, $params['taxonomy_terms'] );
    }

    // Process external images.
    if ( isset( $params['content'] ) ) {
        $current_post      = get_post( $post->ID );
        $processed_content = seo_mcp_process_content_images( $post->ID, $current_post->post_content );
        if ( $processed_content !== $current_post->post_content ) {
            wp_update_post( array( 'ID' => $post->ID, 'post_content' => $processed_content ) );
        }
    }

    // FAQs.
    $faq_count = seo_mcp_save_faqs( $post->ID, $params['faqs'] ?? array() );

    // Yoast SEO.
    $seo_count = 0;
    if ( ! empty( $params['seo'] ) ) {
        $seo_count = seo_mcp_set_yoast_seo( $post->ID, $params['seo'] );
    }

    $type_label = SEO_MCP_POST_TYPE_LABELS[ $post->post_type ] ?? $post->post_type;
    $msg = sprintf( '%s #%d updated successfully.', $type_label, $post->ID );
    if ( $faq_count > 0 ) $msg .= sprintf( ' %d FAQ(s) set.', $faq_count );
    if ( $seo_count > 0 ) $msg .= sprintf( ' %d SEO field(s) set.', $seo_count );

    return array(
        'success'        => true,
        'post_id'        => $post->ID,
        'post_type'      => $post->post_type,
        'post_url'       => get_permalink( $post->ID ),
        'faq_count'      => $faq_count,
        'seo_fields_set' => $seo_count,
        'message'        => $msg,
    );
}


/* ── GET POST ───────────────────────────────────── */
function seo_mcp_get_post( $params ) {
    if ( empty( $params['post_id'] ) ) {
        return new WP_Error( 'missing_id', 'post_id is required.' );
    }

    $post = get_post( absint( $params['post_id'] ) );
    if ( ! $post ) {
        return new WP_Error( 'not_found', 'Post not found.' );
    }

    $data = array(
        'id'        => $post->ID,
        'title'     => $post->post_title,
        'content'   => $post->post_content,
        'excerpt'   => $post->post_excerpt,
        'status'    => $post->post_status,
        'post_type' => $post->post_type,
        'date'      => $post->post_date,
        'modified'  => $post->post_modified,
        'url'       => get_permalink( $post->ID ),
    );

    if ( 'post' === $post->post_type ) {
        $categories = wp_get_post_categories( $post->ID, array( 'fields' => 'names' ) );
        $tags       = wp_get_post_tags( $post->ID, array( 'fields' => 'names' ) );
        $data['categories'] = is_array( $categories ) ? $categories : array();
        $data['tags']       = is_array( $tags ) ? $tags : array();
    } else {
        $data['categories'] = array();
        $data['tags']       = array();
    }

    // Custom taxonomy terms.
    $taxonomy_terms  = array();
    $post_taxonomies = get_object_taxonomies( $post->post_type, 'names' );
    foreach ( $post_taxonomies as $tax ) {
        if ( in_array( $tax, array( 'category', 'post_tag' ), true ) ) continue;
        $terms = wp_get_object_terms( $post->ID, $tax, array( 'fields' => 'names' ) );
        if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
            $taxonomy_terms[ $tax ] = $terms;
        }
    }
    $data['taxonomy_terms'] = $taxonomy_terms;

    return $data;
}


/* ── LIST POSTS ─────────────────────────────────── */
function seo_mcp_list_posts( $params ) {
    $post_type = sanitize_text_field( $params['post_type'] ?? 'post' );

    if ( 'any' === $post_type ) {
        $post_type = SEO_MCP_POST_TYPES;
    } elseif ( ! in_array( $post_type, SEO_MCP_POST_TYPES, true ) ) {
        return new WP_Error( 'invalid_post_type', 'Invalid post type.' );
    }

    $args = array(
        'post_type'      => $post_type,
        'post_status'    => sanitize_text_field( $params['status'] ?? 'any' ),
        'posts_per_page' => min( absint( $params['per_page'] ?? 10 ), 100 ),
        'paged'          => absint( $params['page'] ?? 1 ),
        'orderby'        => sanitize_text_field( $params['orderby'] ?? 'date' ),
        'order'          => sanitize_text_field( $params['order'] ?? 'DESC' ),
    );

    if ( ! empty( $params['search'] ) )   $args['s']             = sanitize_text_field( $params['search'] );
    if ( ! empty( $params['slug'] ) )     $args['name']          = sanitize_title( $params['slug'] );
    if ( ! empty( $params['category'] ) ) $args['category_name'] = sanitize_text_field( $params['category'] );

    $query = new WP_Query( $args );
    $posts = array();

    foreach ( $query->posts as $post ) {
        $posts[] = array(
            'id'        => $post->ID,
            'title'     => $post->post_title,
            'status'    => $post->post_status,
            'post_type' => $post->post_type,
            'date'      => $post->post_date,
            'url'       => get_permalink( $post->ID ),
        );
    }

    return $posts;
}


/* ── LIST CATEGORIES ────────────────────────────── */
function seo_mcp_list_categories( $params ) {
    $categories = get_categories( array(
        'hide_empty' => ! empty( $params['hide_empty'] ),
        'orderby'    => 'name',
        'order'      => 'ASC',
    ) );

    $result = array();
    foreach ( $categories as $cat ) {
        $result[] = array(
            'id'    => $cat->term_id,
            'name'  => $cat->name,
            'slug'  => $cat->slug,
            'count' => $cat->count,
        );
    }
    return $result;
}


/* ── LIST TAGS ──────────────────────────────────── */
function seo_mcp_list_tags( $params ) {
    $tags = get_tags( array(
        'hide_empty' => ! empty( $params['hide_empty'] ),
        'orderby'    => 'name',
        'order'      => 'ASC',
    ) );

    if ( is_wp_error( $tags ) ) return array();

    $result = array();
    foreach ( $tags as $tag ) {
        $result[] = array(
            'id'    => $tag->term_id,
            'name'  => $tag->name,
            'slug'  => $tag->slug,
            'count' => $tag->count,
        );
    }
    return $result;
}


/* ── CREATE CATEGORY ────────────────────────────── */
function seo_mcp_create_category( $params ) {
    if ( empty( $params['name'] ) ) {
        return new WP_Error( 'missing_name', 'Category name is required.' );
    }

    $args = array();
    if ( ! empty( $params['slug'] ) )        $args['slug']        = sanitize_title( $params['slug'] );
    if ( ! empty( $params['description'] ) ) $args['description'] = sanitize_text_field( $params['description'] );

    $name = sanitize_text_field( $params['name'] );
    $term = wp_insert_term( $name, 'category', $args );

    if ( is_wp_error( $term ) ) return $term;

    $cat = get_term( $term['term_id'], 'category' );

    return array(
        'success' => true,
        'term_id' => $cat->term_id,
        'name'    => $cat->name,
        'slug'    => $cat->slug,
        'message' => sprintf( 'Category "%s" created.', $cat->name ),
    );
}


/* ── UPLOAD MEDIA (MCP) ─────────────────────────── */
function seo_mcp_upload_media( $params ) {
    if ( ! function_exists( 'media_sideload_image' ) ) {
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }

    $has_url    = ! empty( $params['url'] );
    $has_base64 = ! empty( $params['base64_data'] );

    if ( ! $has_url && ! $has_base64 ) {
        return new WP_Error( 'no_source', 'Provide either "url" or "base64_data".' );
    }
    if ( $has_url && $has_base64 ) {
        return new WP_Error( 'dual_source', 'Provide only one: "url" or "base64_data".' );
    }

    $post_id       = ! empty( $params['post_id'] ) ? absint( $params['post_id'] ) : 0;
    $attachment_id = 0;

    // URL download.
    if ( $has_url ) {
        $attachment_id = media_sideload_image( esc_url_raw( $params['url'] ), $post_id, '', 'id' );
        if ( is_wp_error( $attachment_id ) ) return $attachment_id;
    }

    // Base64 upload.
    if ( $has_base64 ) {
        if ( empty( $params['filename'] ) || empty( $params['mime_type'] ) ) {
            return new WP_Error( 'missing_meta', 'filename and mime_type required with base64_data.' );
        }

        $decoded = base64_decode( $params['base64_data'], true );
        if ( false === $decoded || empty( $decoded ) ) {
            return new WP_Error( 'invalid_base64', 'Could not decode base64 data.' );
        }

        $filename  = sanitize_file_name( $params['filename'] );
        $mime_type = sanitize_mime_type( $params['mime_type'] );

        $ext_map = array( 'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp', 'image/svg+xml' => 'svg' );
        $expected_ext = $ext_map[ $mime_type ] ?? '';
        if ( $expected_ext ) {
            $filename = preg_replace( '/\.[^.]+$/', '', $filename ) . '.' . $expected_ext;
        }

        $tmp_file = wp_tempnam( $filename );
        file_put_contents( $tmp_file, $decoded );

        $file_array = array( 'name' => $filename, 'type' => $mime_type, 'tmp_name' => $tmp_file, 'error' => 0, 'size' => strlen( $decoded ) );
        $overrides  = array( 'test_form' => false, 'test_type' => true, 'mimes' => array( 'jpg|jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'webp' => 'image/webp', 'svg' => 'image/svg+xml' ) );

        $upload = wp_handle_sideload( $file_array, $overrides );
        if ( ! empty( $upload['error'] ) ) {
            @unlink( $tmp_file );
            return new WP_Error( 'upload_error', $upload['error'] );
        }

        $attachment_data = array(
            'post_mime_type' => $upload['type'],
            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $upload['file'] ) ),
            'post_content'   => '',
            'post_status'    => 'inherit',
        );
        if ( $post_id ) $attachment_data['post_parent'] = $post_id;

        $attachment_id = wp_insert_attachment( $attachment_data, $upload['file'], $post_id );
        if ( is_wp_error( $attachment_id ) ) return $attachment_id;

        $metadata = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
        wp_update_attachment_metadata( $attachment_id, $metadata );
    }

    // Set metadata.
    if ( ! empty( $params['alt_text'] ) ) update_post_meta( $attachment_id, '_wp_attachment_image_alt', sanitize_text_field( $params['alt_text'] ) );
    if ( ! empty( $params['title'] ) )    wp_update_post( array( 'ID' => $attachment_id, 'post_title' => sanitize_text_field( $params['title'] ) ) );
    if ( ! empty( $params['caption'] ) )  wp_update_post( array( 'ID' => $attachment_id, 'post_excerpt' => sanitize_text_field( $params['caption'] ) ) );

    $url      = wp_get_attachment_url( $attachment_id );
    $metadata = wp_get_attachment_metadata( $attachment_id );
    $width    = $metadata['width'] ?? 0;
    $height   = $metadata['height'] ?? 0;
    $alt      = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
    $fname    = basename( get_attached_file( $attachment_id ) );
    $mime     = get_post_mime_type( $attachment_id );
    $img_tag  = sprintf( '<img src="%s" alt="%s" class="wp-image-%d" width="%d" height="%d" />', esc_url( $url ), esc_attr( $alt ), $attachment_id, $width, $height );

    return array(
        'success'       => true,
        'attachment_id' => $attachment_id,
        'url'           => $url,
        'filename'      => $fname,
        'mime_type'     => $mime,
        'width'         => (int) $width,
        'height'        => (int) $height,
        'html_img_tag'  => $img_tag,
        'message'       => sprintf( 'Image "%s" uploaded successfully.', $fname ),
    );
}


/* ── UPLOAD MEDIA CHUNKED ───────────────────────── */
function seo_mcp_upload_media_chunked( $params ) {
    $action = sanitize_text_field( $params['action'] ?? '' );

    if ( ! in_array( $action, array( 'start', 'append', 'finish' ), true ) ) {
        return new WP_Error( 'invalid_action', 'action must be "start", "append", or "finish".' );
    }

    // ── START ──
    if ( 'start' === $action ) {
        if ( empty( $params['filename'] ) || empty( $params['mime_type'] ) ) {
            return new WP_Error( 'missing_meta', 'filename and mime_type required for "start".' );
        }
        if ( empty( $params['total_chunks'] ) || (int) $params['total_chunks'] < 1 ) {
            return new WP_Error( 'missing_total', 'total_chunks required and must be >= 1.' );
        }
        if ( ! isset( $params['chunk_data'] ) || '' === $params['chunk_data'] ) {
            return new WP_Error( 'missing_chunk', 'chunk_data required for "start".' );
        }

        $upload_id = 'smcp_' . wp_generate_password( 16, false );
        $session   = array(
            'filename'     => sanitize_file_name( $params['filename'] ),
            'mime_type'    => sanitize_mime_type( $params['mime_type'] ),
            'total_chunks' => (int) $params['total_chunks'],
            'chunks'       => array( 0 => $params['chunk_data'] ),
            'post_id'      => ! empty( $params['post_id'] ) ? absint( $params['post_id'] ) : 0,
            'title'        => ! empty( $params['title'] ) ? sanitize_text_field( $params['title'] ) : '',
            'alt_text'     => ! empty( $params['alt_text'] ) ? sanitize_text_field( $params['alt_text'] ) : '',
            'caption'      => ! empty( $params['caption'] ) ? sanitize_text_field( $params['caption'] ) : '',
        );

        set_transient( $upload_id, $session, HOUR_IN_SECONDS );

        return array(
            'success'         => true,
            'upload_id'       => $upload_id,
            'chunks_received' => 1,
            'total_chunks'    => $session['total_chunks'],
            'message'         => sprintf( 'Upload started. Send remaining %d chunks.', $session['total_chunks'] - 1 ),
        );
    }

    // ── APPEND ──
    if ( 'append' === $action ) {
        if ( empty( $params['upload_id'] ) ) return new WP_Error( 'missing_id', 'upload_id required.' );
        if ( ! isset( $params['chunk_index'] ) ) return new WP_Error( 'missing_index', 'chunk_index required.' );
        if ( ! isset( $params['chunk_data'] ) || '' === $params['chunk_data'] ) return new WP_Error( 'missing_chunk', 'chunk_data required.' );

        $upload_id = sanitize_text_field( $params['upload_id'] );
        $session   = get_transient( $upload_id );
        if ( false === $session ) return new WP_Error( 'session_expired', 'Upload session not found or expired.' );

        $session['chunks'][ (int) $params['chunk_index'] ] = $params['chunk_data'];
        set_transient( $upload_id, $session, HOUR_IN_SECONDS );

        $received = count( $session['chunks'] );
        return array(
            'success'         => true,
            'upload_id'       => $upload_id,
            'chunks_received' => $received,
            'total_chunks'    => $session['total_chunks'],
            'message'         => sprintf( 'Chunk %d received (%d/%d).', (int) $params['chunk_index'], $received, $session['total_chunks'] ),
        );
    }

    // ── FINISH ──
    if ( 'finish' === $action ) {
        if ( empty( $params['upload_id'] ) ) return new WP_Error( 'missing_id', 'upload_id required.' );

        $upload_id = sanitize_text_field( $params['upload_id'] );
        $session   = get_transient( $upload_id );
        if ( false === $session ) return new WP_Error( 'session_expired', 'Upload session not found or expired.' );

        // Allow metadata override on finish.
        if ( ! empty( $params['post_id'] ) )  $session['post_id']  = absint( $params['post_id'] );
        if ( ! empty( $params['title'] ) )    $session['title']    = sanitize_text_field( $params['title'] );
        if ( ! empty( $params['alt_text'] ) ) $session['alt_text'] = sanitize_text_field( $params['alt_text'] );
        if ( ! empty( $params['caption'] ) )  $session['caption']  = sanitize_text_field( $params['caption'] );

        // Assemble chunks.
        $total       = $session['total_chunks'];
        $base64_full = '';
        for ( $i = 0; $i < $total; $i++ ) {
            if ( ! isset( $session['chunks'][ $i ] ) ) {
                return new WP_Error( 'missing_chunk', sprintf( 'Chunk %d missing.', $i ) );
            }
            $base64_full .= $session['chunks'][ $i ];
        }

        delete_transient( $upload_id );

        $decoded = base64_decode( $base64_full, true );
        if ( false === $decoded || empty( $decoded ) ) {
            return new WP_Error( 'invalid_base64', 'Could not decode assembled base64 data.' );
        }

        if ( ! function_exists( 'media_sideload_image' ) ) {
            require_once ABSPATH . 'wp-admin/includes/media.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';
        }

        $filename  = $session['filename'];
        $mime_type = $session['mime_type'];
        $ext_map   = array( 'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp', 'image/svg+xml' => 'svg' );
        $ext       = $ext_map[ $mime_type ] ?? '';
        if ( $ext ) $filename = preg_replace( '/\.[^.]+$/', '', $filename ) . '.' . $ext;

        $tmp_file = wp_tempnam( $filename );
        file_put_contents( $tmp_file, $decoded );

        $file_array = array( 'name' => $filename, 'type' => $mime_type, 'tmp_name' => $tmp_file, 'error' => 0, 'size' => strlen( $decoded ) );
        $overrides  = array( 'test_form' => false, 'test_type' => true, 'mimes' => array( 'jpg|jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'webp' => 'image/webp', 'svg' => 'image/svg+xml' ) );

        $upload = wp_handle_sideload( $file_array, $overrides );
        if ( ! empty( $upload['error'] ) ) {
            @unlink( $tmp_file );
            return new WP_Error( 'upload_error', $upload['error'] );
        }

        $post_id = $session['post_id'];
        $att_data = array(
            'post_mime_type' => $upload['type'],
            'post_title'     => ! empty( $session['title'] ) ? $session['title'] : preg_replace( '/\.[^.]+$/', '', basename( $upload['file'] ) ),
            'post_content'   => '',
            'post_status'    => 'inherit',
        );
        if ( $post_id ) $att_data['post_parent'] = $post_id;

        $attachment_id = wp_insert_attachment( $att_data, $upload['file'], $post_id );
        if ( is_wp_error( $attachment_id ) ) return $attachment_id;

        $metadata = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
        wp_update_attachment_metadata( $attachment_id, $metadata );

        if ( ! empty( $session['alt_text'] ) ) update_post_meta( $attachment_id, '_wp_attachment_image_alt', $session['alt_text'] );
        if ( ! empty( $session['caption'] ) )  wp_update_post( array( 'ID' => $attachment_id, 'post_excerpt' => $session['caption'] ) );

        $url    = wp_get_attachment_url( $attachment_id );
        $meta   = wp_get_attachment_metadata( $attachment_id );
        $width  = $meta['width'] ?? 0;
        $height = $meta['height'] ?? 0;
        $alt    = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
        $fname  = basename( get_attached_file( $attachment_id ) );
        $mime   = get_post_mime_type( $attachment_id );
        $img_tag = sprintf( '<img src="%s" alt="%s" class="wp-image-%d" width="%d" height="%d" />', esc_url( $url ), esc_attr( $alt ), $attachment_id, $width, $height );

        return array(
            'success'       => true,
            'attachment_id' => $attachment_id,
            'url'           => $url,
            'filename'      => $fname,
            'mime_type'     => $mime,
            'width'         => (int) $width,
            'height'        => (int) $height,
            'html_img_tag'  => $img_tag,
            'message'       => sprintf( 'Image "%s" uploaded via chunked upload (%d chunks).', $fname, $total ),
        );
    }
}


/* ── ATTACH MEDIA ───────────────────────────────── */
function seo_mcp_attach_media( $params ) {
    $post_id  = isset( $params['post_id'] ) ? absint( $params['post_id'] ) : 0;
    $position = isset( $params['insert_position'] ) ? $params['insert_position'] : 'append';

    if ( ! $post_id ) return new WP_Error( 'missing_post_id', 'post_id is required.' );

    $post = get_post( $post_id );
    if ( ! $post ) return new WP_Error( 'post_not_found', sprintf( 'Post %d not found.', $post_id ) );
    if ( ! in_array( $post->post_type, SEO_MCP_POST_TYPES, true ) ) {
        return new WP_Error( 'invalid_post_type', sprintf( 'Post type "%s" not allowed.', $post->post_type ) );
    }

    // Build unified attachments list.
    $items = array();
    if ( ! empty( $params['attachments'] ) && is_array( $params['attachments'] ) ) {
        foreach ( $params['attachments'] as $att ) {
            if ( ! isset( $att['id'] ) ) continue;
            $items[] = array(
                'id'                => absint( $att['id'] ),
                'alt_text'          => isset( $att['alt_text'] ) ? sanitize_text_field( $att['alt_text'] ) : null,
                'caption'           => isset( $att['caption'] ) ? sanitize_text_field( $att['caption'] ) : null,
                'set_featured'      => ! empty( $att['set_featured'] ),
                'insert_in_content' => isset( $att['insert_in_content'] ) ? (bool) $att['insert_in_content'] : true,
            );
        }
    } elseif ( ! empty( $params['attachment_id'] ) ) {
        $items[] = array(
            'id'                => absint( $params['attachment_id'] ),
            'alt_text'          => isset( $params['alt_text'] ) ? sanitize_text_field( $params['alt_text'] ) : null,
            'caption'           => isset( $params['caption'] ) ? sanitize_text_field( $params['caption'] ) : null,
            'set_featured'      => ! empty( $params['set_featured'] ),
            'insert_in_content' => ! empty( $params['insert_in_content'] ),
        );
    }

    if ( empty( $items ) ) return new WP_Error( 'no_attachments', 'Provide attachment_id or attachments[].' );

    $results        = array();
    $image_blocks   = array();
    $featured_id    = 0;
    $total_attached = 0;
    $inserted_count = 0;

    foreach ( $items as $item ) {
        $att_id = $item['id'];
        $entry  = array( 'attachment_id' => $att_id, 'attachment_url' => '', 'status' => '' );

        $attachment = get_post( $att_id );
        if ( ! $attachment || 'attachment' !== $attachment->post_type ) {
            $entry['status'] = 'error: attachment not found';
            $results[] = $entry;
            continue;
        }
        if ( ! wp_attachment_is_image( $att_id ) ) {
            $entry['status'] = 'error: not an image';
            $results[] = $entry;
            continue;
        }

        $att_url = wp_get_attachment_url( $att_id );
        $entry['attachment_url'] = $att_url;
        $actions = array();

        wp_update_post( array( 'ID' => $att_id, 'post_parent' => $post_id ) );
        $total_attached++;
        $actions[] = 'attached';

        if ( null !== $item['alt_text'] ) {
            update_post_meta( $att_id, '_wp_attachment_image_alt', $item['alt_text'] );
            $actions[] = 'alt updated';
        }
        if ( null !== $item['caption'] ) {
            wp_update_post( array( 'ID' => $att_id, 'post_excerpt' => $item['caption'] ) );
            $actions[] = 'caption updated';
        }
        if ( $item['set_featured'] && 0 === $featured_id ) {
            if ( set_post_thumbnail( $post_id, $att_id ) ) {
                $featured_id = $att_id;
                $actions[] = 'featured';
            }
        }

        if ( $item['insert_in_content'] ) {
            $img_alt = $item['alt_text'] ?? get_post_meta( $att_id, '_wp_attachment_image_alt', true );
            $image_blocks[] = sprintf(
                '<!-- wp:image {"id":%d,"sizeSlug":"large","linkDestination":"none"} -->' . "\n" .
                '<figure class="wp-block-image size-large"><img src="%s" alt="%s" class="wp-image-%d"/></figure>' . "\n" .
                '<!-- /wp:image -->',
                $att_id, esc_url( $att_url ), esc_attr( $img_alt ?: '' ), $att_id
            );
            $inserted_count++;
            $actions[] = 'block built';
        }

        $entry['status'] = implode( ', ', $actions );
        $results[] = $entry;
    }

    if ( ! empty( $image_blocks ) ) {
        $all_blocks      = implode( "\n\n", $image_blocks );
        $current_content = $post->post_content;
        $new_content     = ( 'prepend' === $position ) ? $all_blocks . "\n\n" . $current_content : $current_content . "\n\n" . $all_blocks;
        wp_update_post( array( 'ID' => $post_id, 'post_content' => $new_content ) );
    }

    $parts   = array();
    $parts[] = sprintf( '%d image(s) attached to post %d', $total_attached, $post_id );
    if ( $featured_id )    $parts[] = sprintf( 'featured image set to %d', $featured_id );
    if ( $inserted_count ) $parts[] = sprintf( '%d image block(s) %sed in content', $inserted_count, $position );

    return array(
        'success'        => true,
        'post_id'        => $post_id,
        'total_attached' => $total_attached,
        'featured_id'    => $featured_id,
        'inserted_count' => $inserted_count,
        'results'        => $results,
        'message'        => implode( '. ', $parts ) . '.',
    );
}


/* ── LIST TAXONOMY TERMS ────────────────────────── */
function seo_mcp_list_taxonomy_terms( $params ) {
    if ( empty( $params['taxonomy'] ) ) {
        return new WP_Error( 'missing_taxonomy', 'taxonomy is required.' );
    }

    $taxonomy = sanitize_key( $params['taxonomy'] );
    $allowed  = array_merge( array( 'category', 'post_tag' ), SEO_MCP_CUSTOM_TAXONOMIES );

    if ( ! in_array( $taxonomy, $allowed, true ) ) {
        return new WP_Error( 'invalid_taxonomy', 'Taxonomy not whitelisted: ' . $taxonomy );
    }
    if ( ! taxonomy_exists( $taxonomy ) ) {
        return new WP_Error( 'taxonomy_not_found', 'Taxonomy does not exist: ' . $taxonomy );
    }

    $terms = get_terms( array(
        'taxonomy'   => $taxonomy,
        'hide_empty' => ! empty( $params['hide_empty'] ),
        'orderby'    => 'name',
        'order'      => 'ASC',
    ) );

    if ( is_wp_error( $terms ) ) return $terms;

    $result = array();
    foreach ( $terms as $term ) {
        $result[] = array(
            'id'       => $term->term_id,
            'name'     => $term->name,
            'slug'     => $term->slug,
            'count'    => $term->count,
            'parent'   => $term->parent,
            'taxonomy' => $term->taxonomy,
        );
    }
    return $result;
}


/* ── SET POST FAQs ──────────────────────────────── */
function seo_mcp_set_post_faqs( $params ) {
    $post_id = isset( $params['post_id'] ) ? absint( $params['post_id'] ) : 0;
    $faqs    = isset( $params['faqs'] ) && is_array( $params['faqs'] ) ? $params['faqs'] : array();

    if ( ! $post_id ) {
        return new WP_Error( 'missing_post_id', 'post_id is required.' );
    }
    $post = get_post( $post_id );
    if ( ! $post ) {
        return new WP_Error( 'post_not_found', sprintf( 'Post %d not found.', $post_id ) );
    }
    if ( ! in_array( $post->post_type, SEO_MCP_POST_TYPES, true ) ) {
        return new WP_Error( 'invalid_post_type', sprintf( 'Post type "%s" is not allowed.', $post->post_type ) );
    }
    if ( empty( $faqs ) ) {
        return new WP_Error( 'no_faqs', 'faqs array is required and cannot be empty.' );
    }

    if ( ! function_exists( 'update_field' ) ) {
        return new WP_Error( 'acf_missing', 'ACF/SCF plugin is not active. update_field() is not available.' );
    }

    $rows = array();
    $pos  = 1;
    foreach ( $faqs as $faq ) {
        if ( empty( $faq['question'] ) || empty( $faq['answer'] ) ) {
            continue;
        }
        $rows[] = array(
            'question' => sanitize_text_field( $faq['question'] ),
            'answer'   => wp_kses_post( $faq['answer'] ),
            'position' => isset( $faq['position'] ) ? absint( $faq['position'] ) : $pos,
        );
        $pos++;
    }

    if ( empty( $rows ) ) {
        return new WP_Error( 'no_valid_faqs', 'No valid FAQs provided. Each FAQ needs at least question and answer.' );
    }

    $result = update_field( SEO_MCP_FAQ_FIELD_KEY, $rows, $post_id );
    if ( false === $result ) {
        return new WP_Error( 'update_failed', 'Failed to update FAQs. The ACF/SCF field may not exist on this post type.' );
    }

    return array(
        'success'   => true,
        'post_id'   => $post_id,
        'faq_count' => count( $rows ),
        'message'   => sprintf( '%d FAQ(s) saved to post %d.', count( $rows ), $post_id ),
    );
}


/* ── GET POST FAQs ──────────────────────────────── */
function seo_mcp_get_post_faqs( $params ) {
    $post_id = isset( $params['post_id'] ) ? absint( $params['post_id'] ) : 0;

    if ( ! $post_id ) {
        return new WP_Error( 'missing_post_id', 'post_id is required.' );
    }
    $post = get_post( $post_id );
    if ( ! $post ) {
        return new WP_Error( 'post_not_found', sprintf( 'Post %d not found.', $post_id ) );
    }

    if ( ! function_exists( 'get_field' ) ) {
        return new WP_Error( 'acf_missing', 'ACF/SCF plugin is not active. get_field() is not available.' );
    }

    $rows = get_field( SEO_MCP_FAQ_FIELD_KEY, $post_id );
    $faqs = array();

    if ( ! empty( $rows ) && is_array( $rows ) ) {
        foreach ( $rows as $row ) {
            $faqs[] = array(
                'question' => isset( $row['question'] ) ? $row['question'] : '',
                'answer'   => isset( $row['answer'] )   ? $row['answer']   : '',
                'position' => isset( $row['position'] )  ? (int) $row['position'] : 0,
            );
        }
    }

    return array(
        'success'   => true,
        'post_id'   => $post_id,
        'faq_count' => count( $faqs ),
        'faqs'      => $faqs,
    );
}


/* ── SET RESOURCE CTA ───────────────────────────── */
function seo_mcp_set_resource_cta( $params ) {
    $post_id      = isset( $params['post_id'] ) ? absint( $params['post_id'] ) : 0;
    $resource_url = isset( $params['resource_url'] ) ? esc_url_raw( $params['resource_url'] ) : '';

    if ( ! $post_id ) {
        return new WP_Error( 'missing_post_id', 'post_id is required.' );
    }
    $post = get_post( $post_id );
    if ( ! $post ) {
        return new WP_Error( 'post_not_found', sprintf( 'Post %d not found.', $post_id ) );
    }

    if ( ! in_array( $post->post_type, SEO_MCP_CTA_POST_TYPES, true ) ) {
        return new WP_Error( 'invalid_post_type', sprintf(
            'Resource CTA only applies to: %s. Got "%s".', implode( ', ', SEO_MCP_CTA_POST_TYPES ), $post->post_type
        ) );
    }

    if ( empty( $resource_url ) ) {
        return new WP_Error( 'missing_resource_url', 'resource_url is required.' );
    }

    if ( ! function_exists( 'update_field' ) ) {
        return new WP_Error( 'acf_missing', 'ACF/SCF plugin is not active.' );
    }

    // Auto-fetch resource page title.
    $heading           = '';
    $resource_image_id = 0;

    if ( ! empty( $params['heading'] ) ) {
        $heading = sanitize_text_field( $params['heading'] );
    } else {
        $resource_post_id = url_to_postid( $resource_url );
        if ( $resource_post_id ) {
            $resource_post = get_post( $resource_post_id );
            if ( $resource_post ) {
                $heading = $resource_post->post_title;
                if ( empty( $params['cta_image_id'] ) && has_post_thumbnail( $resource_post_id ) ) {
                    $resource_image_id = get_post_thumbnail_id( $resource_post_id );
                }
            }
        }
        // Fallback: extract from URL slug.
        if ( empty( $heading ) ) {
            $path    = wp_parse_url( $resource_url, PHP_URL_PATH );
            $slug    = basename( rtrim( $path, '/' ) );
            $heading = ucwords( str_replace( '-', ' ', $slug ) );
        }
    }

    // Determine image.
    $cta_image_id = 0;
    if ( ! empty( $params['cta_image_id'] ) ) {
        $cta_image_id = absint( $params['cta_image_id'] );
    } elseif ( $resource_image_id ) {
        $cta_image_id = $resource_image_id;
    }

    $resource_link_text = sanitize_text_field( $params['resource_link_text'] ?? 'Download Now' );
    $fields_set = 0;

    // Field 1: Cta Post Heading (Text).
    update_field( SEO_MCP_CTA_HEADING_KEY, $heading, $post_id );
    $fields_set++;

    // Field 2: Cta Post URL (Link — array format).
    $link_value = array( 'title' => $resource_link_text, 'url' => $resource_url, 'target' => '' );
    update_field( SEO_MCP_CTA_URL_KEY, $link_value, $post_id );
    $fields_set++;

    // Field 3: Cta Post Image (Image — attachment ID).
    if ( $cta_image_id ) {
        update_field( SEO_MCP_CTA_IMAGE_KEY, $cta_image_id, $post_id );
        $fields_set++;
    }

    return array(
        'success'       => true,
        'post_id'       => $post_id,
        'heading'       => $heading,
        'resource_url'  => $resource_url,
        'cta_image_id'  => $cta_image_id,
        'fields_set'    => $fields_set,
        'message'       => sprintf( 'Resource CTA set on post %d. Heading: "%s". URL: %s.', $post_id, $heading, $resource_url ),
    );
}


/* ── SET RELATED PAGES ──────────────────────────── */
/*
 * Brand-specific field types:
 *   Botphonic (ACF): Repeater with sub-fields (related_title + related_post)
 */
function seo_mcp_set_related_pages( $params ) {
    $post_id       = isset( $params['post_id'] ) ? absint( $params['post_id'] ) : 0;
    $related_posts = isset( $params['related_posts'] ) && is_array( $params['related_posts'] )
        ? $params['related_posts'] : array();

    if ( ! $post_id ) {
        return new WP_Error( 'missing_post_id', 'post_id is required.' );
    }
    $post = get_post( $post_id );
    if ( ! $post ) {
        return new WP_Error( 'post_not_found', sprintf( 'Post %d not found.', $post_id ) );
    }
    if ( empty( $related_posts ) ) {
        return new WP_Error( 'no_related_posts', 'related_posts array is required and cannot be empty.' );
    }
    if ( ! function_exists( 'update_field' ) ) {
        return new WP_Error( 'acf_missing', 'ACF/SCF plugin is not active.' );
    }

    // Validate all post IDs and collect results.
    $valid_ids = array();
    $results   = array();

    foreach ( $related_posts as $item ) {
        $rid = isset( $item['post_id'] ) ? absint( $item['post_id'] ) : 0;
        if ( ! $rid ) continue;

        $related = get_post( $rid );
        if ( ! $related ) continue;

        $title = ! empty( $item['title'] )
            ? sanitize_text_field( $item['title'] )
            : $related->post_title;

        $valid_ids[] = $rid;
        $results[]   = array( 'id' => $rid, 'title' => $title );
    }

    if ( empty( $valid_ids ) ) {
        return new WP_Error( 'no_valid_posts', 'None of the provided post IDs are valid.' );
    }

    $field_key  = SEO_MCP_RELATED_POSTS_KEY;
    $field_type = SEO_MCP_RELATED_POSTS_TYPE;

    if ( 'relationship' === $field_type ) {
        // Relationship field — pass array of post IDs.
        update_field( $field_key, $valid_ids, $post_id );
    } else {
        // Repeater with sub-fields (related_title + related_post).
        $rows = array();
        foreach ( $results as $r ) {
            $rows[] = array(
                'related_title' => $r['title'],
                'related_post'  => array( $r['id'] ),  // Relationship sub-field expects array.
            );
        }
        update_field( $field_key, $rows, $post_id );
    }

    return array(
        'success'       => true,
        'post_id'       => $post_id,
        'related_count' => count( $valid_ids ),
        'related_posts' => $results,
        'message'       => sprintf( '%d related post(s) set on post %d.', count( $valid_ids ), $post_id ),
    );
}
