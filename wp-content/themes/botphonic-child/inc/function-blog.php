<?php

/**
 * Botphonic child theme — blog helpers.
 * @package Botphonic
 */

defined('ABSPATH') || exit;
if (!defined('BOTPHONIC_BLOG_STYLE')) {
	define('BOTPHONIC_BLOG_STYLE', 'botphonic-blog');
}

if (!defined('BOTPHONIC_BLOG_WPM')) {
	define('BOTPHONIC_BLOG_WPM', 200);
}


/* ==========================================================================
   1. View detection
   ========================================================================== */

/**
 * @return bool
 */
function botphonic_is_blog_single_view()
{
	return is_singular('post');
}

/**
 * @return bool
 */
function botphonic_is_blog_archive_view()
{
	if (is_home()) {
		return true;
	}

	// Author archives are blog listings too (author.php is built on the same
	// .bpg-archive components), so they get blog.css, blog.js and body.bpg-blog.
	return is_archive() && !is_post_type_archive();
}

/**
 * Any of the blog views.
 *
 * @return bool
 */
function botphonic_is_blog_view()
{
	return botphonic_is_blog_single_view() || botphonic_is_blog_archive_view();
}

/**
 * Author archives show only the author's 3 latest posts: one row of the
 * three-column card grid, no pagination (author.php prints none). Every other
 * listing keeps Settings → Reading (12).
 *
 * @param WP_Query $query Query being prepared.
 * @return void
 */
function botphonic_blog_author_per_page($query)
{
	if (!is_admin() && $query->is_main_query() && $query->is_author()) {
		$query->set('posts_per_page', 3);
	}
}
add_action('pre_get_posts', 'botphonic_blog_author_per_page');

/**
 * @param string[] $classes Body classes.
 * @return string[]
 */
function botphonic_blog_body_class($classes)
{
	if (botphonic_is_blog_view()) {
		$classes[] = 'bpg-blog';
	}

	return $classes;
}
add_filter('body_class', 'botphonic_blog_body_class');

/**
 * @param string $output Already-built attribute string, e.g. `lang="en-US"`.
 * @return string
 */
function botphonic_blog_html_class($output)
{
	if (is_admin() || !botphonic_is_blog_view()) {
		return $output;
	}

	return trim($output . ' class="bpg-blog-html"');
}
add_filter('language_attributes', 'botphonic_blog_html_class');


/* ==========================================================================
   2. Post metadata
   ========================================================================== */

/**
 * Estimated reading time for a post.
 *
 * Memoised per post: the listing templates ask for it once per card, and the
 * single template once per related card, all inside the same request.
 *
 * @param int|WP_Post|null $post   Optional. Defaults to the current post.
 * @param string           $suffix Optional. Text appended to the number.
 * @return string e.g. "6 min read"
 */
function botphonic_blog_reading_time($post = null, $suffix = ' min read')
{
	static $cache = array();

	$post = get_post($post);

	if (!$post instanceof WP_Post) {
		return '1' . $suffix;
	}

	if (!isset($cache[$post->ID])) {
		// strip_tags() (via wp_strip_all_tags) also drops the block editor's
		// HTML comments, so only human-readable text is counted.
		$text = wp_strip_all_tags(strip_shortcodes($post->post_content));

		// Unicode-aware: str_word_count() ignores non-ASCII letters and digits.
		$words = (int) preg_match_all('/[\p{L}\p{N}]+/u', $text);

		$cache[$post->ID] = max(1, (int) ceil($words / max(1, (int) BOTPHONIC_BLOG_WPM)));
	}

	return $cache[$post->ID] . $suffix;
}

/**
 * Primary category for a post, honouring Yoast's primary term when set.
 *
 * Mirrors the logic already used by wpdev_breadcrumbs() so breadcrumbs and the
 * visible category chip never disagree. Memoised per post.
 *
 * @param int|WP_Post|null $post Optional. Defaults to the current post.
 * @return WP_Term|null
 */
function botphonic_blog_primary_category($post = null)
{
	static $cache = array();

	$post = get_post($post);

	if (!$post instanceof WP_Post) {
		return null;
	}

	if (array_key_exists($post->ID, $cache)) {
		return $cache[$post->ID];
	}

	$term = null;

	if (class_exists('WPSEO_Primary_Term')) {
		$primary_term = new WPSEO_Primary_Term('category', $post->ID);
		$primary_id = $primary_term->get_primary_term();

		if ($primary_id) {
			$maybe = get_term($primary_id, 'category');
			if ($maybe instanceof WP_Term) {
				$term = $maybe;
			}
		}
	}

	if (!$term) {
		$categories = get_the_category($post->ID);
		if (!empty($categories) && $categories[0] instanceof WP_Term) {
			$term = $categories[0];
		}
	}

	$cache[$post->ID] = $term;

	return $term;
}

/**
 * Short, plain-text summary for cards and hero ledes.
 *
 * @param int|WP_Post|null $post  Optional. Defaults to the current post.
 * @param int              $words Optional. Word budget.
 * @return string
 */
function botphonic_blog_excerpt($post = null, $words = 24)
{
	$post = get_post($post);

	if (!$post instanceof WP_Post) {
		return '';
	}

	$text = has_excerpt($post) ? get_the_excerpt($post) : strip_shortcodes($post->post_content);
	$text = wp_strip_all_tags($text, true);

	// Literal ellipsis (not an entity) so callers can safely esc_html() it.
	return wp_trim_words($text, max(1, (int) $words), '…');
}

/**
 * Share targets for a post.
 *
 * Returned URLs are complete and ready for esc_url(); the caller does not have
 * to know how each network encodes its parameters.
 *
 * @param int|WP_Post|null $post Optional. Defaults to the current post.
 * @return array<int,array{network:string,label:string,url:string}>
 */
function botphonic_blog_share_links($post = null)
{
	$post = get_post($post);

	if (!$post instanceof WP_Post) {
		return array();
	}

	$url = rawurlencode(get_permalink($post));
	$title = rawurlencode(get_the_title($post));

	return array(
		array(
			'network' => 'linkedin',
			'label' => 'LinkedIn',
			'url' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $url,
		),
		array(
			'network' => 'x',
			'label' => 'X',
			'url' => 'https://x.com/intent/tweet?url=' . $url . '&text=' . $title,
		),
		array(
			'network' => 'facebook',
			'label' => 'Facebook',
			'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . $url,
		),
	);
}

/**
 * Related posts for the single template.
 *
 * Latest posts from the same primary category, falling back to the latest
 * posts overall so the section is never half-empty on a thin category.
 *
 * @param int|WP_Post|null $post  Optional. Defaults to the current post.
 * @param int              $limit Optional. How many posts to return.
 * @return WP_Query|null Query with results, or null when there is nothing to show.
 */
function botphonic_blog_related_posts($post = null, $limit = 3)
{
	$post = get_post($post);

	if (!$post instanceof WP_Post) {
		return null;
	}

	$limit = max(1, (int) $limit);

	$args = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => $limit,
		'post__not_in' => array($post->ID),
		'ignore_sticky_posts' => true,
		'no_found_rows' => true,
		'orderby' => 'date',
		'order' => 'DESC',
	);

	$category = botphonic_blog_primary_category($post);

	if ($category) {
		$query = new WP_Query($args + array('cat' => (int) $category->term_id));

		if ($query->have_posts()) {
			return $query;
		}
	}

	$query = new WP_Query($args);

	return $query->have_posts() ? $query : null;
}


/* ==========================================================================
   3. Archive labels
   ========================================================================== */

/**
 * Eyebrow + heading for the archive hero.
 * @return array{eyebrow:string,title:string}
 */
function botphonic_blog_archive_labels()
{
	$eyebrow = 'Archive';
	$title = 'Blog';

	if (is_category()) {
		$eyebrow = 'Topic';
		$title = single_cat_title('', false);
	} elseif (is_tag()) {
		$eyebrow = 'Tag';
		$title = single_tag_title('', false);
	} elseif (is_tax()) {
		$term = get_queried_object();
		$title = single_term_title('', false);

		if ($term instanceof WP_Term) {
			$taxonomy = get_taxonomy($term->taxonomy);
			if ($taxonomy && !empty($taxonomy->labels->singular_name)) {
				$eyebrow = $taxonomy->labels->singular_name;
			}
		}
	} elseif (is_day() || is_month() || is_year()) {
		// Built from query vars rather than get_the_date(), so the heading is
		// still correct when the archive has no posts to loop over.
		$year = (int) get_query_var('year');
		$month = (int) get_query_var('monthnum');
		$day = (int) get_query_var('day');
		$stamp = mktime(0, 0, 0, $month ? $month : 1, $day ? $day : 1, $year ? $year : (int) gmdate('Y'));

		if (is_day()) {
			$title = date_i18n(get_option('date_format'), $stamp);
		} elseif (is_month()) {
			$title = date_i18n('F Y', $stamp);
		} else {
			$title = (string) $year;
		}
	} elseif (is_search()) {
		$eyebrow = 'Search';
		$title = get_search_query();
	} else {
		// Post formats and anything else that lands on archive.php.
		$fallback = wp_strip_all_tags(get_the_archive_title());
		if ($fallback) {
			$title = preg_replace('/^[^:]{1,24}:\s*/', '', $fallback);
		}
	}

	return array(
		'eyebrow' => $eyebrow,
		'title' => $title !== '' ? $title : 'Blog',
	);
}


/* ==========================================================================
   4. Icons
   ========================================================================== */

/**
 * Inline SVG icons used across the blog templates.
 *
 * Returning markup from a whitelist keeps the templates readable and avoids
 * repeating the same paths in four files.
 *
 * @param string $name Icon key.
 * @return string Escaped-by-construction SVG markup, or an empty string.
 */
function botphonic_blog_icon($name)
{
	$open = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">';

	$paths = array(
		'calendar' => '<rect x="3" y="4.5" width="18" height="16" rx="2.5"/><path d="M8 3v3M16 3v3M3 10h18"/>',
		'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7.5V12l3 2"/>',
		'list' => '<path d="M4 6h16M4 12h11M4 18h7"/>',
		'chevron-down' => '<path d="M6 9l6 6 6-6"/>',
		'close' => '<path d="M6 6l12 12M18 6L6 18"/>',
		'arrow-right' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'link' => '<path d="M9.5 14.5a3.5 3.5 0 0 0 5 0l3-3a3.5 3.5 0 0 0-5-5l-1 1"/><path d="M14.5 9.5a3.5 3.5 0 0 0-5 0l-3 3a3.5 3.5 0 0 0 5 5l1-1"/>',
		'mail' => '<rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="M3.5 7.5l8.5 6 8.5-6"/>',
	);

	$raw = array(
		'linkedin' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor" aria-hidden="true" focusable="false"><path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1a53.79 53.79 0 1 1 53.79-53.79 53.79 53.79 0 0 1-53.79 53.79zM447.9 448h-92.4V302.4c0-34.7-.7-79.3-48.3-79.3-48.3 0-55.7 37.7-55.7 76.7V448h-92.4V148.9h88.7v40.8h1.3c12.4-23.5 42.7-48.3 87.8-48.3 93.9 0 111.2 61.8 111.2 142.3z"/></svg>',
		'x' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" aria-hidden="true" focusable="false"><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48h145.6l100.5 132.9zM364.4 421.8h39.1L151.1 88h-42z"/></svg>',
		'facebook' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" fill="currentColor" aria-hidden="true" focusable="false"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>',
		'youtube' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" fill="currentColor" aria-hidden="true" focusable="false"><path d="M44.9 14.5c-.4-2.2-2.3-3.8-4.5-4.3C37.1 9.5 31 9 24.4 9S11.6 9.5 8.3 10.2c-2.2.5-4.1 2-4.5 4.3C3.4 17 3 20.5 3 25s.4 8 .9 10.5c.4 2.2 2.3 3.8 4.5 4.3C11.9 40.5 17.9 41 24.5 41s12.6-.5 16.1-1.2c2.2-.5 4.1-2 4.5-4.3.4-2.5.9-6.1 1-10.5-.2-4.5-.7-8-1.2-10.5zM19 32V18l12.2 7z"/></svg>',
	);

	if (isset($raw[$name])) {
		return $raw[$name];
	}

	if (!isset($paths[$name])) {
		return '';
	}

	return $open . $paths[$name] . '</svg>';
}


/* ==========================================================================
   5. Category filter rail
   ========================================================================== */

/**
 * @param array $args {
 *     @type int  $limit       Optional. Inline categories before the overflow menu.
 *     @type bool $show_counts Optional. Append post counts.
 * }
 */
function botphonic_blog_category_filter($args = array())
{
	$args = wp_parse_args(
		$args,
		array(
			'limit' => 5,
			'show_counts' => true,
		)
	);

	$categories = get_categories(
		array(
			'hide_empty' => true,
			'orderby' => 'count',
			'order' => 'DESC',
		)
	);

	if (empty($categories)) {
		return;
	}

	$current_id = 0;
	if (is_category()) {
		$queried = get_queried_object();
		if ($queried instanceof WP_Term) {
			$current_id = (int) $queried->term_id;
		}
	}

	$limit = max(1, (int) $args['limit']);
	$inline = array_slice($categories, 0, $limit);
	$overflow = array_slice($categories, $limit);

	if ($current_id && !empty($overflow)) {
		$inline_ids = wp_list_pluck($inline, 'term_id');

		if (!in_array($current_id, array_map('intval', $inline_ids), true)) {
			foreach ($overflow as $index => $candidate) {
				if ((int) $candidate->term_id === $current_id) {
					$demoted = array_pop($inline);
					array_splice($overflow, $index, 1);
					array_unshift($overflow, $demoted);
					$inline[] = $candidate;
					break;
				}
			}
		}
	}

	usort(
		$overflow,
		static function ($a, $b) {
			return strnatcasecmp($a->name, $b->name);
		}
	);

	$posts_page_id = (int) get_option('page_for_posts');
	$all_url = $posts_page_id ? get_permalink($posts_page_id) : home_url('/');

	/**
	 * One pill. Kept local so the inline row and the overflow panel can never
	 * drift apart.
	 *
	 * @param string $url    Destination.
	 * @param string $label  Visible text.
	 * @param bool   $active Whether this is the current view.
	 * @param string $class  Base class for the link.
	 * @param int    $count  Post count, or 0 to omit.
	 * @return string
	 */
	$pill = static function ($url, $label, $active, $class, $count = 0) {
		return sprintf(
			'<a class="%1$s%2$s" href="%3$s"%4$s>%5$s%6$s</a>',
			esc_attr($class),
			$active ? ' is-active' : '',
			esc_url($url),
			$active ? ' aria-current="page"' : '',
			esc_html($label),
			$count ? '<span class="bpg-filter__count">' . absint($count) . '</span>' : ''
		);
	};

	echo '<nav class="bpg-filters" aria-label="Browse articles by topic">';
	echo '<div class="bpg-filters__track">';
	echo $pill($all_url, 'All articles', is_home(), 'bpg-filter');

	foreach ($inline as $category) {
		echo $pill(
			get_category_link($category->term_id),
			$category->name,
			($current_id === (int) $category->term_id),
			'bpg-filter',
			$args['show_counts'] ? (int) $category->count : 0
		);
	}

	echo '</div>';

	if (!empty($overflow)) {
		echo '<details class="bpg-more">';
		echo '<summary class="bpg-filter bpg-more__summary"><span>More topics</span>' . botphonic_blog_icon('chevron-down') . '</summary>';
		echo '<div class="bpg-more__panel">';

		foreach ($overflow as $category) {
			echo $pill(
				get_category_link($category->term_id),
				$category->name,
				($current_id === (int) $category->term_id),
				'bpg-more__link',
				$args['show_counts'] ? (int) $category->count : 0
			);
		}

		echo '</div></details>';
	}

	echo '</nav>';
}
