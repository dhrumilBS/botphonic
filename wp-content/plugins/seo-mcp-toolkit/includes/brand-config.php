<?php
/**
 * Brand-specific configuration for SEO MCP Toolkit.
 *
 * Defines post types, taxonomies, labels, and upload token constants
 * based on the detected brand (SEO_MCP_BRAND).
 *
 * Botphonic uses ACF (Advanced Custom Fields) for FAQ repeater.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ═══════════════════════════════════════════════════════════════════
 * BRAND-SPECIFIC POST TYPES
 * ═══════════════════════════════════════════════════════════════════ */

$seo_mcp_brand_config = array(

    'botphonic' => array(
        'post_types' => array(
            'post',
            'page',
            'success-stories',
        ),
        'post_type_labels' => array(
            'post'            => 'Blog Post',
            'page'            => 'Page',
            'success-stories' => 'Success Story (Case Study)',
        ),
        'custom_taxonomies' => array(),
        'upload_token_constant' => 'BOTPHONIC_UPLOAD_TOKEN',
        'faq_plugin'            => 'acf',  // Advanced Custom Fields
        'faq_field_key'         => 'field_680b23c4af48a',  // Botphonic FAQs repeater
        'related_posts_type'    => '',      // No related posts field
        'related_posts_key'     => '',
        'cta_post_types'        => array(),  // No CTA custom fields
        'cta_heading_key'       => '',
        'cta_url_key'           => '',
        'cta_image_key'         => '',
        'ability_prefix'        => 'botphonic',
        'category_slug'         => 'botphonic-content',
        'brand_label'           => 'Botphonic',
    ),
);


/* ═══════════════════════════════════════════════════════════════════
 * RESOLVE CURRENT BRAND CONFIG
 * ═══════════════════════════════════════════════════════════════════ */

$current_brand = SEO_MCP_BRAND;

if ( ! isset( $seo_mcp_brand_config[ $current_brand ] ) ) {
    // Fallback to botphonic if unknown brand.
    $current_brand = 'botphonic';
}

$brand_cfg = $seo_mcp_brand_config[ $current_brand ];


/* ═══════════════════════════════════════════════════════════════════
 * DEFINE CONSTANTS (used by content-abilities.php and media-upload.php)
 * ═══════════════════════════════════════════════════════════════════ */

if ( ! defined( 'SEO_MCP_POST_TYPES' ) ) {
    define( 'SEO_MCP_POST_TYPES', $brand_cfg['post_types'] );
}

if ( ! defined( 'SEO_MCP_POST_TYPE_LABELS' ) ) {
    define( 'SEO_MCP_POST_TYPE_LABELS', $brand_cfg['post_type_labels'] );
}

if ( ! defined( 'SEO_MCP_CUSTOM_TAXONOMIES' ) ) {
    define( 'SEO_MCP_CUSTOM_TAXONOMIES', $brand_cfg['custom_taxonomies'] );
}

if ( ! defined( 'SEO_MCP_UPLOAD_TOKEN_CONSTANT' ) ) {
    define( 'SEO_MCP_UPLOAD_TOKEN_CONSTANT', $brand_cfg['upload_token_constant'] );
}

if ( ! defined( 'SEO_MCP_FAQ_PLUGIN' ) ) {
    define( 'SEO_MCP_FAQ_PLUGIN', $brand_cfg['faq_plugin'] );
}

if ( ! defined( 'SEO_MCP_FAQ_FIELD_KEY' ) ) {
    define( 'SEO_MCP_FAQ_FIELD_KEY', $brand_cfg['faq_field_key'] );
}

if ( ! defined( 'SEO_MCP_ABILITY_PREFIX' ) ) {
    define( 'SEO_MCP_ABILITY_PREFIX', $brand_cfg['ability_prefix'] );
}

if ( ! defined( 'SEO_MCP_CATEGORY_SLUG' ) ) {
    define( 'SEO_MCP_CATEGORY_SLUG', $brand_cfg['category_slug'] );
}

if ( ! defined( 'SEO_MCP_BRAND_LABEL' ) ) {
    define( 'SEO_MCP_BRAND_LABEL', $brand_cfg['brand_label'] );
}

if ( ! defined( 'SEO_MCP_CTA_POST_TYPES' ) ) {
    define( 'SEO_MCP_CTA_POST_TYPES', $brand_cfg['cta_post_types'] );
}

if ( ! defined( 'SEO_MCP_CTA_HEADING_KEY' ) ) {
    define( 'SEO_MCP_CTA_HEADING_KEY', $brand_cfg['cta_heading_key'] );
}

if ( ! defined( 'SEO_MCP_CTA_URL_KEY' ) ) {
    define( 'SEO_MCP_CTA_URL_KEY', $brand_cfg['cta_url_key'] );
}

if ( ! defined( 'SEO_MCP_CTA_IMAGE_KEY' ) ) {
    define( 'SEO_MCP_CTA_IMAGE_KEY', $brand_cfg['cta_image_key'] );
}

if ( ! defined( 'SEO_MCP_RELATED_POSTS_TYPE' ) ) {
    define( 'SEO_MCP_RELATED_POSTS_TYPE', $brand_cfg['related_posts_type'] );
}

if ( ! defined( 'SEO_MCP_RELATED_POSTS_KEY' ) ) {
    define( 'SEO_MCP_RELATED_POSTS_KEY', $brand_cfg['related_posts_key'] );
}


/* ═══════════════════════════════════════════════════════════════════
 * HELPER: Get upload token value
 *
 * Reads the brand-specific token constant from wp-config.php.
 * e.g., BOTPHONIC_UPLOAD_TOKEN
 * ═══════════════════════════════════════════════════════════════════ */

function seo_mcp_get_upload_token() {
    $constant_name = SEO_MCP_UPLOAD_TOKEN_CONSTANT;
    if ( defined( $constant_name ) ) {
        return constant( $constant_name );
    }
    return '';
}


/* ═══════════════════════════════════════════════════════════════════
 * HELPER: Save FAQ fields (ACF or SCF)
 *
 * ACF: uses update_field() with field key
 * SCF: uses update_field() with field key (SCF is ACF-compatible fork)
 *
 * Both ACF and SCF expose the same update_field() API, so the code
 * is identical — we just need the function to exist.
 * ═══════════════════════════════════════════════════════════════════ */

function seo_mcp_save_faqs( $post_id, $faqs ) {
    if ( empty( $faqs ) || ! is_array( $faqs ) ) {
        return 0;
    }

    // Both ACF and SCF expose update_field().
    if ( ! function_exists( 'update_field' ) ) {
        return 0;
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

    if ( ! empty( $rows ) ) {
        update_field( SEO_MCP_FAQ_FIELD_KEY, $rows, $post_id );
        return count( $rows );
    }

    return 0;
}
