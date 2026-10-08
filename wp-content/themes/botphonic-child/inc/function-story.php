<?php

/**
 * Botphonic child theme — Customer Story (success-stories) helpers.
 *
 * The success-stories templates reuse the blog design system wholesale: the
 * `bpg-*` tokens, primitives, cards, pagination, share rail and CTA blocks all
 * live in assets/css/blog.css, and the sticky table of contents, reading
 * progress rail, scroll-spy and clipboard behaviour all live in
 * assets/js/blog.js. This file supplies the three things that are specific to
 * the post type:
 *
 *   1. view detection + the body/html classes those two assets key off,
 *   2. safe readers for the ACF fields the templates render,
 *   3. a single ordered description of the story body, so the section nav and
 *      the sections themselves can never drift apart.
 *
 * Nothing here assumes ACF is active: every reader degrades to an empty value,
 * and the templates skip whatever is empty.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

if (!defined('BOTPHONIC_STORY_STYLE')) {
	define('BOTPHONIC_STORY_STYLE', 'botphonic-story');
}


/* ==========================================================================
   1. View detection
   ========================================================================== */

/**
 * Post type slug, resilient to the constant not being defined yet.
 *
 * @return string
 */
function botphonic_story_post_type()
{
	return defined('CUSTOME_STORY_SLUG') ? CUSTOME_STORY_SLUG : 'success-stories';
}

/**
 * @return bool
 */
function botphonic_is_story_single_view()
{
	return is_singular(botphonic_story_post_type());
}

/**
 * @return bool
 */
function botphonic_is_story_archive_view()
{
	return is_post_type_archive(botphonic_story_post_type());
}

/**
 * Any of the customer story views.
 *
 * @return bool
 */
function botphonic_is_story_view()
{
	return botphonic_is_story_single_view() || botphonic_is_story_archive_view();
}

/**
 * `bpg-blog` is what the navbar chrome in assets/js/blog.js and the phone-width
 * navbar fix in blog.css § 3.1 look for; `bpg-story` is the hook the story
 * stylesheet uses so it can never reach a post or a landing page.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function botphonic_story_body_class($classes)
{
	if (botphonic_is_story_view()) {
		$classes[] = 'bpg-blog';
		$classes[] = 'bpg-story';
	}

	return $classes;
}
add_filter('body_class', 'botphonic_story_body_class');

/**
 * Mirrors botphonic_blog_html_class() for story views. The two are mutually
 * exclusive — a story is never a blog view — so the attribute is only ever
 * written once.
 *
 * @param string $output Already-built attribute string, e.g. `lang="en-US"`.
 * @return string
 */
function botphonic_story_html_class($output)
{
	if (is_admin() || !botphonic_is_story_view()) {
		return $output;
	}

	return trim($output . ' class="bpg-blog-html"');
}
add_filter('language_attributes', 'botphonic_story_html_class');


/* ==========================================================================
   2. Field readers
   ========================================================================== */

/**
 * One ACF field, or an empty string when ACF is unavailable.
 *
 * @param string           $name Field name.
 * @param int|WP_Post|null $post Optional. Defaults to the current post.
 * @return mixed
 */
function botphonic_story_field($name, $post = null)
{
	if (!function_exists('get_field')) {
		return '';
	}

	$post = get_post($post);
	$value = get_field($name, $post instanceof WP_Post ? $post->ID : false);

	return (null === $value || false === $value) ? '' : $value;
}

/**
 * Trimmed single-line field value, ready for esc_html().
 *
 * @param string           $name Field name.
 * @param int|WP_Post|null $post Optional.
 * @return string
 */
function botphonic_story_text($name, $post = null)
{
	$value = botphonic_story_field($name, $post);

	return is_scalar($value) ? trim(wp_strip_all_tags((string) $value)) : '';
}

/**
 * Resolve an image field to a URL, whatever return format it is configured for.
 *
 * Handles the three shapes ACF can hand back (array, attachment ID, raw URL)
 * so the templates do not have to care how the field was set up.
 *
 * @param mixed  $value Field value.
 * @param string $size  Optional. Registered image size for ID/array values.
 * @return string URL, or an empty string.
 */
function botphonic_story_image_url($value, $size = 'large')
{
	if (is_array($value)) {
		if (!empty($value['sizes'][$size])) {
			return (string) $value['sizes'][$size];
		}

		return !empty($value['url']) ? (string) $value['url'] : '';
	}

	if (is_numeric($value)) {
		$src = wp_get_attachment_image_src((int) $value, $size);

		return $src ? (string) $src[0] : '';
	}

	if (is_string($value) && '' !== trim($value)) {
		return esc_url_raw(trim($value));
	}

	return '';
}

/**
 * Attachment ID behind an image field, when there is one.
 *
 * @param mixed $value Field value.
 * @return int 0 when the field holds a bare URL.
 */
function botphonic_story_image_id($value)
{
	if (is_array($value)) {
		return isset($value['ID']) ? (int) $value['ID'] : (isset($value['id']) ? (int) $value['id'] : 0);
	}

	return is_numeric($value) ? (int) $value : 0;
}

/**
 * Hero lede for a story: the ACF summary, falling back to the excerpt.
 *
 * @param int|WP_Post|null $post Optional.
 * @return string
 */
function botphonic_story_lede($post = null)
{
	$post = get_post($post);
	$lede = botphonic_story_text('main_text', $post);

	if ('' !== $lede) {
		return $lede;
	}

	if ($post instanceof WP_Post && has_excerpt($post)) {
		return trim(wp_strip_all_tags(get_the_excerpt($post)));
	}

	return '';
}

/**
 * Headline for a story: the ACF heading, falling back to the post title.
 *
 * @param int|WP_Post|null $post Optional.
 * @return string
 */
function botphonic_story_headline($post = null)
{
	$post = get_post($post);
	$heading = botphonic_story_text('main_heading', $post);

	return '' !== $heading ? $heading : get_the_title($post);
}


/* ==========================================================================
   3. Result metrics
   ========================================================================== */

/**
 * Normalised `stat_boxes` repeater.
 *
 * Read in one go rather than through have_rows()/the_sub_field(), because the
 * same rows are rendered twice on the single view (hero counters and outcome
 * cards) and once on every archive card. Rows with neither a value nor a label
 * are dropped so an empty repeater row cannot print a blank tile.
 *
 * @param int|WP_Post|null $post  Optional.
 * @param int              $limit Optional. 0 for all rows.
 * @return array<int,array{prefix:string,value:string,suffix:string,label:string,description:string,icon:mixed}>
 */
function botphonic_story_stats($post = null, $limit = 0)
{
	static $cache = array();

	$post = get_post($post);

	if (!$post instanceof WP_Post) {
		return array();
	}

	if (!isset($cache[$post->ID])) {
		$rows = botphonic_story_field('stat_boxes', $post);
		$stats = array();

		if (is_array($rows)) {
			foreach ($rows as $row) {
				if (!is_array($row)) {
					continue;
				}

				// The repeater ships the label sub-field as `Label`; the
				// lower-case spelling is accepted too, in case it is ever fixed.
				$label = isset($row['Label']) ? $row['Label'] : (isset($row['label']) ? $row['label'] : '');

				$stat = array(
					'prefix' => isset($row['prefix']) ? trim(wp_strip_all_tags((string) $row['prefix'])) : '',
					'value' => isset($row['target_value']) ? trim(wp_strip_all_tags((string) $row['target_value'])) : '',
					'suffix' => isset($row['suffix']) ? trim(wp_strip_all_tags((string) $row['suffix'])) : '',
					'label' => trim(wp_strip_all_tags((string) $label)),
					'description' => isset($row['description']) ? trim(wp_strip_all_tags((string) $row['description'])) : '',
					'icon' => isset($row['icon']) ? $row['icon'] : '',
				);

				if ('' === $stat['value'] && '' === $stat['label']) {
					continue;
				}

				$stats[] = $stat;
			}
		}

		$cache[$post->ID] = $stats;
	}

	$stats = $cache[$post->ID];
	$limit = max(0, (int) $limit);

	return $limit ? array_slice($stats, 0, $limit) : $stats;
}

/**
 * The numeric part of a stat, for the count-up animation.
 *
 * Returns an empty string for anything that is not a plain number (e.g. "3×
 * faster"), which tells assets/js/success-stories.js to leave that tile alone
 * and print the value as authored.
 *
 * @param string $value Raw field value.
 * @return string
 */
function botphonic_story_stat_target($value)
{
	$clean = str_replace(array(',', ' ', "\xc2\xa0"), '', (string) $value);

	return preg_match('/^\d+(\.\d+)?$/', $clean) ? $clean : '';
}


/* ==========================================================================
   4. Story body
   ========================================================================== */

/**
 * The ordered narrative of a story.
 *
 * Single source of truth for the body: the section nav in the sidebar and the
 * sections in the main column are both built from this array, so a missing
 * heading can never leave a dead anchor in the nav. Each entry carries its own
 * slug, which is also the `id` the nav links to.
 *
 * `outcomes` is flagged so the template knows to append the metric cards after
 * its prose.
 *
 * @param int|WP_Post|null $post Optional.
 * @return array<int,array{id:string,title:string,content:string,image:string,image_id:int,outcomes:bool}>
 */
function botphonic_story_sections($post = null)
{
	$post = get_post($post);

	if (!$post instanceof WP_Post) {
		return array();
	}

	$overview_image = botphonic_story_field('overview_image', $post);

	$blueprint = array(
		array(
			'id' => 'overview',
			'heading' => botphonic_story_text('label', $post),
			'fallback' => 'Overview',
			'content' => botphonic_story_field('overview_content', $post),
			'image' => $overview_image,
			'outcomes' => false,
		),
		array(
			'id' => 'challenges',
			'heading' => botphonic_story_text('challenges_heading', $post),
			'fallback' => 'The challenge',
			'content' => botphonic_story_field('challenges_content', $post),
			'image' => '',
			'outcomes' => false,
		),
		array(
			'id' => 'actions',
			'heading' => botphonic_story_text('actions_heading', $post),
			'fallback' => 'What we did',
			'content' => botphonic_story_field('actions_content', $post),
			'image' => '',
			'outcomes' => false,
		),
		array(
			'id' => 'outcomes',
			'heading' => botphonic_story_text('quantifiable_outcomes_heading', $post),
			'fallback' => 'Quantifiable outcomes',
			'content' => botphonic_story_field('quantifiable_outcomes_content', $post),
			'image' => '',
			'outcomes' => true,
		),
		array(
			'id' => 'conclusion',
			'heading' => botphonic_story_text('conclusion_heading', $post),
			'fallback' => 'The result',
			'content' => botphonic_story_field('conclusion_content', $post),
			'image' => '',
			'outcomes' => false,
		),
	);

	$has_outcome_cards = (bool) botphonic_story_stats($post);
	$sections = array();

	foreach ($blueprint as $part) {
		$content = is_scalar($part['content']) ? trim((string) $part['content']) : '';
		$image = botphonic_story_image_url($part['image'], 'large');
		$renders_cards = $part['outcomes'] && $has_outcome_cards;

		// A section with no prose, no image and no cards has nothing to show.
		if ('' === $content && '' === $image && !$renders_cards) {
			continue;
		}

		$sections[] = array(
			'id' => 'story-' . $part['id'],
			'title' => '' !== $part['heading'] ? $part['heading'] : $part['fallback'],
			'content' => $content,
			'image' => $image,
			'image_id' => botphonic_story_image_id($part['image']),
			'outcomes' => $renders_cards,
		);
	}

	return $sections;
}

/**
 * Render a story prose field.
 *
 * wpautop() is a no-op on WYSIWYG output that already carries block-level
 * tags, so one call covers both a textarea and an editor field.
 *
 * @param string $content Raw field value.
 * @return string
 */
function botphonic_story_prose($content)
{
	$content = (string) $content;

	if ('' === trim($content)) {
		return '';
	}

	return wpautop(wp_kses_post($content));
}


/* ==========================================================================
   5. Listing helpers
   ========================================================================== */

/**
 * Other customer stories, for the single view's "More customer stories" rail.
 *
 * @param int|WP_Post|null $post  Optional.
 * @param int              $limit Optional.
 * @return WP_Query|null Query with results, or null when there is nothing to show.
 */
function botphonic_story_related($post = null, $limit = 3)
{
	$post = get_post($post);

	if (!$post instanceof WP_Post) {
		return null;
	}

	$query = new WP_Query(
		array(
			'post_type' => botphonic_story_post_type(),
			'post_status' => 'publish',
			'posts_per_page' => max(1, (int) $limit),
			'post__not_in' => array($post->ID),
			'ignore_sticky_posts' => true,
			'no_found_rows' => true,
			'orderby' => 'date',
			'order' => 'DESC',
		)
	);

	return $query->have_posts() ? $query : null;
}


/* ==========================================================================
   6. Icons
   ========================================================================== */

/**
 * Inline SVG icons for the story templates.
 *
 * Falls through to botphonic_blog_icon() so the shared set (calendar, clock,
 * list, chevron-down, close, arrow-right, link, and the social marks) is
 * available under one call.
 *
 * @param string $name Icon key.
 * @return string SVG markup, or an empty string.
 */
function botphonic_story_icon($name)
{
	$open = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">';

	$paths = array(
		'trend' => '<path d="M3 17l6-6 4 4 7-7"/><path d="M14 8h6v6"/>',
		'target' => '<circle cx="12" cy="12" r="8.5"/><circle cx="12" cy="12" r="4"/><circle cx="12" cy="12" r="0.6" fill="currentColor"/>',
		'spark' => '<path d="M12 3l1.9 5.4L19.5 10l-5.6 1.6L12 17l-1.9-5.4L4.5 10l5.6-1.6z"/><path d="M18.5 16.5l.8 2.2 2.2.8-2.2.8-.8 2.2-.8-2.2-2.2-.8 2.2-.8z"/>',
		'check' => '<path d="M20 6L9 17l-5-5"/>',
		'quote' => '<path d="M9.5 6.5C6.5 8 5 10.5 5 14a3.5 3.5 0 1 0 3.5-3.5"/><path d="M19 6.5C16 8 14.5 10.5 14.5 14a3.5 3.5 0 1 0 3.5-3.5"/>',
		'building' => '<path d="M4 21V6a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v15"/><path d="M15 10h3a2 2 0 0 1 2 2v9"/><path d="M8 8h3M8 12h3M8 16h3M2 21h20"/>',
		'phone' => '<path d="M6.5 3.5h3l1.5 4-2 1.5a11 11 0 0 0 6 6l1.5-2 4 1.5v3a2 2 0 0 1-2 2A16 16 0 0 1 4.5 5.5a2 2 0 0 1 2-2z"/>',
	);

	if (isset($paths[$name])) {
		return $open . $paths[$name] . '</svg>';
	}

	return function_exists('botphonic_blog_icon') ? botphonic_blog_icon($name) : '';
}
