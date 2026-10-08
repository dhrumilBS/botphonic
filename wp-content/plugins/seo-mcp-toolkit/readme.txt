=== SEO MCP Toolkit ===
Contributors: botphonic
Tags: mcp, seo, content-management, media-upload, ai
Requires at least: 6.0
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 2.0.0
License: GPL-2.0-or-later

MCP content management plugin for Botphonic. Provides media upload, safety filter, and content abilities with brand configuration.

== Description ==

SEO MCP Toolkit provides:
- MCP Content abilities
- Media Upload API
- MCP Safety Filter

== Features ==

**Content Management (MCP Abilities)**
- Create, update, get, and list posts across all post types
- Category and tag management
- Yoast SEO field management (meta title, description, focus keyphrase, etc.)
- FAQ repeater support (ACF)

**Media Upload (REST API)**
- Single file upload: POST /wp-json/seo-mcp/v1/upload
- Batch upload: POST /wp-json/seo-mcp/v1/upload-batch
- Content store: POST /wp-json/seo-mcp/v1/store-content
- MCP-based upload (URL or base64)
- Chunked upload for large images
- Attach media to posts with Gutenberg blocks

**Safety**
- Blocks all destructive MCP abilities (delete, trash, destroy, remove, purge, erase, wipe)
- Defence-in-depth: even if another plugin registers a delete ability, it will not be exposed

== Installation ==

1. Upload the `wordpress-plugin` folder to `/wp-content/plugins/seo-mcp-toolkit/`
2. Activate the plugin through the WordPress Plugins screen
3. Add your upload token to wp-config.php:

`define( 'BOTPHONIC_UPLOAD_TOKEN', 'your-secret-token' );`

4. If using the WP MCP Adapter plugin, abilities will be automatically registered

== wp-config.php Constants ==

Required:
- BOTPHONIC_UPLOAD_TOKEN

Optional:
- SEO_MCP_BRAND — Force brand ('botphonic')
- SEO_MCP_UPLOAD_RATE_LIMIT — Max uploads per hour (default: 50)
- SEO_MCP_UPLOAD_BATCH_LIMIT — Max files per batch (default: 20)
- SEO_MCP_CONTENT_MAX_SIZE — Max content store size in bytes (default: 2MB)
- SEO_MCP_CONTENT_TTL — Content token TTL in seconds (default: 300)

== Changelog ==

= 2.0.0 =
* Unified plugin with brand configuration
* REST API namespace: seo-mcp/v1
* Brand-specific post types and taxonomies
* Brand-specific upload token constants
