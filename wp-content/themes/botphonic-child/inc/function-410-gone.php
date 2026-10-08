<?php
/**
 * ====================================================================
 * BOTPHONIC CHILD THEME — HTTP 410 GONE HANDLING
 * ====================================================================
 *
 * Separates "410 Gone" responses from ordinary "404 Not Found" responses.
 *   404 Not Found  ->  404.php   (unchanged, core behaviour)
 *   410 Gone       ->  410.php   (this file)
 * Extend programmatically with the `botphonic_is_410_request` filter.
 *
 * @package Botphonic_Child
 */

defined('ABSPATH') || exit;

/**
 * Post meta key used to flag a post / page as HTTP 410 Gone.
 */
const BOTPHONIC_410_META_KEY = '_botphonic_http_410';


/**
 * --------------------------------------------------------------------
 * 1. DETECTION
 * --------------------------------------------------------------------
 */

/**
 * Load and cache the curated "gone URLs" list.
 *
 * @return array{prefixes:array,regex:array,query_strings:array,exact_paths:array}
 */
function botphonic_410_get_url_rules()
{
	static $rules = null;

	if (null !== $rules) {
		return $rules;
	}

	$defaults = [
		'exact_paths' => [],
		'prefixes' => [],
		'regex' => [],
		'query_strings' => [],
	];

	$list_file = get_stylesheet_directory() . '/gone-urls-list.php';
	$list = is_readable($list_file) ? include $list_file : [];

	if (!is_array($list)) {
		$list = [];
	}

	$rules = array_merge($defaults, $list);

	foreach ($defaults as $key => $_unused) {
		$rules[$key] = is_array($rules[$key]) ? $rules[$key] : [];
	}

	return $rules;
}

/**
 * Current request path, normalised to be relative to the site root.
 *
 * The rules in gone-urls-list.php are written relative to the site root
 * ("/wp-content/", "/wp-login.php", ...). When WordPress is installed in a
 * subdirectory the raw REQUEST_URI carries that subdirectory as a prefix,
 * so it is stripped here. This keeps the same rule list working on a
 * subdirectory install (staging/local) and at a domain root (production).
 *
 * @return string Decoded, site-root-relative path with a leading slash.
 */
function botphonic_410_get_relative_path()
{
	$request_uri = isset($_SERVER['REQUEST_URI']) ? (string) wp_unslash($_SERVER['REQUEST_URI']) : '';

	if ('' === $request_uri) {
		return '';
	}

	$path = rawurldecode((string) wp_parse_url($request_uri, PHP_URL_PATH));

	if ('' === $path) {
		return '/';
	}
	if ('/' !== substr($path, 0, 1)) {
		$path = '/' . $path;
	}

	$base = (string) wp_parse_url(home_url('/'), PHP_URL_PATH);
	$base = '/' . trim($base, '/');

	if ('/' !== $base) {
		if (0 === strcasecmp($path, $base)) {
			return '/';
		}
		if (0 === strncasecmp($path, $base . '/', strlen($base) + 1)) {
			$path = substr($path, strlen($base));
		}
	}

	return '' === $path ? '/' : $path;
}

/**
 * Does the current request path / query string match the gone URL list?
 *
 * @return bool
 */
function botphonic_410_request_matches_url_list()
{
	$request_uri = isset($_SERVER['REQUEST_URI']) ? (string) wp_unslash($_SERVER['REQUEST_URI']) : '';

	if ('' === $request_uri) {
		return false;
	}

	$path = botphonic_410_get_relative_path();
	$query = (string) wp_parse_url($request_uri, PHP_URL_QUERY);

	if ('' === $path) {
		return false;
	}

	$rules = botphonic_410_get_url_rules();

	// -- Exact paths (with and without trailing slash) -----------------
	foreach ($rules['exact_paths'] as $exact) {
		$exact = '/' . ltrim((string) $exact, '/');
		if ($path === $exact || untrailingslashit($path) === untrailingslashit($exact)) {
			return true;
		}
	}

	// -- Prefix matches ------------------------------------------------
	foreach ($rules['prefixes'] as $prefix) {
		$prefix = (string) $prefix;
		if ('' !== $prefix && 0 === strpos($path, $prefix)) {
			return true;
		}
	}

	// -- Regex matches -------------------------------------------------
	// The curated list mixes two conventions: some patterns are anchored
	// against a leading slash ("#^/wp-...#"), others against the path
	// without it ("#^\)...#"). Test both so either style works.
	$path_no_slash = ltrim($path, '/');

	foreach ($rules['regex'] as $pattern) {
		$pattern = (string) $pattern;
		if ('' === $pattern) {
			continue;
		}
		if (@preg_match($pattern, $path) === 1 || @preg_match($pattern, $path_no_slash) === 1) {
			return true;
		}
	}

	// -- Query-string matches ------------------------------------------
	if ('' !== $query) {
		foreach ($rules['query_strings'] as $pattern) {
			$pattern = (string) $pattern;
			if ('' !== $pattern && @preg_match($pattern, $query) === 1) {
				return true;
			}
		}
	}

	return false;
}

/**
 * Is a given post explicitly flagged as HTTP 410 Gone?
 *
 * @param int|WP_Post|null $post Post to check. Defaults to the current post.
 * @return bool
 */
function botphonic_410_is_post_gone($post = null)
{
	$post = get_post($post);

	if (!$post instanceof WP_Post) {
		return false;
	}

	return '1' === (string) get_post_meta($post->ID, BOTPHONIC_410_META_KEY, true);
}

/**
 * Should the current request return HTTP 410 Gone?
 *
 * Safe to call from `template_redirect` onwards. The result is cached for
 * the remainder of the request.
 *
 * @return bool
 */
function botphonic_is_410_request()
{
	static $is_gone = null;

	if (null !== $is_gone) {
		return $is_gone;
	}

	if (!did_action('wp')) {
		return false;
	}

	$is_gone = false;

	$is_front_end_page = !is_admin()
		&& !wp_doing_ajax()
		&& !wp_doing_cron()
		&& !(defined('REST_REQUEST') && REST_REQUEST)
		&& !(defined('WP_CLI') && WP_CLI)
		&& !is_feed()
		&& !is_trackback()
		&& !is_robots()
		&& !(function_exists('is_favicon') && is_favicon());

	if ($is_front_end_page) {
		if (is_singular() && botphonic_410_is_post_gone(get_queried_object_id())) {
			$is_gone = true;
		} elseif (is_404() && botphonic_410_request_matches_url_list()) {
			$is_gone = true;
		}
	}

	/**
	 * Filter whether the current request should return HTTP 410 Gone.
	 *
	 * @param bool $is_gone Current decision.
	 */
	$is_gone = (bool) apply_filters('botphonic_is_410_request', $is_gone);

	return $is_gone;
}


/**
 * --------------------------------------------------------------------
 * 2. RESPONSE — status header + template
 * --------------------------------------------------------------------
 */

/**
 * Send the 410 status header.
 *
 * Priority 999 so it runs after `redirect_canonical`, Yoast redirects and
 * any other redirect logic: a URL that is supposed to redirect still
 * redirects, and only requests that actually render get a 410.
 */
function botphonic_410_send_status_header()
{
	if (!botphonic_is_410_request()) {
		return;
	}

	if (headers_sent()) {
		return;
	}

	nocache_headers();
	status_header(410, 'Gone');
	header('X-Robots-Tag: noindex, follow', true);
}
add_action('template_redirect', 'botphonic_410_send_status_header', 999);

/**
 * Load 410.php instead of the template WordPress would normally pick
 * (404.php, single.php, page.php, an Elementor layout, ...).
 *
 * Priority 999 so it wins over other `template_include` consumers.
 *
 * @param string $template Template path resolved so far.
 * @return string
 */
function botphonic_410_template_include($template)
{
	if (!botphonic_is_410_request()) {
		return $template;
	}

	$gone_template = locate_template(['410.php']);

	return $gone_template ? $gone_template : $template;
}
add_filter('template_include', 'botphonic_410_template_include', 999);


/**
 * --------------------------------------------------------------------
 * 3. SEO ALIGNMENT (410 requests only)
 * --------------------------------------------------------------------
 */

/**
 * Force noindex on 410 responses (core robots API).
 *
 * @param array $robots Robots directives.
 * @return array
 */
function botphonic_410_wp_robots($robots)
{
	if (botphonic_is_410_request()) {
		$robots['noindex'] = true;
		$robots['follow'] = true;
		unset($robots['index'], $robots['nofollow']);
	}

	return $robots;
}
add_filter('wp_robots', 'botphonic_410_wp_robots', 999);

/**
 * Force noindex on 410 responses (Yoast robots string).
 *
 * @param string $robots Yoast robots value.
 * @return string
 */
function botphonic_410_wpseo_robots($robots)
{
	return botphonic_is_410_request() ? 'noindex, follow' : $robots;
}
add_filter('wpseo_robots', 'botphonic_410_wpseo_robots', 999);

/**
 * Document title for 410 responses.
 *
 * Without this the 410 template inherits whatever title the request would
 * otherwise get. A URL matched from gone-urls-list.php is also an is_404()
 * request, so Yoast applies its 404 title template and the 410 page ends up
 * reading "404 – Page Not Found". A post flagged 410 keeps its own title,
 * which is worse: the page advertises content that no longer exists.
 *
 * @return string
 */
function botphonic_410_get_title()
{
	/**
	 * Filter the title shown on 410 responses (before the site name).
	 *
	 * @param string $title Title text.
	 */
	return (string) apply_filters('botphonic_410_title', __('410 – Page Permanently Removed', 'botphonic'));
}

/**
 * Meta description for 410 responses.
 *
 * @return string
 */
function botphonic_410_get_description()
{
	/**
	 * Filter the meta description shown on 410 responses.
	 *
	 * @param string $description Description text.
	 */
	return (string) apply_filters(
		'botphonic_410_description',
		__('This page has been permanently removed and will not be coming back. Visit the Botphonic homepage to explore our AI voice agents.', 'botphonic')
	);
}

/**
 * Site-name suffix, matching the separator the rest of the site uses.
 *
 * Yoast's configured separator wins when Yoast is active, so the 410 title
 * looks like every other title on the site.
 *
 * @return string Suffix with a leading space, or '' when there is no site name.
 */
function botphonic_410_title_suffix()
{
	$site_name = get_bloginfo('name', 'display');

	if ('' === $site_name) {
		return '';
	}

	if (function_exists('wpseo_replace_vars')) {
		$suffix = trim((string) wpseo_replace_vars('%%sep%% %%sitename%%', []));

		if ('' !== $suffix) {
			return ' ' . $suffix;
		}
	}

	$separator = (string) apply_filters('document_title_separator', '-');

	return ' ' . $separator . ' ' . $site_name;
}

/**
 * Replace the document title on 410 responses.
 *
 * Hooked on both filters so it works whether the <title> is rendered by core
 * (`pre_get_document_title`) or by Yoast's own presenter (`wpseo_title`).
 * Priority 999 so it runs after Yoast, which hooks the core filter at 15.
 *
 * @param string $title Title resolved so far.
 * @return string
 */
function botphonic_410_document_title($title)
{
	if (!botphonic_is_410_request()) {
		return $title;
	}

	$own_title = botphonic_410_get_title();

	return '' === $own_title ? $title : $own_title . botphonic_410_title_suffix();
}
add_filter('pre_get_document_title', 'botphonic_410_document_title', 999);
add_filter('wpseo_title', 'botphonic_410_document_title', 999);

/**
 * Print the meta description on 410 responses.
 *
 * Priority 1 so it sits near the top of <head>.
 */
function botphonic_410_print_description()
{
	if (!botphonic_is_410_request()) {
		return;
	}

	$description = botphonic_410_get_description();

	if ('' === $description) {
		return;
	}

	printf('<meta name="description" content="%s" />' . "\n", esc_attr($description));
}
add_action('wp_head', 'botphonic_410_print_description', 1);

/**
 * Suppress Yoast's meta description on 410 responses so the page does not
 * carry two description tags. Yoast omits the tag when the value is empty.
 *
 * @param string $description Yoast meta description.
 * @return string
 */
function botphonic_410_wpseo_metadesc($description)
{
	return botphonic_is_410_request() ? '' : $description;
}
add_filter('wpseo_metadesc', 'botphonic_410_wpseo_metadesc', 999);

/**
 * Keep flagged posts out of the Yoast XML sitemap.
 *
 * @param array $excluded_post_ids Post IDs already excluded.
 * @return array
 */
function botphonic_410_exclude_from_sitemap($excluded_post_ids)
{
	$excluded_post_ids = is_array($excluded_post_ids) ? $excluded_post_ids : [];

	$gone_ids = get_posts([
		'post_type' => 'any',
		'post_status' => 'any',
		'posts_per_page' => -1,
		'fields' => 'ids',
		'no_found_rows' => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
		'suppress_filters' => false,
		'meta_query' => [
			[
				'key' => BOTPHONIC_410_META_KEY,
				'value' => '1',
			],
		],
	]);

	return array_values(array_unique(array_merge($excluded_post_ids, $gone_ids)));
}
add_filter('wpseo_exclude_from_sitemap_by_post_ids', 'botphonic_410_exclude_from_sitemap');


/**
 * --------------------------------------------------------------------
 * 4. ADMIN — "Mark as 410 Gone" checkbox
 * --------------------------------------------------------------------
 */

/**
 * Post types that can be flagged as gone.
 *
 * @return string[]
 */
function botphonic_410_supported_post_types()
{
	$post_types = get_post_types(['public' => true], 'names');
	unset($post_types['attachment']);

	/**
	 * Filter the post types offering the 410 Gone flag.
	 *
	 * @param string[] $post_types Post type names.
	 */
	return (array) apply_filters('botphonic_410_post_types', array_values($post_types));
}

/**
 * Register the post meta so it is sanitised consistently and stays
 * protected (leading underscore = not exposed to the REST API).
 */
function botphonic_410_register_meta()
{
	foreach (botphonic_410_supported_post_types() as $post_type) {
		register_post_meta($post_type, BOTPHONIC_410_META_KEY, [
			'type' => 'string',
			'single' => true,
			'show_in_rest' => false,
			'sanitize_callback' => static function ($value) {
				return '1' === (string) $value ? '1' : '';
			},
			'auth_callback' => static function () {
				return current_user_can('edit_posts');
			},
		]);
	}
}
add_action('init', 'botphonic_410_register_meta', 20);

/**
 * Add the meta box to every supported post type.
 */
function botphonic_410_add_meta_box()
{
	add_meta_box(
		'botphonic-410-gone',
		__('HTTP Status (410 Gone)', 'botphonic'),
		'botphonic_410_render_meta_box',
		botphonic_410_supported_post_types(),
		'side',
		'default'
	);
}
add_action('add_meta_boxes', 'botphonic_410_add_meta_box');

/**
 * Render the meta box.
 *
 * @param WP_Post $post Current post.
 */
function botphonic_410_render_meta_box($post)
{
	wp_nonce_field('botphonic_410_save', 'botphonic_410_nonce');

	$is_gone = botphonic_410_is_post_gone($post);
	?>
	<p>
		<label for="botphonic-410-checkbox">
			<input type="checkbox" id="botphonic-410-checkbox" name="botphonic_410_gone" value="1" <?php checked($is_gone); ?> /> <strong><?php esc_html_e('Return HTTP 410 Gone', 'botphonic'); ?></strong>
		</label>
	</p>
	<p class="description">
		<?php esc_html_e('Serves the 410 Gone template with a 410 status code and noindex, and removes this URL from the XML sitemap. Use for content permanently removed — not for content that has moved (use a redirect instead).', 'botphonic'); ?>
	</p>
	<?php
}

/**
 * Persist the checkbox.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 */
function botphonic_410_save_meta_box($post_id, $post)
{
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if (wp_is_post_revision($post_id)) {
		return;
	}

	if (!isset($_POST['botphonic_410_nonce'])) {
		return;
	}

	if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['botphonic_410_nonce'])), 'botphonic_410_save')) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (!in_array($post->post_type, botphonic_410_supported_post_types(), true)) {
		return;
	}

	if (!empty($_POST['botphonic_410_gone'])) {
		update_post_meta($post_id, BOTPHONIC_410_META_KEY, '1');
	} else {
		delete_post_meta($post_id, BOTPHONIC_410_META_KEY);
	}
}
add_action('save_post', 'botphonic_410_save_meta_box', 10, 2);

/**
 * Flag 410 entries in the posts / pages list tables.
 *
 * @param array   $states  Existing post states.
 * @param WP_Post $post    Current post.
 * @return array
 */
function botphonic_410_post_state($states, $post)
{
	if (botphonic_410_is_post_gone($post)) {
		$states['botphonic_410'] = __('410 Gone', 'botphonic');
	}

	return $states;
}
add_filter('display_post_states', 'botphonic_410_post_state', 10, 2);
