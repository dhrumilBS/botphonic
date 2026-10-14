<?php

/**
 * ====================================================================
 * BOTPHONIC CHILD THEME — BLOG MIGRATION / LEGACY URL REDIRECTS (301)
 * ====================================================================
 *
 * Blog posts moved from /<slug>/ to /blog/<slug>/ when the permalink
 * structure changed to /blog/%postname%/. Old URLs are still indexed and
 * linked from outside, so any 404 that matches a moved URL is sent to its
 * new home with a 301. The query string (UTM, gclid) is kept.
 *
 *   /<post-slug>/           ->  /blog/<post-slug>/
 *   /category|tag|author/…  ->  /blog/category|tag|author/…
 *   renamed pages           ->  botphonic_legacy_page_redirects()
 *
 * @package Botphonic_Child
 */

defined('ABSPATH') || exit;

/**
 * Pages whose slug changed: 'old-path' => 'new-path'.
 *
 * @return array<string,string>
 */
function botphonic_legacy_page_redirects()
{
	return array(
		'pricings-plans' => 'pricing',
	);
}

/**
 * Request path relative to the site root, without surrounding slashes.
 * Works when WordPress lives in a subfolder (e.g. /botphonic/ locally).
 *
 * @return string
 */
function botphonic_legacy_request_path()
{
	$request = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '';
	$path = (string) wp_parse_url($request, PHP_URL_PATH);
	$home_path = (string) wp_parse_url(home_url('/'), PHP_URL_PATH);

	if ('' !== $home_path && 0 === strpos($path, $home_path)) {
		$path = substr($path, strlen($home_path));
	}

	return trim(rawurldecode($path), '/');
}

/**
 * Work out where a 404 path has moved to, if anywhere.
 *
 * @param string $path Request path relative to the site root.
 * @return string Absolute target URL, or '' when the path is not a moved URL.
 */
function botphonic_legacy_redirect_target($path)
{
	if ('' === $path) {
		return '';
	}

	$pages = botphonic_legacy_page_redirects();
	if (isset($pages[$path])) {
		return home_url(user_trailingslashit('/' . $pages[$path]));
	}

	if (preg_match('#^(category|tag|author)/.+#', $path)) {
		return home_url(user_trailingslashit('/blog/' . $path));
	}

	if (preg_match('/^[a-z0-9_-]+$/i', $path)) {
		$post = get_page_by_path($path, OBJECT, 'post');
		if ($post && 'post' === $post->post_type && 'publish' === $post->post_status) {
			return (string) get_permalink($post);
		}
	}

	return '';
}
function botphonic_legacy_redirect()
{
	if (!is_404() || is_admin()) {
		return;
	}

	$method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
	if (!in_array($method, array('GET', 'HEAD'), true)) {
		return;
	}

	$target = botphonic_legacy_redirect_target(botphonic_legacy_request_path());
	if ('' === $target) {
		return;
	}

	$query = isset($_SERVER['QUERY_STRING']) ? wp_unslash($_SERVER['QUERY_STRING']) : '';
	if ('' !== $query) {
		$target .= (false === strpos($target, '?') ? '?' : '&') . $query;
	}

	wp_safe_redirect($target, 301, 'Botphonic Blog Migration');
	exit;
}
add_action('template_redirect', 'botphonic_legacy_redirect', 5);
