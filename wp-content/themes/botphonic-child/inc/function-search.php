<?php

/**
 * Botphonic child theme — site search.
 *
 * search.php renders the results on the blog design system (blog.css tokens
 * and primitives) with a thin layer of its own in assets/css/search.css. This
 * file supplies everything behind it:
 *
 *   1. The content types search covers, and the `?type=` tab that narrows it.
 *   2. Main-query scoping: post types, noindex / utility page exclusions,
 *      page size and the relevance / newest sort.
 *   3. Per-type result counts for the tabs.
 *   4. Snippet + highlight helpers for the result card.
 *   5. A small REST endpoint feeding the live suggestions in search.js.
 *   6. Body / html classes and asset loading.
 *
 * URLs stay plain WordPress search URLs: /?s=term, plus optional
 * &type=articles|comparisons|stories|pages and &orderby=date.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

if (!defined('BOTPHONIC_SEARCH_STYLE')) {
	define('BOTPHONIC_SEARCH_STYLE', 'botphonic-search');
}

if (!defined('BOTPHONIC_SEARCH_PER_PAGE')) {
	define('BOTPHONIC_SEARCH_PER_PAGE', 12);
}


/* ==========================================================================
   1. Content types
   ========================================================================== */

/**
 * The searchable content types, in tab order.
 *
 * Keyed by the public `?type=` value. Filterable so a new post type can join
 * search without touching the template.
 *
 * @return array<string,array{post_type:string,label:string,singular:string,cta:string,icon:string}>
 */
function botphonic_search_types()
{
	static $types = null;

	if (null !== $types) {
		return $types;
	}

	$types = array(
		'articles' => array(
			'post_type' => 'post',
			'label' => 'Articles',
			'singular' => 'Article',
			'cta' => 'Read article',
			'icon' => 'article',
		),
		'comparisons' => array(
			'post_type' => function_exists('botphonic_alt_post_type') ? botphonic_alt_post_type() : 'alternatives',
			'label' => 'Comparisons',
			'singular' => 'Comparison',
			'cta' => 'View comparison',
			'icon' => 'scale',
		),
		'stories' => array(
			'post_type' => function_exists('botphonic_story_post_type') ? botphonic_story_post_type() : 'success-stories',
			'label' => 'Customer stories',
			'singular' => 'Customer story',
			'cta' => 'Read story',
			'icon' => 'trophy',
		),
		'pages' => array(
			'post_type' => 'page',
			'label' => 'Pages',
			'singular' => 'Page',
			'cta' => 'Visit page',
			'icon' => 'page',
		),
	);

	$types = (array) apply_filters('botphonic_search_types', $types);

	// Drop anything whose post type is not registered (e.g. a CPT disabled).
	foreach ($types as $key => $type) {
		if (empty($type['post_type']) || !post_type_exists($type['post_type'])) {
			unset($types[$key]);
		}
	}

	return $types;
}

/**
 * @return string[] Post type slugs covered by "All results".
 */
function botphonic_search_post_types()
{
	return array_values(wp_list_pluck(botphonic_search_types(), 'post_type'));
}

/**
 * The active `?type=` tab, or '' for all results.
 *
 * @return string
 */
function botphonic_search_current_type()
{
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only GET filter.
	$type = isset($_GET['type']) ? sanitize_key(wp_unslash($_GET['type'])) : '';

	return array_key_exists($type, botphonic_search_types()) ? $type : '';
}

/**
 * The active sort: 'relevance' (default) or 'date'.
 *
 * @return string
 */
function botphonic_search_current_sort()
{
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only GET filter.
	$sort = isset($_GET['orderby']) ? sanitize_key(wp_unslash($_GET['orderby'])) : '';

	return 'date' === $sort ? 'date' : 'relevance';
}

/**
 * Type key for a post, falling back to 'pages' for anything unexpected.
 *
 * @param int|WP_Post|null $post Optional. Defaults to the current post.
 * @return string
 */
function botphonic_search_type_of($post = null)
{
	$post_type = get_post_type($post);

	foreach (botphonic_search_types() as $key => $type) {
		if ($type['post_type'] === $post_type) {
			return $key;
		}
	}

	return 'pages';
}

/**
 * Search URL for a term, keeping (or overriding) the type and sort.
 *
 * @param string      $term Search term.
 * @param string|null $type Type key, '' for all, null to keep the current one.
 * @param string|null $sort 'relevance' | 'date', null to keep the current one.
 * @return string
 */
function botphonic_search_url($term, $type = null, $sort = null)
{
	$type = null === $type ? botphonic_search_current_type() : $type;
	$sort = null === $sort ? botphonic_search_current_sort() : $sort;

	$args = array('s' => $term);

	if ($type) {
		$args['type'] = $type;
	}

	if ('date' === $sort) {
		$args['orderby'] = 'date';
	}

	return add_query_arg(array_map('rawurlencode', $args), home_url('/'));
}


/* ==========================================================================
   2. Query scoping
   ========================================================================== */

/**
 * Posts that must never appear in search: anything Yoast marks noindex, and
 * pages built on utility templates (thank-you screens, the internal CTA
 * gallery). Cached; the cache is dropped whenever a post is saved.
 *
 * @return int[]
 */
function botphonic_search_excluded_ids()
{
	static $ids = null;

	if (null !== $ids) {
		return $ids;
	}

	$ids = get_transient('botphonic_search_excluded_ids');

	if (false !== $ids && is_array($ids)) {
		return $ids;
	}

	$templates = (array) apply_filters(
		'botphonic_search_excluded_templates',
		array('thank-you.php', 'temp-cta.php')
	);

	$ids = get_posts(
		array(
			'post_type' => botphonic_search_post_types(),
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'fields' => 'ids',
			'no_found_rows' => true,
			'suppress_filters' => true,
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key' => '_yoast_wpseo_meta-robots-noindex',
					'value' => '1',
				),
				array(
					'key' => '_wp_page_template',
					'value' => $templates,
					'compare' => 'IN',
				),
			),
		)
	);

	$ids = array_map('intval', (array) apply_filters('botphonic_search_excluded_ids', $ids));

	set_transient('botphonic_search_excluded_ids', $ids, 12 * HOUR_IN_SECONDS);

	return $ids;
}

/**
 * Drop the exclusion cache whenever content changes.
 */
function botphonic_search_flush_excluded_ids()
{
	delete_transient('botphonic_search_excluded_ids');
}
add_action('save_post', 'botphonic_search_flush_excluded_ids');
add_action('deleted_post', 'botphonic_search_flush_excluded_ids');
add_action('updated_post_meta', 'botphonic_search_flush_excluded_ids');

/**
 * Shared query arguments for every search query on the site: the main query,
 * the tab counts and the REST suggestions all go through this, so they can
 * never disagree about what is searchable.
 *
 * @param string $term Search term.
 * @param string $type Type key, or '' for all.
 * @return array
 */
function botphonic_search_query_args($term, $type = '')
{
	$types = botphonic_search_types();

	return array(
		's' => $term,
		'post_type' => ($type && isset($types[$type])) ? $types[$type]['post_type'] : botphonic_search_post_types(),
		'post_status' => 'publish',
		'post__not_in' => botphonic_search_excluded_ids(),
		'ignore_sticky_posts' => true,
	);
}

/**
 * Scope the main search query.
 *
 * @param WP_Query $query Query being prepared.
 */
function botphonic_search_pre_get_posts($query)
{
	if (is_admin() || !$query->is_main_query() || !$query->is_search()) {
		return;
	}

	$args = botphonic_search_query_args($query->get('s'), botphonic_search_current_type());

	foreach ($args as $key => $value) {
		$query->set($key, $value);
	}

	$query->set('posts_per_page', (int) BOTPHONIC_SEARCH_PER_PAGE);

	// Only two sorts are offered; anything else in ?orderby= (rand, meta…)
	// falls back to WordPress' own relevance ordering for searches.
	if ('date' === botphonic_search_current_sort()) {
		$query->set('orderby', 'date');
		$query->set('order', 'DESC');
	} else {
		$query->set('orderby', 'relevance');
		$query->set('order', 'DESC');
	}
}
add_action('pre_get_posts', 'botphonic_search_pre_get_posts');


/* ==========================================================================
   3. Tab counts
   ========================================================================== */

/**
 * Result count per type for the current search term.
 *
 * @param string $term Search term.
 * @return array<string,int> Keyed by type, plus 'all'.
 */
function botphonic_search_type_counts($term)
{
	static $cache = array();

	if (isset($cache[$term])) {
		return $cache[$term];
	}

	$counts = array('all' => 0);

	foreach (array_keys(botphonic_search_types()) as $key) {
		$query = new WP_Query(
			array_merge(
				botphonic_search_query_args($term, $key),
				array(
					'posts_per_page' => 1,
					'fields' => 'ids',
					'orderby' => 'none',
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
				)
			)
		);

		$counts[$key] = (int) $query->found_posts;
		$counts['all'] += $counts[$key];
	}

	$cache[$term] = $counts;

	return $counts;
}


/* ==========================================================================
   4. Snippets + highlighting
   ========================================================================== */

/**
 * The individual words of the current search, longest first so a longer
 * match wins over a shorter one it contains.
 *
 * @param string|null $term Optional. Defaults to the current search query.
 * @return string[]
 */
function botphonic_search_terms($term = null)
{
	$term = null === $term ? get_search_query(false) : $term;
	$words = preg_split('/\s+/u', trim((string) $term), -1, PREG_SPLIT_NO_EMPTY);
	$words = array_map(
		static function ($word) {
			return trim($word, "\"'");
		},
		(array) $words
	);
	$words = array_filter(
		array_unique($words),
		static function ($word) {
			return mb_strlen($word) > 1;
		}
	);

	usort(
		$words,
		static function ($a, $b) {
			return strlen($b) - strlen($a);
		}
	);

	return array_values($words);
}

/**
 * Escape text and wrap the search terms in <mark>.
 *
 * @param string   $text  Plain text.
 * @param string[] $terms Optional. Defaults to the current search terms.
 * @return string Safe HTML.
 */
function botphonic_search_highlight($text, $terms = null)
{
	$terms = null === $terms ? botphonic_search_terms() : $terms;
	$text = esc_html($text);

	if (empty($terms)) {
		return $text;
	}

	$pattern = implode('|', array_map(
		static function ($word) {
			return preg_quote(esc_html($word), '/');
		},
		$terms
	));

	$marked = preg_replace('/(' . $pattern . ')/iu', '<mark class="bps-mark">$1</mark>', $text);

	return null === $marked ? $text : $marked;
}

/**
 * A short, highlighted excerpt for a result, centred on the first match.
 *
 * Source order: the excerpt and body (where the match usually is), then the
 * Yoast meta description (comparison guides keep their copy in ACF fields,
 * so their body can be empty).
 *
 * @param int|WP_Post|null $post   Optional. Defaults to the current post.
 * @param int              $length Optional. Approximate characters.
 * @return string Safe HTML, or ''.
 */
function botphonic_search_snippet($post = null, $length = 170)
{
	$post = get_post($post);

	if (!$post instanceof WP_Post) {
		return '';
	}

	$clean = static function ($html) {
		$text = wp_strip_all_tags(strip_shortcodes((string) $html), true);
		return trim(preg_replace('/\s+/u', ' ', html_entity_decode($text, ENT_QUOTES, 'UTF-8')));
	};

	$sources = array_filter(array(
		$clean($post->post_excerpt),
		$clean($post->post_content),
		$clean(get_post_meta($post->ID, '_yoast_wpseo_metadesc', true)),
	));

	if (empty($sources)) {
		return '';
	}

	$terms = botphonic_search_terms();
	$text = reset($sources);
	$start = 0;

	foreach ($sources as $source) {
		foreach ($terms as $word) {
			$pos = mb_stripos($source, $word);
			if (false !== $pos) {
				$text = $source;
				$start = max(0, $pos - 50);
				break 2;
			}
		}
	}

	$snippet = mb_substr($text, $start, $length);

	// Snap to word boundaries so the snippet never starts or ends mid-word.
	if ($start > 0) {
		$snippet = preg_replace('/^\S*\s/u', '', $snippet);
	}
	if (mb_strlen($text) > $start + $length) {
		$snippet = preg_replace('/\s\S*$/u', '', $snippet);
	}

	$snippet = botphonic_search_highlight(trim($snippet), $terms);

	return ($start > 0 ? '&hellip; ' : '') . $snippet . (mb_strlen($text) > $start + $length ? ' &hellip;' : '');
}


/* ==========================================================================
   5. Icons
   ========================================================================== */

/**
 * Inline SVG icons for the search UI.
 *
 * @param string $name Icon key.
 * @return string
 */
function botphonic_search_icon($name)
{
	$paths = array(
		'search' => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/>',
		'close' => '<path d="M6 6l12 12M18 6L6 18"/>',
		'arrow-right' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'article' => '<rect x="4" y="3" width="16" height="18" rx="2.5"/><path d="M8 8h8M8 12h8M8 16h5"/>',
		'scale' => '<path d="M12 3v18M7 7h10"/><path d="M4 12l3-5 3 5a3 3 0 0 1-6 0zM14 12l3-5 3 5a3 3 0 0 1-6 0z"/>',
		'trophy' => '<path d="M8 4h8v5a4 4 0 0 1-8 0V4z"/><path d="M16 5h3v2a3 3 0 0 1-3 3M8 5H5v2a3 3 0 0 0 3 3M12 13v4M8.5 21h7M9.5 17h5"/>',
		'page' => '<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/>',
		'grid' => '<rect x="4" y="4" width="7" height="7" rx="1.5"/><rect x="13" y="4" width="7" height="7" rx="1.5"/><rect x="4" y="13" width="7" height="7" rx="1.5"/><rect x="13" y="13" width="7" height="7" rx="1.5"/>',
		'sort' => '<path d="M7 4v16M7 20l-3-3M7 20l3-3M17 20V4M17 4l-3 3M17 4l3 3"/>',
	);

	if (!isset($paths[$name])) {
		return '';
	}

	return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $paths[$name] . '</svg>';
}


/* ==========================================================================
   6. Live suggestions (REST)
   ========================================================================== */

/**
 * GET /wp-json/botphonic/v1/search?q=term[&type=articles]
 *
 * Public and read-only: it only ever returns titles and links of published,
 * indexable content, exactly what the search page itself would show.
 */
function botphonic_search_register_rest_route()
{
	register_rest_route(
		'botphonic/v1',
		'/search',
		array(
			'methods' => WP_REST_Server::READABLE,
			'permission_callback' => '__return_true',
			'callback' => 'botphonic_search_rest_callback',
			'args' => array(
				'q' => array(
					'type' => 'string',
					'required' => true,
					'sanitize_callback' => 'sanitize_text_field',
				),
				'type' => array(
					'type' => 'string',
					'default' => '',
					'sanitize_callback' => 'sanitize_key',
				),
			),
		)
	);
}
add_action('rest_api_init', 'botphonic_search_register_rest_route');

/**
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response
 */
function botphonic_search_rest_callback($request)
{
	$term = trim((string) $request->get_param('q'));
	$type = (string) $request->get_param('type');
	$types = botphonic_search_types();

	if (!isset($types[$type])) {
		$type = '';
	}

	if ((function_exists('mb_strlen') ? mb_strlen($term) : strlen($term)) < 2) {
		return rest_ensure_response(array('items' => array(), 'total' => 0));
	}

	$query = new WP_Query(
		array_merge(
			botphonic_search_query_args($term, $type),
			array(
				'posts_per_page' => 6,
				'orderby' => 'relevance',
				'update_post_term_cache' => false,
			)
		)
	);

	$items = array();

	foreach ($query->posts as $post) {
		$key = botphonic_search_type_of($post);
		$thumb = get_the_post_thumbnail_url($post, 'thumbnail');

		$items[] = array(
			'title' => html_entity_decode(get_the_title($post), ENT_QUOTES, 'UTF-8'),
			'url' => get_permalink($post),
			'type' => $key,
			'typeLabel' => $types[$key]['singular'],
			'thumb' => $thumb ? $thumb : '',
		);
	}

	$response = rest_ensure_response(
		array(
			'items' => $items,
			'total' => (int) $query->found_posts,
			'url' => botphonic_search_url($term, $type, 'relevance'),
		)
	);

	$response->header('Cache-Control', 'public, max-age=300');

	return $response;
}


/* ==========================================================================
   7. Body / html classes + assets
   ========================================================================== */

/**
 * The search results page, and any page that prints the enhanced search box.
 *
 * @return bool
 */
function botphonic_is_search_view()
{
	return is_search();
}

/**
 * `bpg-blog` gives the search page the same navbar chrome as the blog
 * (blog.js / blog.css § 3.1); `bps-search` scopes search.css.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function botphonic_search_body_class($classes)
{
	if (botphonic_is_search_view()) {
		$classes[] = 'bpg-blog';
		$classes[] = 'bps-search';
	}

	return $classes;
}
add_filter('body_class', 'botphonic_search_body_class');

/**
 * @param string $output Attribute string.
 * @return string
 */
function botphonic_search_html_class($output)
{
	if (is_admin() || !botphonic_is_search_view()) {
		return $output;
	}

	return trim($output . ' class="bpg-blog-html"');
}
add_filter('language_attributes', 'botphonic_search_html_class');

/**
 * Assets. The search page loads the blog stack plus the search layer; the
 * blog listings load only the search layer on top of what they already have,
 * so their hero search box gets live suggestions too.
 */
function botphonic_search_enqueue_assets()
{
	$is_search = botphonic_is_search_view();
	$is_blog_listing = function_exists('botphonic_is_blog_archive_view') && botphonic_is_blog_archive_view();

	if (!$is_search && !$is_blog_listing) {
		return;
	}

	$dir = get_stylesheet_directory_uri();

	if ($is_search) {
		wp_enqueue_style(BOTPHONIC_BLOG_STYLE, $dir . '/assets/css/blog.css', array(BOTPHONIC_LANDING_STYLE), botphonic_child_asset_ver('/assets/css/blog.css'));
		wp_enqueue_script(BOTPHONIC_BLOG_STYLE, $dir . '/assets/js/blog.js', array(), botphonic_child_asset_ver('/assets/js/blog.js'), true);
	}

	wp_enqueue_style(BOTPHONIC_SEARCH_STYLE, $dir . '/assets/css/search.css', array(BOTPHONIC_BLOG_STYLE), botphonic_child_asset_ver('/assets/css/search.css'));
	wp_enqueue_script(BOTPHONIC_SEARCH_STYLE, $dir . '/assets/js/search.js', array(), botphonic_child_asset_ver('/assets/js/search.js'), true);

	wp_add_inline_script(
		BOTPHONIC_SEARCH_STYLE,
		'window.bpsSearch = ' . wp_json_encode(
			array(
				'endpoint' => esc_url_raw(rest_url('botphonic/v1/search')),
				'searchUrl' => esc_url_raw(home_url('/')),
			)
		) . ';',
		'before'
	);
}
add_action('wp_enqueue_scripts', 'botphonic_search_enqueue_assets', 20);
