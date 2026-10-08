<?php
/**
 * Plugin Name: SEO MCP Toolkit
 * Plugin URI:  https://botphonic.ai
 * Description: MCP content management plugin for Botphonic. Provides MCP abilities for content CRUD, media uploads (REST + MCP), safety filtering, Yoast SEO, and ACF FAQ support.
 * Version:     2.0.0
 * Author:      Botphonic
 * Author URI:  https://botphonic.ai
 * License:     GPL-2.0-or-later
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ═══════════════════════════════════════════════════════════════════
 * BRAND AUTO-DETECTION
 *
 * Priority:
 *   1. SEO_MCP_BRAND constant in wp-config.php (override)
 *   2. Auto-detect from site URL
 *
 * Sets: SEO_MCP_BRAND ('botphonic')
 * ═══════════════════════════════════════════════════════════════════ */

if ( ! defined( 'SEO_MCP_BRAND' ) ) {
    define( 'SEO_MCP_BRAND', 'botphonic' );
}


/* ═══════════════════════════════════════════════════════════════════
 * PLUGIN CONSTANTS
 * ═══════════════════════════════════════════════════════════════════ */

define( 'SEO_MCP_VERSION', '2.0.0' );
define( 'SEO_MCP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SEO_MCP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );


/* ═══════════════════════════════════════════════════════════════════
 * LOAD MODULES
 * ═══════════════════════════════════════════════════════════════════ */

// 1. Brand-specific configuration (post types, taxonomies, labels)
require_once SEO_MCP_PLUGIN_DIR . 'includes/brand-config.php';

// 2. Safety filter (blocks destructive MCP abilities)
require_once SEO_MCP_PLUGIN_DIR . 'includes/safety-filter.php';

// 3. Media upload REST endpoints (binary file upload, batch, content store)
require_once SEO_MCP_PLUGIN_DIR . 'includes/media-upload.php';

// 4. MCP content abilities (create, read, update posts + media + taxonomies)
require_once SEO_MCP_PLUGIN_DIR . 'includes/content-abilities.php';


/* ═══════════════════════════════════════════════════════════════════
 * ADMIN NOTICE — show detected brand
 * ═══════════════════════════════════════════════════════════════════ */

add_action( 'admin_notices', 'seo_mcp_brand_notice' );

function seo_mcp_brand_notice() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // Only show once per session.
    if ( get_transient( 'seo_mcp_brand_notice_shown' ) ) {
        return;
    }

    $brand  = SEO_MCP_BRAND;
    $labels = array(
        'botphonic' => 'Botphonic (botphonic.ai)',
    );
    $label = $labels[ $brand ] ?? $brand;

    echo '<div class="notice notice-info is-dismissible"><p>';
    echo '<strong>SEO MCP Toolkit:</strong> Running in <strong>' . esc_html( $label ) . '</strong> mode.';
    if ( defined( 'SEO_MCP_BRAND' ) ) {
        echo ' <small>(To override, set <code>define( \'SEO_MCP_BRAND\', \'botphonic\' );</code> in wp-config.php)</small>';
    }
    echo '</p></div>';

    set_transient( 'seo_mcp_brand_notice_shown', true, DAY_IN_SECONDS );
}
