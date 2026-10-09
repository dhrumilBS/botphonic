<?php

/**
 * Botphonic child theme — author archive helpers.
 *
 * author.php renders the profile on the blog design system (blog.css + the
 * global --bpg-* tokens in style.css); everything it needs to know about the
 * author is gathered here so the template only prints.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;


/* ==========================================================================
   1. Content
   ========================================================================== */

/**
 * Content types an author page lists, in display order.
 *
 * Post types that are not registered (e.g. a CPT plugin switched off) are
 * dropped, so callers never query a type WordPress does not know.
 *
 * @return array<string,array{label:string,singular:string,anchor:string}>
 */
function botphonic_author_content_types()
{
	$types = array(
		'post' => array(
			'label' => __('Articles', 'botphonic'),
			'singular' => __('Article', 'botphonic'),
			'anchor' => 'articles',
		),
		(defined('BOTPHONIC_ALT_SLUG') ? BOTPHONIC_ALT_SLUG : 'alternatives') => array(
			'label' => __('Comparison guides', 'botphonic'),
			'singular' => __('Comparison guide', 'botphonic'),
			'anchor' => 'comparisons',
		),
		(defined('CUSTOME_STORY_SLUG') ? CUSTOME_STORY_SLUG : 'success-stories') => array(
			'label' => __('Customer stories', 'botphonic'),
			'singular' => __('Customer story', 'botphonic'),
			'anchor' => 'stories',
		),
	);

	return array_filter(
		$types,
		static function ($post_type) {
			return post_type_exists($post_type);
		},
		ARRAY_FILTER_USE_KEY
	);
}

/**
 * Published item count per content type. Memoised per author.
 *
 * @param int $user_id Author ID.
 * @return array<string,int> post type => count (zero counts included).
 */
function botphonic_author_counts($user_id)
{
	static $cache = array();

	$user_id = (int) $user_id;

	if (!isset($cache[$user_id])) {
		$cache[$user_id] = array();

		foreach (array_keys(botphonic_author_content_types()) as $post_type) {
			$cache[$user_id][$post_type] = (int) count_user_posts($user_id, $post_type, true);
		}
	}

	return $cache[$user_id];
}

/**
 * Categories the author writes about, most-used first.
 *
 * Counted from the author's own posts (not the category's site-wide total),
 * and the default "Uncategorized" bucket is left out.
 *
 * @param int $user_id Author ID.
 * @param int $limit   Optional. Maximum topics returned.
 * @return array<int,array{term:WP_Term,count:int}>
 */
function botphonic_author_topics($user_id, $limit = 8)
{
	$post_ids = get_posts(
		array(
			'author' => (int) $user_id,
			'post_type' => 'post',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'fields' => 'ids',
			'no_found_rows' => true,
			'suppress_filters' => false,
		)
	);

	if (empty($post_ids)) {
		return array();
	}

	$terms = wp_get_object_terms($post_ids, 'category', array('fields' => 'all_with_object_id'));

	if (is_wp_error($terms) || empty($terms)) {
		return array();
	}

	$default = (int) get_option('default_category');
	$topics = array();

	foreach ($terms as $term) {
		if ((int) $term->term_id === $default) {
			continue;
		}

		if (!isset($topics[$term->term_id])) {
			$topics[$term->term_id] = array('term' => $term, 'count' => 0);
		}

		$topics[$term->term_id]['count']++;
	}

	uasort(
		$topics,
		static function ($a, $b) {
			return $b['count'] - $a['count'] ?: strnatcasecmp($a['term']->name, $b['term']->name);
		}
	);

	return array_slice(array_values($topics), 0, max(1, (int) $limit));
}

/**
 * First and latest publish dates across everything the author has written.
 *
 * @param int $user_id Author ID.
 * @return array{first:string,latest:string} Dates as 'Y-m-d H:i:s', or '' when none.
 */
function botphonic_author_date_range($user_id)
{
	$range = array('first' => '', 'latest' => '');
	$types = array_keys(botphonic_author_content_types());

	foreach (array('first' => 'ASC', 'latest' => 'DESC') as $key => $order) {
		$ids = get_posts(
			array(
				'author' => (int) $user_id,
				'post_type' => $types,
				'post_status' => 'publish',
				'posts_per_page' => 1,
				'orderby' => 'date',
				'order' => $order,
				'fields' => 'ids',
				'no_found_rows' => true,
			)
		);

		if (!empty($ids)) {
			$range[$key] = get_post_field('post_date', $ids[0]);
		}
	}

	return $range;
}


/* ==========================================================================
   2. Profile
   ========================================================================== */

/**
 * Everything the profile hero and the About section print.
 *
 * Bio sources: `user_description` (the rich HTML bio edited in the profile
 * screen) and core `description` (the plain Biographical Info). The hero
 * shows a short plain lede; the About section shows the rich bio only when
 * there is one, so the same text is never printed twice.
 *
 * @param WP_User $user Author.
 * @return array{
 *     role:string,
 *     lede:string,
 *     about:string,
 *     links:array<int,array{network:string,label:string,url:string}>
 * }
 */
function botphonic_author_profile($user)
{
	$user_id = (int) $user->ID;
	$rich = trim((string) get_user_meta($user_id, 'user_description', true));
	$plain = trim(wp_strip_all_tags((string) get_the_author_meta('description', $user_id)));

	$lede = botphonic_author_trim_sentences($plain !== '' ? $plain : wp_strip_all_tags($rich), 42);

	$links = array();
	$networks = array(
		'linkedin' => 'LinkedIn',
		'twitter' => 'X',
		'youtube' => 'YouTube',
	);

	foreach ($networks as $meta_key => $label) {
		$url = trim((string) get_the_author_meta($meta_key, $user_id));
		if ($url !== '' && wp_http_validate_url($url)) {
			$links[] = array(
				'network' => 'twitter' === $meta_key ? 'x' : $meta_key,
				'label' => $label,
				'url' => $url,
			);
		}
	}

	$website = trim((string) $user->user_url);
	if ($website !== '' && wp_http_validate_url($website)) {
		$links[] = array('network' => 'website', 'label' => __('Website', 'botphonic'), 'url' => $website);
	}

	// Obfuscated so the address is not harvested from the page source.
	if (is_email($user->user_email)) {
		$links[] = array('network' => 'mail', 'label' => __('Email', 'botphonic'), 'url' => 'mailto:' . antispambot($user->user_email));
	}

	return array(
		'role' => trim((string) get_user_meta($user_id, 'user_position', true)),
		'lede' => $lede,
		'about' => $rich !== '' ? $rich : '',
		'links' => $links,
	);
}

/**
 * Trim text to a word budget, ending on a full sentence when one fits.
 *
 * Falls back to a word cut with an ellipsis when the last sentence break is
 * too early to keep at least half of the budget.
 *
 * @param string $text  Plain text.
 * @param int    $words Word budget.
 * @return string
 */
function botphonic_author_trim_sentences($text, $words = 42)
{
	$text = trim(preg_replace('/\s+/u', ' ', (string) $text));
	$parts = explode(' ', $text);

	if (count($parts) <= $words) {
		return $text;
	}

	$cut = implode(' ', array_slice($parts, 0, $words));

	if (preg_match('/^(.*[.!?])\s/u', $cut . ' ', $m) && mb_strlen($m[1]) >= mb_strlen($cut) / 2) {
		return $m[1];
	}

	return rtrim($cut, " ,;:-") . '…';
}

/**
 * Icons the author page needs that the blog icon set does not have.
 *
 * @param string $name Icon key.
 * @return string SVG markup, or the blog icon of the same name.
 */
function botphonic_author_icon($name)
{
	$open = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">';

	$paths = array(
		'website' => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18"/>',
		'mail' => '<rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="M4 7l8 6 8-6"/>',
		'pen' => '<path d="M4 20h4L19 9l-4-4L4 16v4z"/><path d="M13.5 6.5l4 4"/>',
		'layers' => '<path d="M12 3l9 5-9 5-9-5 9-5z"/><path d="M3 13l9 5 9-5"/>',
		'star' => '<path d="M12 3.5l2.6 5.3 5.9.9-4.2 4.1 1 5.8L12 16.9l-5.3 2.7 1-5.8-4.2-4.1 5.9-.9z"/>',
	);

	if ('youtube' === $name) {
		return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M23 7.2a3 3 0 0 0-2.1-2.1C19 4.6 12 4.6 12 4.6s-7 0-8.9.5A3 3 0 0 0 1 7.2 31 31 0 0 0 .5 12 31 31 0 0 0 1 16.8a3 3 0 0 0 2.1 2.1c1.9.5 8.9.5 8.9.5s7 0 8.9-.5a3 3 0 0 0 2.1-2.1 31 31 0 0 0 .5-4.8 31 31 0 0 0-.5-4.8zM9.8 15.1V8.9l5.4 3.1z"/></svg>';
	}

	if (isset($paths[$name])) {
		return $open . $paths[$name] . '</svg>';
	}

	return function_exists('botphonic_blog_icon') ? botphonic_blog_icon($name) : '';
}

/**
 * Person schema for the author page — only when Yoast is not active, since
 * Yoast already prints ProfilePage + Person for author archives and two
 * Person nodes for one human would compete.
 *
 * @param WP_User $user    Author.
 * @param array   $profile Result of botphonic_author_profile().
 */
function botphonic_author_schema($user, $profile)
{
	if (defined('WPSEO_VERSION')) {
		return;
	}

	$person = array(
		'@context' => 'https://schema.org',
		'@type' => 'ProfilePage',
		'mainEntity' => array_filter(
			array(
				'@type' => 'Person',
				'name' => $user->display_name,
				'url' => get_author_posts_url($user->ID),
				'image' => get_avatar_url($user->ID, array('size' => 256)),
				'jobTitle' => $profile['role'],
				'description' => $profile['lede'],
				'worksFor' => array('@type' => 'Organization', 'name' => 'Botphonic', 'url' => home_url('/')),
				'sameAs' => array_values(array_filter(wp_list_pluck($profile['links'], 'url'), 'wp_http_validate_url')),
			)
		),
	);

	echo '<script type="application/ld+json">' . wp_json_encode($person, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
