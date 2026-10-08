<?php

/**
 * Botphonic child theme — Alternatives (competitor comparison) helpers.
 *
 * The Alternatives templates are built on the blog design system rather than
 * beside it: assets/css/blog.css is enqueued as a dependency so the `--bpg-*`
 * tokens, the reset keyed on .bpg-single / .bpg-archive, and the shared
 * primitives (.bpg-shell, .bpg-eyebrow, .bpg-btn, .bpg-grid, .bpg-card,
 * .bpg-layout, .bpg-side-cta, .bpg-endcta, .bpg-authorbox, .bpg-arc-hero,
 * .bpg-pagination-wrap, .bpg-empty, .bpg-band) are all reused as-is.
 * assets/css/alternatives.css only adds what is specific to a comparison page.
 *
 * assets/js/blog.js is deliberately *not* loaded here: its reading-progress
 * rail, share rail and clipboard behaviour all answer markup a comparison guide
 * does not print. The table of contents used to be part of that reasoning and is
 * not any more — template-parts/toc.php and assets/js/toc.js are shared with the
 * blog and the customer stories, and botphonic_alt_toc() below feeds the same
 * partial the other two views call.
 *
 * This file supplies four things:
 *
 *   1. view detection plus the body/html classes the assets key off,
 *   2. safe readers and renderers for the ACF fields the templates print,
 *   3. one resolved description of a comparison page (botphonic_alt_data()),
 *      so the table of contents and the sections it links to are built from
 *      the same array and can never drift apart,
 *   4. the generated blocks a profile's prose positions with a token.
 *
 * Nothing here assumes ACF is active: every reader degrades to an empty value
 * and the templates skip whatever is empty.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

if (!defined('BOTPHONIC_ALT_STYLE')) {
	define('BOTPHONIC_ALT_STYLE', 'botphonic-alternatives');
}


/* ==========================================================================
   1. View detection
   ========================================================================== */

/**
 * Post type slug, resilient to the constant not being defined yet.
 *
 * @return string
 */
function botphonic_alt_post_type()
{
	return defined('BOTPHONIC_ALT_SLUG') ? BOTPHONIC_ALT_SLUG : 'alternatives';
}

/**
 * @return bool
 */
function botphonic_is_alt_single_view()
{
	return is_singular(botphonic_alt_post_type());
}

/**
 * @return bool
 */
function botphonic_is_alt_archive_view()
{
	return is_post_type_archive(botphonic_alt_post_type());
}

/**
 * Either of the Alternatives views.
 *
 * @return bool
 */
function botphonic_is_alt_view()
{
	return botphonic_is_alt_single_view() || botphonic_is_alt_archive_view();
}

/**
 * `bpg-alt` is the single hook the Alternatives stylesheet scopes itself to, so
 * none of its rules can reach a post, a customer story or a landing page.
 *
 * Note the absence of `bpg-blog`: that class exists for the blog's own navbar
 * treatment in blog.css § 3.1 and for assets/js/blog.js, neither of which
 * applies here.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function botphonic_alt_body_class($classes)
{
	if (botphonic_is_alt_view()) {
		$classes[] = 'bpg-alt';
	}

	return $classes;
}
add_filter('body_class', 'botphonic_alt_body_class');

/**
 * Print the comparison copy with the punctuation it was written with.
 *
 * ACF mimics `the_content` on every wysiwyg value, and that chain includes
 * WordPress's wptexturize (secure-custom-fields/includes/fields/
 * class-acf-field-wysiwyg.php, where it is added to `acf_the_content`). On a
 * comparison guide that is actively unhelpful: " - " becomes an en dash and
 * straight quotes become curly, so a reviewer's words no longer match the
 * review being cited, and a hyphen in a plan name or a price range is silently
 * redrawn.
 *
 * These pages are transcribed from vendor documentation and published reviews,
 * where the characters are the point. Removed only on this post type and only
 * from ACF's chain, so the blog, the customer stories and every other editor on
 * the site keep WordPress's smart punctuation.
 *
 * Delete this function to get texturized typography back.
 *
 * @return void
 */
function botphonic_alt_literal_punctuation()
{
	if (!botphonic_is_alt_view()) {
		return;
	}

	remove_filter('acf_the_content', 'wptexturize');
}
add_action('wp', 'botphonic_alt_literal_punctuation');

/**
 * Mirrors botphonic_blog_html_class() / botphonic_story_html_class(). Only the
 * smooth-scroll opt-in hangs off it, which matters on a page whose table of
 * contents is nothing but in-page anchors.
 *
 * The three views are mutually exclusive, so the attribute is only ever
 * written once.
 *
 * @param string $output Already-built attribute string, e.g. `lang="en-US"`.
 * @return string
 */
function botphonic_alt_html_class($output)
{
	if (is_admin() || !botphonic_is_alt_view()) {
		return $output;
	}

	return trim($output . ' class="bpg-alt-html"');
}
add_filter('language_attributes', 'botphonic_alt_html_class');


/* ==========================================================================
   2. Brand + defaults
   ========================================================================== */

/**
 * The name of the "us" column in the comparison table and of the first profile.
 *
 * A function rather than a literal in six templates so a rebrand, or a
 * regional variant of the site, is one filter away.
 *
 * @return string
 */
function botphonic_alt_brand()
{
	return (string) apply_filters('botphonic_alt_brand', 'Botphonic');
}

/**
 * Fallback destination for every CTA button on an Alternatives page, used when
 * the post leaves the URL field empty.
 *
 * @return string
 */
function botphonic_alt_default_cta_url()
{
	return (string) apply_filters('botphonic_alt_default_cta_url', home_url('/contact/'));
}


/* ==========================================================================
   3. Field readers
   ========================================================================== */

/**
 * One ACF field, or an empty string when ACF is unavailable.
 *
 * @param string           $name Field name.
 * @param int|WP_Post|null $post Optional. Defaults to the current post.
 * @return mixed
 */
function botphonic_alt_field($name, $post = null)
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
 * @param string           $name     Field name.
 * @param int|WP_Post|null $post     Optional.
 * @param string           $fallback Optional. Returned when the field is empty.
 * @return string
 */
function botphonic_alt_text($name, $post = null, $fallback = '')
{
	$value = botphonic_alt_field($name, $post);
	$value = is_scalar($value) ? trim(wp_strip_all_tags((string) $value)) : '';

	return '' !== $value ? $value : (string) $fallback;
}

/**
 * A repeater field, always as a list.
 *
 * ACF returns false for an empty repeater and an associative array is
 * impossible here, so anything that is not a list becomes one.
 *
 * @param string           $name Field name.
 * @param int|WP_Post|null $post Optional.
 * @return array
 */
function botphonic_alt_rows($name, $post = null)
{
	$rows = botphonic_alt_field($name, $post);

	return is_array($rows) ? array_values($rows) : array();
}


/* ==========================================================================
   4. Rich text
   ========================================================================== */

/**
 * Render a WYSIWYG value as its own block of prose.
 *
 * ACF already runs wysiwyg values through wpautop, so that call is a no-op for
 * them. It matters for a value saved while the field was still a textarea:
 * those are stored as bare text with newlines, and this keeps them rendering
 * as paragraphs rather than one run-on line.
 *
 * @param string $raw Field value.
 * @return string Empty string when there is nothing to render.
 */
function botphonic_alt_prose($raw)
{
	$raw = (string) $raw;

	if ('' === trim($raw)) {
		return '';
	}

	return wp_kses_post(wpautop($raw));
}

/**
 * Render a WYSIWYG value that sits inside an element the template already
 * provides — a <p> in a review quote, an <li> in a bullet list.
 *
 * The wrapping paragraph is stripped because a <p> inside a <p> is invalid and
 * would drop the spacing the surrounding element was designed with. A second
 * paragraph becomes a pair of line breaks rather than a new block, for the same
 * reason. Fields rendered through this are given the locked-down "Inline"
 * toolbar in inc/acf-alternatives.php, so the only markup expected is emphasis
 * and links.
 *
 * @param string $raw Field value.
 * @return string
 */
function botphonic_alt_inline($raw)
{
	$html = botphonic_alt_prose($raw);

	if ('' === $html) {
		return '';
	}

	$html = preg_replace('~</p>\s*<p[^>]*>~i', '<br /><br />', $html);
	$html = preg_replace('~^\s*<p[^>]*>~i', '', $html);
	$html = preg_replace('~</p>\s*$~i', '', $html);

	return trim($html);
}


/* ==========================================================================
   5. Profile content tokens
   ========================================================================== */

/**
 * The generated blocks a profile's prose can position with a token.
 *
 * A profile write-up — description, subheadings, bullet lists, "Best for", the
 * closing verdict — is one wysiwyg field, so its structure and order live in
 * the editor where the writer can see them. The three things the editor cannot
 * hand-write are the screenshot (a media ID), the star rating (generated
 * markup) and the pros/cons grid (generated markup, icons and review boxes).
 * Those are dropped in with a token, so the writer still owns where they sit.
 *
 * Slug => token. Slugs are what botphonic_alt_profile_parts() hands back.
 *
 * @return array<string,string>
 */
function botphonic_alt_profile_tokens()
{
	return array(
		'screenshot' => '[alt-screenshot]',
		'rating' => '[alt-rating]',
		'pros_cons' => '[alt-pros-cons]',
	);
}

/**
 * Split a profile's rendered prose into an ordered list of renderable parts.
 *
 * Each part is one of:
 *   array('type' => 'html',  'value' => '<div class="bpg-alt-profile__…">…</div>')
 *   array('type' => 'block', 'value' => 'screenshot'|'rating'|'pros_cons')
 *
 * Two rules keep hand-written content forgiving:
 *
 *   1. wpautop wraps a token sitting on its own line in a <p>. That paragraph
 *      is unwrapped first, otherwise the generated block would land inside a
 *      <p> and lose the spacing its CSS was written for.
 *   2. A block whose token is missing still renders — screenshot above the
 *      prose, rating and pros/cons below it — so forgetting a token never
 *      silently drops content. A repeated token only renders the first time.
 *
 * The html parts have already been through wp_kses_post() via
 * botphonic_alt_prose(), so a caller may echo them directly.
 *
 * @param string $html Content already passed through botphonic_alt_prose().
 * @return array<int,array{type:string,value:string}>
 */
function botphonic_alt_profile_parts($html)
{
	$html = (string) $html;
	$tokens = botphonic_alt_profile_tokens();
	$slugs = array_flip($tokens);

	$alternation = implode(
		'|',
		array_map(
			static function ($token) {
				return preg_quote($token, '~');
			},
			$tokens
		)
	);

	$html = preg_replace('~<p>\s*(' . $alternation . ')\s*</p>~i', '$1', $html);

	// PREG_SPLIT_DELIM_CAPTURE puts the matched token at every odd index.
	$pieces = preg_split('~(' . $alternation . ')~i', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
	$pieces = is_array($pieces) ? $pieces : array();

	$parts = array();
	$used = array();

	foreach ($pieces as $index => $piece) {
		if (0 === $index % 2) {
			if ('' !== trim($piece)) {
				$parts[] = array('type' => 'html', 'value' => $piece);
			}
			continue;
		}

		$slug = isset($slugs[strtolower($piece)]) ? $slugs[strtolower($piece)] : '';

		if ('' === $slug || in_array($slug, $used, true)) {
			continue;
		}

		$used[] = $slug;
		$parts[] = array('type' => 'block', 'value' => $slug);
	}

	// The screenshot leads when it was not placed; everything else falls in
	// after the prose.
	if (!in_array('screenshot', $used, true)) {
		array_unshift($parts, array('type' => 'block', 'value' => 'screenshot'));
		$used[] = 'screenshot';
	}

	foreach (array_keys($tokens) as $slug) {
		if (!in_array($slug, $used, true)) {
			$parts[] = array('type' => 'block', 'value' => $slug);
		}
	}

	return $parts;
}


/* ==========================================================================
   6. Comparison matrix + ratings
   ========================================================================== */

/**
 * A tick or a cross for the feature matrix.
 *
 * Both marks are drawn in `currentColor` — the disc as a low-opacity fill, the
 * glyph as the stroke — so the yes/no palette lives in alternatives.css § 5
 * next to the rest of the table, instead of being hard-coded in PHP where a
 * theme colour change would not reach it.
 *
 * @param string $type 'yes' or 'no'.
 * @return string SVG markup, or an empty string.
 */
function botphonic_alt_mark($type)
{
	$open = '<svg class="bpg-alt-mark bpg-alt-mark--' . ('yes' === $type ? 'yes' : 'no') . '" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">';
	$disc = '<circle cx="12" cy="12" r="11" fill="currentColor" fill-opacity="0.13"/>';
	$stroke = ' fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>';

	if ('yes' === $type) {
		return $open . $disc . '<path d="M7 12.5l3 3 7-7"' . $stroke . '</svg>';
	}

	if ('no' === $type) {
		return $open . $disc . '<path d="M8.5 8.5l7 7M15.5 8.5l-7 7"' . $stroke . '</svg>';
	}

	return '';
}

/**
 * Whether a cell value means "the vendor has not published this".
 *
 * Source documents phrase it several ways and all of them mean the same thing:
 * we looked, and the vendor does not say. That is not the same claim as "this
 * feature is missing", and rendering it as a dash or as plain text would blur
 * the two. The neutral pill keeps the distinction visible, which matters on a
 * page that names competitors.
 *
 * "not documented" is included because it is the phrasing the comparison legend
 * defines: "Not published by the vendor at the time of writing. This is not a
 * finding that the capability is missing."
 *
 * @param string $raw Cell value.
 * @return bool
 */
function botphonic_alt_is_unverified($raw)
{
	$needle = strtolower(trim((string) $raw));
	$needle = rtrim($needle, " \t\n\r\0\x0B.");

	return in_array(
		$needle,
		array(
			'not documented',
			'not published',
			'not publicly available',
			'not disclosed',
			'verify with provider',
			'verify with vendor',
			'contact vendor',
			'contact sales',
		),
		true
	);
}

/**
 * The neutral pill for a value the vendor has not published.
 *
 * The label is "Not documented" because that is the term the comparison legend
 * defines, and a badge that names itself differently to the legend explaining it
 * is worse than no legend at all. The tooltip repeats the legend's own wording
 * for the same reason.
 *
 * @return string
 */
function botphonic_alt_unverified_tag()
{
	return '<span class="bpg-alt-cell__unverified" title="'
		. esc_attr__('Not published by the vendor at the time of writing. This is not a finding that the capability is missing.', 'botphonic')
		. '">' . esc_html__('Not documented', 'botphonic') . '</span>';
}

/**
 * One feature-matrix cell.
 *
 * Accepts four shapes so a writer never has to think about markup:
 *   ""            → an em dash
 *   "yes" / "no"  → a tick or a cross
 *   "yes: 50+"    → the mark plus a caption underneath
 *   anything else → plain text
 *
 * @param string $raw Cell value.
 * @return string
 */
function botphonic_alt_cell($raw)
{
	$raw = trim((string) $raw);

	if ('' === $raw) {
		return '<span class="bpg-alt-cell__dash" aria-hidden="true">&ndash;</span>';
	}

	if (botphonic_alt_is_unverified($raw)) {
		return botphonic_alt_unverified_tag();
	}

	if (preg_match('/^(yes|no)\s*:\s*(.+)$/i', $raw, $matches)) {
		return botphonic_alt_mark(strtolower($matches[1]))
			. '<span class="bpg-alt-cell__caption">' . esc_html(trim($matches[2])) . '</span>';
	}

	if (0 === strcasecmp($raw, 'yes')) {
		return botphonic_alt_mark('yes');
	}

	if (0 === strcasecmp($raw, 'no')) {
		return botphonic_alt_mark('no');
	}

	return '<span class="bpg-alt-cell__text">' . esc_html($raw) . '</span>';
}

/**
 * A CSS-only star rating, halves included.
 *
 * role="img" with an aria-label rather than the glyphs alone, so a screen
 * reader hears "4.5 out of 5" instead of ten star characters.
 *
 * @param float $value Rating, clamped to 0–5.
 * @return string
 */
function botphonic_alt_stars($value)
{
	$value = max(0, min(5, (float) $value));
	$percent = ($value / 5) * 100;

	return '<span class="bpg-alt-stars" role="img" aria-label="'
		. esc_attr(sprintf(/* translators: %s: rating out of five. */ __('%s out of 5', 'botphonic'), $value))
		. '">'
		. '<span class="bpg-alt-stars__track" aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733;</span>'
		. '<span class="bpg-alt-stars__fill" aria-hidden="true" style="width:' . esc_attr($percent) . '%">&#9733;&#9733;&#9733;&#9733;&#9733;</span>'
		. '</span>';
}

/**
 * One at-a-glance row value: a star rating, a yes/no mark, or plain text,
 * depending on how the row was configured.
 *
 * @param string $raw  Cell value.
 * @param string $type 'rating', 'yesno' or 'text'.
 * @return string
 */
function botphonic_alt_glance_value($raw, $type)
{
	$raw = trim((string) $raw);

	if ('rating' === $type) {
		return '' === $raw
			? '<span class="bpg-alt-cell__dash" aria-hidden="true">&ndash;</span>'
			: botphonic_alt_stars((float) $raw);
	}

	if ('yesno' === $type) {
		return botphonic_alt_cell($raw);
	}

	if ('' === $raw) {
		return '<span class="bpg-alt-cell__dash" aria-hidden="true">&ndash;</span>';
	}

	if (botphonic_alt_is_unverified($raw)) {
		return botphonic_alt_unverified_tag();
	}

	return '<span class="bpg-alt-cell__text">' . esc_html($raw) . '</span>';
}

/**
 * The logo + name block in a comparison table header cell.
 *
 * The brand column falls back to the site's custom logo when no image is set,
 * so the table still reads correctly on a fresh install with nothing uploaded.
 *
 * @param string $name     Column label.
 * @param int    $logo_id  Optional. Attachment ID.
 * @param bool   $is_brand Optional. Whether this is the Botphonic column.
 * @return string
 */
function botphonic_alt_column_head($name, $logo_id = 0, $is_brand = false)
{
	$name = trim((string) $name);
	$logo_id = (int) $logo_id;

	if (!$logo_id && $is_brand) {
		$logo_id = (int) get_theme_mod('custom_logo');
	}

	$out = '<span class="bpg-alt-col' . ($is_brand ? ' bpg-alt-col--brand' : '') . '">';

	if ($logo_id) {
		$out .= '<span class="bpg-alt-col__logo">'
			. wp_get_attachment_image(
				$logo_id,
				'medium',
				false,
				array(
					'alt' => $name,
					'loading' => 'lazy',
					'decoding' => 'async',
				)
			)
			. '</span>';
	}

	$out .= '<span class="bpg-alt-col__name">' . esc_html($name) . '</span>';
	$out .= '</span>';

	return $out;
}

/**
 * Column geometry for the feature matrix.
 *
 * The grouped feature rows are CSS grid rather than table rows (they live
 * inside a single colspan'd cell so a whole category can collapse), so they
 * need the same column widths the <colgroup> gives the summary rows. Computing
 * both from one place is what keeps the two halves of the table aligned.
 *
 * @param int $competitor_count Competitors excluding Botphonic.
 * @return array{products:int,total:int,label_pct:float,product_pct:float,template:string}
 */
function botphonic_alt_matrix_columns($competitor_count)
{
	$products = 1 + max(0, (int) $competitor_count); // Botphonic + competitors
	$label_pct = 22;
	$product_pct = round((100 - $label_pct) / $products, 4);

	return array(
		'products' => $products,
		'total' => $products + 1, // + the feature-name column
		'label_pct' => $label_pct,
		'product_pct' => $product_pct,
		'template' => $label_pct . '% ' . implode(' ', array_fill(0, $products, $product_pct . '%')),
	);
}


/* ==========================================================================
   7. Reviews
   ========================================================================== */

/**
 * The verification line under a review quote, e.g. "Verified G2 review".
 *
 * The writer types it, because the source is not always a review site — an app
 * store, or Botphonic's own reviews page, needs its own wording. Left empty it
 * falls back to the rating source.
 *
 * No reviewer name means there is no third party to credit: source documents
 * sometimes put the writer's own observation in this box, and naming the rating
 * source there would attribute a quote it never carried.
 *
 * Returns plain text; escape at the point of output.
 *
 * @param array  $profile One row of the competitor_profiles repeater.
 * @param string $slot    'pros' or 'cons'.
 * @return string
 */
function botphonic_alt_review_label(array $profile, $slot)
{
	$label = trim((string) (isset($profile[$slot . '_review_label']) ? $profile[$slot . '_review_label'] : ''));

	if ('' !== $label) {
		return $label;
	}

	$author = trim((string) (isset($profile[$slot . '_review_author']) ? $profile[$slot . '_review_author'] : ''));

	if ('' === $author) {
		return __('Verified review', 'botphonic');
	}

	$source = trim((string) (isset($profile['rating_source']) ? $profile['rating_source'] : ''));
	$source = '' !== $source ? $source : 'G2';

	/* translators: %s: review source, e.g. G2. */
	return sprintf(__('Verified %s review', 'botphonic'), $source);
}


/* ==========================================================================
   8. Calls to action
   ========================================================================== */

/**
 * `target="_blank"` only when a link leaves the site, so in-page anchors and
 * internal pages keep normal same-tab behaviour.
 *
 * @param string $url Destination.
 * @return string Attribute string, with a leading space, or an empty string.
 */
function botphonic_alt_link_target($url)
{
	$host = wp_parse_url((string) $url, PHP_URL_HOST);
	$site = wp_parse_url(home_url(), PHP_URL_HOST);

	return ($host && $host !== $site) ? ' target="_blank" rel="noopener noreferrer"' : '';
}

/**
 * A highlighted CTA box.
 *
 * Shared by both CTAs on an Alternatives post — the inline one after the first
 * profile and the one near the end of the article — and by the archive. Returns
 * an empty string when there is no heading, so callers can echo it
 * unconditionally and an unfilled field simply prints nothing.
 *
 * The button reuses .bpg-btn / .bpg-btn--coral from blog.css rather than
 * inventing a third button style.
 *
 * @param array $args {
 *     @type string $heading  Required. Nothing renders without it.
 *     @type string $text     Optional supporting line. Treated as inline HTML.
 *     @type string $button   Optional. Defaults to "Book a free demo".
 *     @type string $url      Optional. Defaults to botphonic_alt_default_cta_url().
 *     @type string $modifier Optional extra class, e.g. 'bpg-alt-cta--inline'.
 * }
 * @return string
 */
function botphonic_alt_cta(array $args)
{
	$heading = trim((string) (isset($args['heading']) ? $args['heading'] : ''));

	if ('' === $heading) {
		return '';
	}

	$text = botphonic_alt_inline(isset($args['text']) ? $args['text'] : '');
	$button = trim((string) (isset($args['button']) ? $args['button'] : ''));
	$button = '' !== $button ? $button : __('Book a free demo', 'botphonic');
	$url = trim((string) (isset($args['url']) ? $args['url'] : ''));
	$url = '' !== $url ? $url : botphonic_alt_default_cta_url();
	$modifier = trim((string) (isset($args['modifier']) ? $args['modifier'] : ''));

	$out = '<div class="bpg-alt-cta' . ('' !== $modifier ? ' ' . esc_attr($modifier) : '') . '">';
	$out .= '<div class="bpg-alt-cta__box">';
	$out .= '<p class="bpg-alt-cta__title">' . esc_html($heading) . '</p>';

	if ('' !== $text) {
		$out .= '<p class="bpg-alt-cta__text">' . $text . '</p>';
	}

	$out .= '<a class="bpg-btn bpg-btn--coral" href="' . esc_url($url) . '"' . botphonic_alt_link_target($url) . '>'
		. esc_html($button)
		. botphonic_alt_icon('arrow-right')
		. '</a>';
	$out .= '</div></div>';

	return $out;
}


/* ==========================================================================
   9. Page data
   ========================================================================== */

/**
 * Everything a single Alternatives page renders, resolved once.
 *
 * Single source of truth for the page: the table of contents and the sections
 * it links to are both derived from this array, so the nav can never point at
 * an anchor the body did not print. Memoised per post because the archive card,
 * the desktop TOC and the mobile TOC all ask for it inside one request.
 *
 * @param int|WP_Post|null $post Optional. Defaults to the current post.
 * @return array
 */
function botphonic_alt_data($post = null)
{
	static $cache = array();

	$post = get_post($post);

	if (!$post instanceof WP_Post) {
		return array();
	}

	if (isset($cache[$post->ID])) {
		return $cache[$post->ID];
	}

	$data = array(
		// Hero
		'subject' => botphonic_alt_text('subject_name', $post),
		'cta_text' => botphonic_alt_text('hero_cta_text', $post, __('Book a free demo', 'botphonic')),
		'cta_url' => botphonic_alt_text('hero_cta_url', $post, botphonic_alt_default_cta_url()),
		'toc_levels' => botphonic_alt_text('toc_heading_levels', $post, 'h3'),

		// Opening + methodology
		'intro' => botphonic_alt_field('intro_content', $post),
		'why_title' => botphonic_alt_text('why_look_title', $post, __('Why look for an alternative', 'botphonic')),
		'why' => botphonic_alt_field('why_look_content', $post),
		'method_title' => botphonic_alt_text('methodology_title', $post),
		'method' => botphonic_alt_field('methodology_content', $post),

		// Comparison table
		'compare_title' => botphonic_alt_text('comparison_title', $post, __('Compare the options at a glance', 'botphonic')),
		'compare_intro' => botphonic_alt_field('comparison_intro', $post),
		'compare_note' => botphonic_alt_field('comparison_note', $post),
		'competitors' => botphonic_alt_rows('competitors', $post),
		'glance_rows' => botphonic_alt_rows('glance_rows', $post),
		'categories' => botphonic_alt_rows('comparison_categories', $post),
		'compare_cta' => botphonic_alt_text('comparison_cta_text', $post, __('Book a free demo', 'botphonic')),

		// Profiles
		'profiles_heading' => botphonic_alt_text('profiles_heading', $post),
		'profiles' => botphonic_alt_rows('competitor_profiles', $post),
		'inline_cta' => array(
			'heading' => botphonic_alt_text('inline_cta_heading', $post),
			'text' => botphonic_alt_field('inline_cta_text', $post),
			'button' => botphonic_alt_text('inline_cta_button_text', $post),
			'url' => botphonic_alt_text('inline_cta_url', $post),
			'modifier' => 'bpg-alt-cta--inline',
		),

		// Verdict, CTA, FAQs
		'choose_title' => botphonic_alt_text('how_to_choose_title', $post, __('How to choose the right alternative', 'botphonic')),
		'choose' => botphonic_alt_field('how_to_choose_content', $post),
		'verdict_title' => botphonic_alt_text('final_verdict_title', $post, __('Final verdict', 'botphonic')),
		'verdict' => botphonic_alt_field('final_verdict_content', $post),
		'mid_cta' => array(
			'heading' => botphonic_alt_text('mid_cta_heading', $post),
			'text' => botphonic_alt_field('mid_cta_text', $post),
			'button' => botphonic_alt_text('mid_cta_button_text', $post),
			'url' => botphonic_alt_text('mid_cta_url', $post),
		),
		'faqs' => botphonic_alt_rows('faqs', $post),
	);

	/*
	 * Drop rows that cannot render anything, here rather than in the template,
	 * so the table of contents below and the body are built from the same list.
	 * A repeater always has one empty row waiting to be filled, and leaving them
	 * in produced a numbered write-up with no heading and an FAQ with no
	 * question.
	 *
	 * `competitors` is deliberately NOT filtered. Every glance row and feature
	 * supplies its values positionally, in the Competitors order, so dropping a
	 * blank competitor from the middle of the list would silently shift every
	 * value one column to the left. An empty column is a visible mistake an
	 * editor can find and fix; misaligned data is not.
	 */
	$data['profiles'] = array_values(
		array_filter(
			$data['profiles'],
			static function ($profile) {
				return is_array($profile) && '' !== trim((string) (isset($profile['name']) ? $profile['name'] : ''));
			}
		)
	);

	$data['faqs'] = array_values(
		array_filter(
			$data['faqs'],
			static function ($faq) {
				return is_array($faq) && '' !== trim((string) (isset($faq['question']) ? $faq['question'] : ''));
			}
		)
	);

	$data['categories'] = array_values(
		array_filter(
			$data['categories'],
			static function ($category) {
				return is_array($category) && !empty($category['features']) && is_array($category['features']);
			}
		)
	);

	$data['has_compare'] = (bool) ($data['glance_rows'] || $data['categories']);
	$data['matrix'] = botphonic_alt_matrix_columns(count($data['competitors']));
	$data['toc'] = botphonic_alt_toc($data);

	$cache[$post->ID] = $data;

	return $data;
}

/**
 * Table of contents for a comparison page, derived from botphonic_alt_data().
 *
 * `level` mirrors the heading level the entry points at: 2 for a page section,
 * 3 for a numbered profile, 0 for the overview link, which is always shown
 * because it is how a reader gets back to the top. The "Sidebar TOC Headings"
 * field then filters the list, so a post with twelve profiles can show only
 * those and a post with three can show the page structure instead.
 *
 * Kept separate from botphonic_alt_data() so both stay readable, but only ever
 * called from it.
 *
 * @param array $data Resolved page data.
 * @return array<int,array{id:string,label:string,level:int}>
 */
function botphonic_alt_toc(array $data)
{
	$items = array(
		array('id' => 'bpg-alt-overview', 'label' => __('Overview', 'botphonic'), 'level' => 0),
	);

	if (!empty($data['why'])) {
		$items[] = array('id' => 'bpg-alt-why', 'label' => $data['why_title'], 'level' => 2);
	}

	if (!empty($data['method'])) {
		$items[] = array(
			'id' => 'bpg-alt-method',
			'label' => '' !== $data['method_title'] ? $data['method_title'] : __('How we analyse', 'botphonic'),
			'level' => 2,
		);
	}

	if (!empty($data['has_compare'])) {
		$items[] = array('id' => 'bpg-alt-compare', 'label' => __('Compare at a glance', 'botphonic'), 'level' => 2);
	}

	if (!empty($data['profiles'])) {
		// Only when the post supplies a heading, so an H2-level table of
		// contents keeps matching the H2s actually on the page.
		if ('' !== $data['profiles_heading']) {
			$items[] = array('id' => 'bpg-alt-profiles', 'label' => $data['profiles_heading'], 'level' => 2);
		}

		foreach ($data['profiles'] as $index => $profile) {
			$name = trim((string) (isset($profile['name']) ? $profile['name'] : ''));

			// botphonic_alt_data() has already dropped nameless rows; this only
			// guards a caller that builds $data by hand, and keeps the indices
			// here identical to the ones the template prints as anchors.
			if ('' === $name) {
				continue;
			}

			$items[] = array(
				'id' => 'bpg-alt-profile-' . (int) $index,
				'label' => ($index + 1) . '. ' . $name,
				'level' => 3,
			);
		}
	}

	if (!empty($data['choose'])) {
		$items[] = array('id' => 'bpg-alt-choose', 'label' => $data['choose_title'], 'level' => 2);
	}

	if (!empty($data['verdict'])) {
		$items[] = array('id' => 'bpg-alt-verdict', 'label' => $data['verdict_title'], 'level' => 2);
	}

	if (!empty($data['faqs'])) {
		$items[] = array('id' => 'bpg-alt-faq', 'label' => __('FAQs', 'botphonic'), 'level' => 2);
	}

	$levels = isset($data['toc_levels']) ? $data['toc_levels'] : 'h3';

	if ('both' !== $levels) {
		$wanted = ('h2' === $levels) ? 2 : 3;

		$items = array_values(
			array_filter(
				$items,
				static function ($item) use ($wanted) {
					return 0 === $item['level'] || $wanted === $item['level'];
				}
			)
		);
	}

	return $items;
}

/**
 * The card label on the archive, e.g. "6 alternatives to Twilio".
 *
 * Built from the subject name and the number of profiles the post actually
 * covers, so the listing says something concrete without the writer having to
 * keep a second field in sync.
 *
 * @param int|WP_Post|null $post Optional.
 * @return string Empty string when the post has no subject name.
 */
function botphonic_alt_card_label($post = null)
{
	$data = botphonic_alt_data($post);

	if (empty($data['subject'])) {
		return '';
	}

	$count = count($data['profiles']);

	if ($count > 1) {
		return sprintf(
			/* translators: 1: number of platforms covered, 2: the platform being compared against. */
			_n('%1$s alternative to %2$s', '%1$s alternatives to %2$s', $count, 'botphonic'),
			number_format_i18n($count),
			$data['subject']
		);
	}

	/* translators: %s: the platform being compared against. */
	return sprintf(__('Alternative to %s', 'botphonic'), $data['subject']);
}


/* ==========================================================================
   10. Icons
   ========================================================================== */

/**
 * Inline SVG icons for the Alternatives templates.
 *
 * Falls through to botphonic_blog_icon() so the shared set (calendar, clock,
 * list, chevron-down, close, arrow-right, link and the social marks) is
 * reachable through one call.
 *
 * @param string $name Icon key.
 * @return string SVG markup, or an empty string.
 */
function botphonic_alt_icon($name)
{
	$open = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">';

	$paths = array(
		'scale' => '<path d="M12 3v18M7 7h10"/><path d="M4 12l3-5 3 5a3 3 0 0 1-6 0zM14 12l3-5 3 5a3 3 0 0 1-6 0z"/>',
		'check-circle' => '<circle cx="12" cy="12" r="9"/><path d="M8.5 12.5l2.5 2.5 4.5-5"/>',
		'plus-circle' => '<circle cx="12" cy="12" r="9"/><path d="M12 8.5v7M8.5 12h7"/>',
		'minus-circle' => '<circle cx="12" cy="12" r="9"/><path d="M8.5 12h7"/>',
		'quote' => '<path d="M9.5 6.5C6.5 8 5 10.5 5 14a3.5 3.5 0 1 0 3.5-3.5"/><path d="M19 6.5C16 8 14.5 10.5 14.5 14a3.5 3.5 0 1 0 3.5-3.5"/>',
		'shield' => '<path d="M12 3l7.5 3v5.5c0 4.6-3.1 7.9-7.5 9.5-4.4-1.6-7.5-4.9-7.5-9.5V6z"/><path d="M9 12l2 2 4-4.5"/>',
		'star' => '<path d="M12 3.6l2.6 5.3 5.9.85-4.25 4.15 1 5.9L12 17l-5.25 2.8 1-5.9L3.5 9.75l5.9-.85z"/>',
	);

	if (isset($paths[$name])) {
		return $open . $paths[$name] . '</svg>';
	}

	return function_exists('botphonic_blog_icon') ? botphonic_blog_icon($name) : '';
}
