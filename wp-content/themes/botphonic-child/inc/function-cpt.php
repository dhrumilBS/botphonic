<?php
/**
 * ====================================================================
 * CUSTOM POST TYPES
 * ====================================================================
 *
 * Every register_post_type() call in the child theme lives here, one
 * function per post type, each hooked to `init`:
 *
 *   1. success-stories — customer case studies
 *   2. alternatives    — competitor comparison guides
 *
 * Both slugs come from constants defined in functions.php § 1, so the
 * permalink base and the template file names can never drift apart.
 * ====================================================================
 */

/**
 * ====================================================================
 * 1. CUSTOM POST TYPE: Customer Stories (Success Stories)
 * ====================================================================
 *
 * Registers the "success-stories" CPT for customer case studies.
 * These are your strongest E-E-A-T signal — make sure each one has:
 * - Real client name and company
 * - Specific metrics (e.g. "35% reduction in call handling time")
 * - Author attribution
 * - Featured image (client photo or logo)
 * ====================================================================
 */

function botphonic_register_customer_story_cpt()
{
	$labels = [
		'name'                  => _x('Customer Stories', 'Post type general name', 'botphonic'),
		'singular_name'         => _x('Customer Story', 'Post type singular name', 'botphonic'),
		'menu_name'             => _x('Customer Stories', 'Admin Menu text', 'botphonic'),
		'all_items'             => __('All Customer Stories', 'botphonic'),
		'add_new'               => __('Add New Story', 'botphonic'),
		'add_new_item'          => __('Add New Customer Story', 'botphonic'),
		'edit_item'             => __('Edit Customer Story', 'botphonic'),
		'new_item'              => __('New Customer Story', 'botphonic'),
		'view_item'             => __('View Customer Story', 'botphonic'),
		'search_items'          => __('Search Customer Stories', 'botphonic'),
		'parent_item_colon'     => __('Parent Customer Stories:', 'botphonic'),
		'not_found'             => __('No customer stories found.', 'botphonic'),
		'not_found_in_trash'    => __('No customer stories found in Trash.', 'botphonic'),
		'featured_image'        => _x('Customer Story Cover Image', 'Overrides the "Featured Image" phrase', 'botphonic'),
		'set_featured_image'    => _x('Set cover image', 'Overrides the "Set featured image" phrase', 'botphonic'),
		'remove_featured_image' => _x('Remove cover image', 'Overrides the "Remove featured image" phrase', 'botphonic'),
		'use_featured_image'    => _x('Use as cover image', 'Overrides the "Use as featured image" phrase', 'botphonic'),
		'archives'              => _x('Customer Stories', 'Post type archive label', 'botphonic'),
		'insert_into_item'      => _x('Insert into customer story', 'Used when inserting media', 'botphonic'),
		'uploaded_to_this_item' => _x('Uploaded to this customer story', 'Media attachment phrase', 'botphonic'),
		'filter_items_list'     => _x('Filter customer stories list', 'Filter text', 'botphonic'),
		'items_list_navigation' => _x('Customer stories list navigation', 'Pagination label', 'botphonic'),
		'items_list'            => _x('Customer stories list', 'Items list label', 'botphonic'),
	];

	$args = [
		'labels'             => $labels,
		'description'        => 'Real results from real customers — case studies showcasing transformational outcomes with Botphonic.',
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true, // Enables Gutenberg + REST API + full Yoast support
		'query_var'          => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 25, // Below Comments in admin menu
		'menu_icon'          => 'dashicons-awards', // Distinct icon in admin
		'supports'           => ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields'],
		'rewrite'            => [
			'slug'       => CUSTOME_STORY_SLUG, // 'success-stories'
			'with_front' => false,              // Don't prepend /blog/ or similar prefix
			'feeds'      => true,               // Enable RSS feed for stories
		],
	];

	register_post_type(CUSTOME_STORY_SLUG, $args);
}
add_action('init', 'botphonic_register_customer_story_cpt');

/**
 * ====================================================================
 * 2. CUSTOM POST TYPE: Alternatives
 * ====================================================================
 *
 * Registers the "alternatives" CPT for competitor comparison guides —
 * "Best <Competitor> alternatives" style articles that pit Botphonic
 * against the platforms a buyer is already evaluating.
 *
 * Each post is assembled from ACF fields (inc/acf-alternatives.php)
 * rather than the editor: a feature matrix, a numbered write-up per
 * platform with pros, cons and a sourced review, and an FAQ block. The
 * editor itself is kept enabled so a post can still close with free
 * prose, and because the comparison table's help text is written
 * against the classic/Gutenberg text view.
 *
 * has_archive is deliberately true: it gives /alternatives/ a listing
 * page and, because wpdev_breadcrumbs() links the post-type archive for
 * any non-post singular with an archive, it also gives every single
 * Alternatives post a correct Home → Alternatives → Title trail with
 * no extra wiring.
 * ====================================================================
 */

function botphonic_register_alternatives_cpt()
{
	$labels = [
		'name'                  => _x('Alternatives', 'Post type general name', 'botphonic'),
		'singular_name'         => _x('Alternative', 'Post type singular name', 'botphonic'),
		'menu_name'             => _x('Alternatives', 'Admin Menu text', 'botphonic'),
		'name_admin_bar'        => _x('Alternative', 'Add New on Toolbar', 'botphonic'),
		'all_items'             => __('All Alternatives', 'botphonic'),
		'add_new'               => __('Add New Comparison', 'botphonic'),
		'add_new_item'          => __('Add New Alternatives Guide', 'botphonic'),
		'edit_item'             => __('Edit Alternatives Guide', 'botphonic'),
		'new_item'              => __('New Alternatives Guide', 'botphonic'),
		'view_item'             => __('View Alternatives Guide', 'botphonic'),
		'view_items'            => __('View Alternatives', 'botphonic'),
		'search_items'          => __('Search Alternatives', 'botphonic'),
		'parent_item_colon'     => __('Parent Alternatives:', 'botphonic'),
		'not_found'             => __('No alternatives found.', 'botphonic'),
		'not_found_in_trash'    => __('No alternatives found in Trash.', 'botphonic'),
		'featured_image'        => _x('Comparison Cover Image', 'Overrides the "Featured Image" phrase', 'botphonic'),
		'set_featured_image'    => _x('Set cover image', 'Overrides the "Set featured image" phrase', 'botphonic'),
		'remove_featured_image' => _x('Remove cover image', 'Overrides the "Remove featured image" phrase', 'botphonic'),
		'use_featured_image'    => _x('Use as cover image', 'Overrides the "Use as featured image" phrase', 'botphonic'),
		'archives'              => _x('Alternatives', 'Post type archive label', 'botphonic'),
		'insert_into_item'      => _x('Insert into comparison', 'Used when inserting media', 'botphonic'),
		'uploaded_to_this_item' => _x('Uploaded to this comparison', 'Media attachment phrase', 'botphonic'),
		'filter_items_list'     => _x('Filter alternatives list', 'Filter text', 'botphonic'),
		'items_list_navigation' => _x('Alternatives list navigation', 'Pagination label', 'botphonic'),
		'items_list'            => _x('Alternatives list', 'Items list label', 'botphonic'),
	];

	$args = [
		'labels'             => $labels,
		'description'        => 'Head-to-head comparison guides — how Botphonic stacks up against the AI voice and call platforms buyers evaluate alongside it.',
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true, // Gutenberg + REST API + full Yoast support
		'query_var'          => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 26, // Directly under Customer Stories
		'menu_icon'          => 'dashicons-randomize',
		'supports'           => ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'custom-fields'],
		'rewrite'            => [
			'slug'       => BOTPHONIC_ALT_SLUG, // 'alternatives'
			'with_front' => false,              // Don't prepend /blog/ or similar prefix
			'feeds'      => true,
		],
	];

	register_post_type(BOTPHONIC_ALT_SLUG, $args);
}
add_action('init', 'botphonic_register_alternatives_cpt');


/**
 * ====================================================================
 * 3. ONE-TIME REWRITE FLUSH
 * ====================================================================
 *
 * A newly registered post type has no rewrite rules until they are
 * regenerated, so /alternatives/ and every single guide would 404 until
 * someone remembered to open Settings → Permalinks. This flushes once,
 * on the first request after deploy, and then never again — the version
 * string in the option is what makes it a no-op from then on.
 *
 * Priority 20 so both register_post_type() calls above (default 10)
 * have already run. Soft flush: the .htaccess catch-all that Apache
 * needs is generic and does not change when a post type is added, so
 * there is no reason to rewrite the file.
 *
 * Bump BOTPHONIC_CPT_REWRITE_VERSION whenever a CPT slug or any of its
 * `rewrite` arguments changes.
 * ====================================================================
 */

if (!defined('BOTPHONIC_CPT_REWRITE_VERSION')) {
	define('BOTPHONIC_CPT_REWRITE_VERSION', '2026-09-alternatives');
}

function botphonic_maybe_flush_cpt_rewrites()
{
	if (get_option('botphonic_cpt_rewrite_version') === BOTPHONIC_CPT_REWRITE_VERSION) {
		return;
	}

	flush_rewrite_rules(false);

	// autoload = false: this is read once per deploy, not on every request.
	update_option('botphonic_cpt_rewrite_version', BOTPHONIC_CPT_REWRITE_VERSION, false);
}
add_action('init', 'botphonic_maybe_flush_cpt_rewrites', 20);
