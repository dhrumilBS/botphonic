<?php
/**
 * Shared identity helpers for the modernized page-template set.
 */

defined('ABSPATH') || exit;

if (!defined('BOTPHONIC_MODERN_STYLE')) {
	define('BOTPHONIC_MODERN_STYLE', 'botphonic-template-modern');
}

/**
 * Return the exact page-template allowlist covered by the modernization layer.
 *
 * @return string[]
 */
function botphonic_modern_template_allowlist()
{
	return array(
		'temp-agent-studio.php',
		'temp-ai-voice-agents.php',
		'temp-cold-mail.php',
		'temp-contact.php',
		'temp-cta.php',
		'temp-elementor-blank.php',
		'temp-jv.php',
		'temp-legal-page.php',
		'temp-marketplace.php',
		'temp-multilingual.php',
		'temp-new-home-page.php',
		'temp-personalize-mail.php',
		'temp-pricings.php',
		'temp-prospect-enrichment.php',
		'temp-sitemap.php',
		'temp-unified-mail.php',
		'temp-usa-main.php',
		'temp-usa.php',
	);
}

/**
 * Resolve the active allowlisted template's file name.
 *
 * The assigned `_wp_page_template` slug of the queried singular object is
 * authoritative for normal page templates. The resolved template path is also
 * checked because legacy routes can load a template (notably temp-usa.php)
 * without assigning that slug.
 *
 * Only the queried object is consulted: on archives and search results the
 * global post is merely the first loop item, and its template must not leak
 * onto the listing. Solution pages are skipped because their template is
 * routed by inc/function-solution-pages.php regardless of the assigned slug.
 *
 * @return string Empty when the active template is not allowlisted.
 */
function botphonic_get_current_template_basename()
{
	if (function_exists('botphonic_is_solution_page') && botphonic_is_solution_page()) {
		return '';
	}

	$allowlist = botphonic_modern_template_allowlist();
	$candidates = array();

	if (is_singular()) {
		$assigned_slug = get_page_template_slug(get_queried_object_id());

		if (is_string($assigned_slug) && '' !== $assigned_slug && 'default' !== $assigned_slug) {
			$candidates[] = wp_basename(wp_normalize_path($assigned_slug));
		}
	}

	global $template;
	if (is_string($template) && '' !== $template) {
		$candidates[] = wp_basename(wp_normalize_path($template));
	}

	foreach (array_unique($candidates) as $candidate) {
		if (in_array($candidate, $allowlist, true)) {
			return $candidate;
		}
	}

	return '';
}

/**
 * Return the CSS-safe key for the active modern template.
 *
 * Example: temp-ai-voice-agents.php becomes ai-voice-agents.
 *
 * @return string
 */
function botphonic_get_current_template_key()
{
	$template_file = botphonic_get_current_template_basename();

	if ('' === $template_file) {
		return '';
	}

	return sanitize_key(substr($template_file, 5, -4));
}

/**
 * Determine whether the request uses an allowlisted modern template.
 *
 * An optional file name or key can narrow the check to one template.
 *
 * @param string|null $template Template file name or normalized key.
 * @return bool
 */
function botphonic_is_modern_template($template = null)
{
	$current_key = botphonic_get_current_template_key();

	if ('' === $current_key) {
		return false;
	}

	if (null === $template || '' === $template) {
		return true;
	}

	$template = wp_basename(wp_normalize_path((string) $template));
	if (in_array($template, botphonic_modern_template_allowlist(), true)) {
		$template = substr($template, 5, -4);
	}

	return $current_key === sanitize_key($template);
}

/**
 * Scope shared and template-specific modernization rules on the body element.
 *
 * @param string[] $classes Existing body classes.
 * @return string[]
 */
function botphonic_modern_template_body_classes($classes)
{
	$template_key = botphonic_get_current_template_key();

	if ('' === $template_key) {
		return $classes;
	}

	$classes[] = 'bpm-page';
	$classes[] = 'bpm-page--' . sanitize_html_class($template_key);

	return array_values(array_unique($classes));
}
add_filter('body_class', 'botphonic_modern_template_body_classes');
