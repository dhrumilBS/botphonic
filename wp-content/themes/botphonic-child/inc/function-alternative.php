<?php

defined('ABSPATH') || exit;

if (!defined('BOTPHONIC_ALT_STYLE')) {
	define('BOTPHONIC_ALT_STYLE', 'botphonic-alternatives');
}


function botphonic_alt_post_type()
{
	return defined('BOTPHONIC_ALT_SLUG') ? BOTPHONIC_ALT_SLUG : 'alternatives';
}

function botphonic_is_alt_single_view()
{
	return is_singular(botphonic_alt_post_type());
}

function botphonic_is_alt_archive_view()
{
	return is_post_type_archive(botphonic_alt_post_type());
}

function botphonic_is_alt_view()
{
	return botphonic_is_alt_single_view() || botphonic_is_alt_archive_view();
}

function botphonic_alt_body_class($classes)
{
	if (botphonic_is_alt_view()) {
		$classes[] = 'bpg-alt';
	}

	return $classes;
}
add_filter('body_class', 'botphonic_alt_body_class');

function botphonic_alt_literal_punctuation()
{
	if (!botphonic_is_alt_view()) {
		return;
	}

	remove_filter('acf_the_content', 'wptexturize');
}
add_action('wp', 'botphonic_alt_literal_punctuation');

function botphonic_alt_html_class($output)
{
	if (is_admin() || !botphonic_is_alt_view()) {
		return $output;
	}

	return trim($output . ' class="bpg-alt-html"');
}
add_filter('language_attributes', 'botphonic_alt_html_class');


function botphonic_alt_brand()
{
	return (string) apply_filters('botphonic_alt_brand', 'Botphonic');
}

function botphonic_alt_default_cta_url()
{
	return (string) apply_filters('botphonic_alt_default_cta_url', home_url('/contact/'));
}


function botphonic_alt_field($name, $post = null)
{
	if (!function_exists('get_field')) {
		return '';
	}

	$post = get_post($post);
	$value = get_field($name, $post instanceof WP_Post ? $post->ID : false);

	return (null === $value || false === $value) ? '' : $value;
}

function botphonic_alt_text($name, $post = null, $fallback = '')
{
	$value = botphonic_alt_field($name, $post);
	$value = is_scalar($value) ? trim(wp_strip_all_tags((string) $value)) : '';

	return '' !== $value ? $value : (string) $fallback;
}

function botphonic_alt_rows($name, $post = null)
{
	$rows = botphonic_alt_field($name, $post);

	return is_array($rows) ? array_values($rows) : array();
}


function botphonic_alt_prose($raw)
{
	$raw = (string) $raw;

	if ('' === trim($raw)) {
		return '';
	}

	return wp_kses_post(wpautop($raw));
}

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


function botphonic_alt_profile_tokens()
{
	return array(
		'screenshot' => '[alt-screenshot]',
		'rating' => '[alt-rating]',
		'pros_cons' => '[alt-pros-cons]',
	);
}

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

function botphonic_alt_unverified_tag()
{
	return '<span class="bpg-alt-cell__unverified" title="'
		. esc_attr__('Not published by the vendor at the time of writing. This is not a finding that the capability is missing.', 'botphonic')
		. '">' . esc_html__('Not documented', 'botphonic') . '</span>';
}

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

function botphonic_alt_stars($value)
{
	$value = max(0, min(5, (float) $value));
	$percent = ($value / 5) * 100;

	return '<span class="bpg-alt-stars" role="img" aria-label="'
		. esc_attr(sprintf(__('%s out of 5', 'botphonic'), $value))
		. '">'
		. '<span class="bpg-alt-stars__track" aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9733;</span>'
		. '<span class="bpg-alt-stars__fill" aria-hidden="true" style="width:' . esc_attr($percent) . '%">&#9733;&#9733;&#9733;&#9733;&#9733;</span>'
		. '</span>';
}

function botphonic_alt_parse_rating($raw)
{
	$raw = trim((string) $raw);

	if ('' === $raw) {
		return null;
	}

	if (preg_match('/^(\d+(?:[.,]\d+)?)\s*(?:\/\s*5|out\s+of\s+5|stars?)?$/iu', $raw, $m)) {
		$value = (float) str_replace(',', '.', $m[1]);

		return $value <= 5 ? $value : null;
	}

	$glyphs = preg_replace('/[\s\x{FE0F}]+/u', '', $raw);
	if ('' !== $glyphs && preg_match('/^[\x{2B50}\x{2605}\x{2606}]+$/u', $glyphs)) {
		return (float) min(5, preg_match_all('/[\x{2B50}\x{2605}]/u', $glyphs));
	}

	return null;
}

function botphonic_alt_glance_value($raw, $type)
{
	$raw = trim((string) $raw);

	if ('' === $raw) {
		return '<span class="bpg-alt-cell__dash" aria-hidden="true">&ndash;</span>';
	}

	if ('yesno' === $type) {
		return botphonic_alt_cell($raw);
	}

	$rating = botphonic_alt_parse_rating($raw);
	if (null !== $rating && ('rating' === $type || !preg_match('/\d/', $raw))) {
		return botphonic_alt_stars($rating);
	}

	if (botphonic_alt_is_unverified($raw)) {
		return botphonic_alt_unverified_tag();
	}

	return '<span class="bpg-alt-cell__text">' . esc_html($raw) . '</span>';
}

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

function botphonic_alt_matrix_columns($competitor_count)
{
	$products = 1 + max(0, (int) $competitor_count);
	$label_pct = 22;
	$product_pct = round((100 - $label_pct) / $products, 4);

	return array(
		'products' => $products,
		'total' => $products + 1,
		'label_pct' => $label_pct,
		'product_pct' => $product_pct,
		'template' => $label_pct . '% ' . implode(' ', array_fill(0, $products, $product_pct . '%')),
	);
}

/*
 * Which comparison a guide prints: 'classic' (the table in
 * single-alternatives.php) or 'vs' (template-parts/alt-compare-vs.php, the
 * interactive pick-your-rivals comparison). Every guide prints 'vs'; use the
 * filter to put a single guide back on 'classic'.
 */
function botphonic_alt_matrix_layout($post = null)
{
	$post = get_post($post);
	// $vs = $post instanceof WP_Post && in_array($post->post_name, array('rosie-ai'), true);

	return (string) apply_filters('botphonic_alt_matrix_layout', 'vs', $post);
}

/*
 * Every product the comparison has values for, brand first, as
 * array('name', 'logo', 'brand').
 *
 * A row can carry more values than there are named Competitors: a platform that
 * got a write-up but was never added to the Competitors list. The classic table
 * stops at the Competitors count and silently drops those values. Here the
 * extra product takes its name from the matching write-up instead — profile 0
 * is the brand, so competitor value i belongs to profile i + 1.
 */
function botphonic_alt_platforms(array $alt)
{
	$competitors = isset($alt['competitors']) ? array_values($alt['competitors']) : array();
	$profiles = isset($alt['profiles']) ? array_values($alt['profiles']) : array();

	$platforms = array(
		array(
			'name' => botphonic_alt_brand(),
			'logo' => (int) get_theme_mod('custom_logo'),
			'brand' => true,
		),
	);

	foreach ($competitors as $competitor) {
		$platforms[] = array(
			'name' => trim((string) (isset($competitor['name']) ? $competitor['name'] : '')),
			'logo' => (int) (isset($competitor['logo']) ? $competitor['logo'] : 0),
			'brand' => false,
		);
	}

	$rows = isset($alt['glance_rows']) ? $alt['glance_rows'] : array();
	foreach ((isset($alt['categories']) ? $alt['categories'] : array()) as $category) {
		$rows = array_merge($rows, array_values((array) $category['features']));
	}

	$values = 0;
	foreach ($rows as $row) {
		if (isset($row['competitor_values']) && is_array($row['competitor_values'])) {
			$values = max($values, count($row['competitor_values']));
		}
	}

	for ($i = count($competitors); $i < $values; $i++) {
		$name = isset($profiles[$i + 1]['name']) ? trim((string) $profiles[$i + 1]['name']) : '';

		$platforms[] = array(
			/* translators: %s: column number. */
			'name' => '' !== $name ? $name : sprintf(__('Platform %s', 'botphonic'), number_format_i18n($i + 2)),
			'logo' => 0,
			'brand' => false,
		);
	}

	return $platforms;
}


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

	return sprintf(__('Verified %s review', 'botphonic'), $source);
}


function botphonic_alt_link_target($url)
{
	$host = wp_parse_url((string) $url, PHP_URL_HOST);
	$site = wp_parse_url(home_url(), PHP_URL_HOST);

	return ($host && $host !== $site) ? ' target="_blank" rel="noopener noreferrer"' : '';
}

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
		'subject' => botphonic_alt_text('subject_name', $post),
		'cta_text' => botphonic_alt_text('hero_cta_text', $post, __('Book a free demo', 'botphonic')),
		'cta_url' => botphonic_alt_text('hero_cta_url', $post, botphonic_alt_default_cta_url()),
		'toc_levels' => botphonic_alt_text('toc_heading_levels', $post, 'h3'),

		'intro' => botphonic_alt_field('intro_content', $post),
		'why_title' => botphonic_alt_text('why_look_title', $post, __('Why look for an alternative', 'botphonic')),
		'why' => botphonic_alt_field('why_look_content', $post),
		'method_title' => botphonic_alt_text('methodology_title', $post),
		'method' => botphonic_alt_field('methodology_content', $post),

		'compare_title' => botphonic_alt_text('comparison_title', $post, __('Compare the options at a glance', 'botphonic')),
		'compare_intro' => botphonic_alt_field('comparison_intro', $post),
		'compare_note' => botphonic_alt_field('comparison_note', $post),
		'competitors' => botphonic_alt_rows('competitors', $post),
		'glance_rows' => botphonic_alt_rows('glance_rows', $post),
		'categories' => botphonic_alt_rows('comparison_categories', $post),
		'compare_cta' => botphonic_alt_text('comparison_cta_text', $post, __('Book a free demo', 'botphonic')),

		'profiles_heading' => botphonic_alt_text('profiles_heading', $post),
		'profiles' => botphonic_alt_rows('competitor_profiles', $post),
		'inline_cta' => array(
			'heading' => botphonic_alt_text('inline_cta_heading', $post),
			'text' => botphonic_alt_field('inline_cta_text', $post),
			'button' => botphonic_alt_text('inline_cta_button_text', $post),
			'url' => botphonic_alt_text('inline_cta_url', $post),
			'modifier' => 'bpg-alt-cta--inline',
		),

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
		if ('' !== $data['profiles_heading']) {
			$items[] = array('id' => 'bpg-alt-profiles', 'label' => $data['profiles_heading'], 'level' => 2);
		}

		foreach ($data['profiles'] as $index => $profile) {
			$name = trim((string) (isset($profile['name']) ? $profile['name'] : ''));

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

function botphonic_alt_card_label($post = null)
{
	$data = botphonic_alt_data($post);

	if (empty($data['subject'])) {
		return '';
	}

	$count = count($data['profiles']);

	if ($count > 1) {
		return sprintf(
			_n('%1$s alternative to %2$s', '%1$s alternatives to %2$s', $count, 'botphonic'),
			number_format_i18n($count),
			$data['subject']
		);
	}

	return sprintf(__('Alternative to %s', 'botphonic'), $data['subject']);
}


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
		'check' => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
		'plus' => '<path d="M12 5v14M5 12h14"/>',
	);

	if (isset($paths[$name])) {
		return $open . $paths[$name] . '</svg>';
	}

	return function_exists('botphonic_blog_icon') ? botphonic_blog_icon($name) : '';
}
