<?php

defined('ABSPATH') || exit;

if (!defined('BOTPHONIC_HUB_STYLE')) {
	define('BOTPHONIC_HUB_STYLE', 'botphonic-hub-pages');
}

function botphonic_hub_templates()
{
	return array(
		'product' => array('file' => 'page-product.php', 'slug' => 'product'),
		'solution' => array('file' => 'page-solution.php', 'slug' => 'solution'),
		'industry' => array('file' => 'template-industries.php', 'assigned' => true),
	);
}

function botphonic_hub_active_key($set = null)
{
	static $key = '';

	if (null !== $set) {
		$key = sanitize_key((string) $set);
	}

	return $key;
}

function botphonic_hub_page_data($set = null)
{
	static $page = array();
	if (is_array($set)) {
		$page = $set;
	}

	return $page;
}

function botphonic_hub_boot($key, $page)
{
	$key = sanitize_key((string) $key);
	$hub = get_queried_object();

	$page = wp_parse_args(
		(array) $page,
		array(
			'label' => '',
			'noun' => array('page', 'pages'),
			'list_name' => '',
			'default_icon' => 'grid',
			'hero' => array(),
			'list' => array(),
			'groups' => array(),
			'items' => array(),
			'guide' => array(),
			'features' => array(),
			'related' => array(),
			'faq' => array(),
			'faqs' => array(),
			'cta' => array(),
			'register_url' => defined('BOTPHONIC_REGISTER_URL') ? BOTPHONIC_REGISTER_URL : 'https://app.botphonic.ai/register/',
			'demo_url' => defined('BOTPHONIC_DEMO_URL') ? BOTPHONIC_DEMO_URL : 'https://calendly.com/contact-botphonic/product-discovery',
		)
	);

	$page['key'] = $key;
	$page['hub_id'] = $hub instanceof WP_Post ? (int) $hub->ID : 0;
	$page['noun'] = array_values((array) $page['noun']) + array('page', 'pages');
	$page['cards'] = botphonic_hub_build_cards($page['hub_id'], $page);
	$page['index'] = botphonic_hub_card_index($page['cards']);
	$page['guide_rows'] = botphonic_hub_guide_rows($page['guide'], $page['index']);

	if (!empty($page['hero']['lede'])) {
		$page['hero']['lede'] = str_replace('{count}', number_format_i18n(count($page['cards'])), (string) $page['hero']['lede']);
	}

	botphonic_hub_active_key($key);
	botphonic_hub_page_data($page);

	return $page;
}

function botphonic_hub_build_cards($parent_id, $page)
{
	$parent_id = absint($parent_id);
	if (!$parent_id) {
		return array();
	}

	$items = is_array($page['items']) ? $page['items'] : array();
	$groups = is_array($page['groups']) ? $page['groups'] : array();
	$children = get_pages(
		array(
			'parent' => $parent_id,
			'hierarchical' => false,
			'post_status' => 'publish',
			'sort_column' => 'menu_order,post_title',
		)
	);

	if (!$children) {
		return array();
	}

	update_meta_cache('post', wp_list_pluck($children, 'ID'));
	$thumbnails = array_filter(array_map('get_post_thumbnail_id', $children));
	if ($thumbnails && function_exists('_prime_post_caches')) {
		_prime_post_caches(array_map('intval', $thumbnails), false, true);
	}

	$order = array_flip(array_keys($items));
	usort(
		$children,
		static function ($a, $b) use ($order) {
			$ia = isset($order[$a->post_name]) ? $order[$a->post_name] : PHP_INT_MAX;
			$ib = isset($order[$b->post_name]) ? $order[$b->post_name] : PHP_INT_MAX;

			if ($ia !== $ib) {
				return $ia <=> $ib;
			}

			return ($a->menu_order <=> $b->menu_order) ?: strcasecmp($a->post_title, $b->post_title);
		}
	);

	$cards = array();
	foreach ($children as $child) {
		if ('' !== (string) $child->post_password) {
			continue;
		}

		$item = isset($items[$child->post_name]) ? (array) $items[$child->post_name] : array();

		$line = !empty($item['line']) ? (string) $item['line'] : botphonic_hub_page_description($child);

		$group = !empty($item['group']) && isset($groups[$item['group']]) ? (string) $item['group'] : '';
		$title = !empty($item['label'])
			? (string) $item['label']
			: html_entity_decode(wp_strip_all_tags(get_the_title($child)), ENT_QUOTES | ENT_HTML5, 'UTF-8');

		$cards[] = array(
			'id' => (int) $child->ID,
			'slug' => (string) $child->post_name,
			'url' => (string) get_permalink($child),
			'title' => $title,
			'group' => $group,
			'group_label' => '' !== $group ? (string) $groups[$group] : '',
			'icon' => !empty($item['icon']) ? (string) $item['icon'] : (string) $page['default_icon'],
			'line' => $line,
			'tasks' => !empty($item['tasks']) ? array_values(array_filter(array_map('strval', (array) $item['tasks']))) : array(),
		);
	}

	return $cards;
}

function botphonic_hub_page_description($post)
{
	$text = trim((string) get_post_meta($post->ID, '_yoast_wpseo_metadesc', true));
	if ('' !== $text && function_exists('wpseo_replace_vars')) {
		$text = trim(wp_strip_all_tags((string) wpseo_replace_vars($text, $post)));
	}
	if ('' === $text && has_excerpt($post)) {
		$text = wp_trim_words(wp_strip_all_tags(get_the_excerpt($post)), 22);
	}

	return $text;
}

function botphonic_hub_card_index($cards)
{
	$index = array();

	foreach ((array) $cards as $card) {
		$index[$card['slug']] = $card;
	}

	return $index;
}

function botphonic_hub_cell_html($cell, $index)
{
	if (!is_array($cell)) {
		return esc_html(trim((string) $cell));
	}

	$links = array();
	foreach ($cell as $ref) {
		if (is_array($ref)) {
			if (!empty($ref['url']) && !empty($ref['label'])) {
				$links[] = array((string) $ref['url'], (string) $ref['label']);
			}
		} elseif (isset($index[(string) $ref])) {
			$links[] = array($index[(string) $ref]['url'], $index[(string) $ref]['title']);
		}
	}

	if (!$links) {
		return '';
	}

	$html = '<ul class="bphub-links" role="list">';
	foreach ($links as $link) {
		$html .= '<li><a href="' . esc_url($link[0]) . '">' . esc_html($link[1]) . '</a></li>';
	}

	return $html . '</ul>';
}

function botphonic_hub_guide_rows($guide, $index)
{
	$rows = array();
	$none = '<span class="bphub-none"><span aria-hidden="true">&mdash;</span><span class="bphub-sr">None</span></span>';

	foreach ((array) ($guide['rows'] ?? array()) as $row) {
		$cells = array();
		$has_links = false;
		$resolved = false;

		foreach (array_values((array) $row) as $cell) {
			$html = botphonic_hub_cell_html($cell, $index);

			if (is_array($cell)) {
				$has_links = true;
				$resolved = $resolved || '' !== $html;
			}

			$cells[] = '' === $html ? $none : $html;
		}

		if ($cells && (!$has_links || $resolved)) {
			$rows[] = $cells;
		}
	}

	return $rows;
}

function botphonic_hub_icon($name, $size = 24)
{
	static $paths = array(
	'arrow' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
	'check' => '<path d="M5 12l5 5L20 7"/>',
	'grid' => '<rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>',
	'layers' => '<path d="m12 2 10 5-10 5L2 7z"/><path d="m2 17 10 5 10-5"/><path d="m2 12 10 5 10-5"/>',
	'building' => '<rect width="16" height="20" x="4" y="2" rx="2"/><path d="M9 22v-4h6v4M8 6h.01M16 6h.01M12 6h.01M12 10h.01M12 14h.01M16 10h.01M16 14h.01M8 10h.01M8 14h.01"/>',
	'plug' => '<path d="M12 22v-5"/><path d="M9 8V2"/><path d="M15 8V2"/><path d="M18 8v5a4 4 0 0 1-4 4h-4a4 4 0 0 1-4-4V8z"/>',
	'tag' => '<path d="M12.6 2.6A2 2 0 0 0 11.2 2H4a2 2 0 0 0-2 2v7.2a2 2 0 0 0 .6 1.4l8.7 8.7a2.4 2.4 0 0 0 3.4 0l6.6-6.6a2.4 2.4 0 0 0 0-3.4z"/><circle cx="7.5" cy="7.5" r="1.5"/>',
	'star' => '<path d="M11.5 2.3a.5.5 0 0 1 1 0l2.3 4.7a2 2 0 0 0 1.5 1.1l5.2.8a.5.5 0 0 1 .3.9l-3.8 3.7a2 2 0 0 0-.6 1.8l.9 5.2a.5.5 0 0 1-.8.6l-4.6-2.5a2 2 0 0 0-1.9 0L6.6 21a.5.5 0 0 1-.8-.6l.9-5.2a2 2 0 0 0-.6-1.8L2.3 9.8a.5.5 0 0 1 .3-.9l5.2-.8a2 2 0 0 0 1.5-1.1z"/>',
	'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>',
	'mic' => '<path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><path d="M12 19v3"/>',
	'file-text' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/>',
	'phone-call' => '<path d="M14 2a9 9 0 0 1 8 8"/><path d="M14 6a5 5 0 0 1 4 4"/><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"/>',
	'phone-incoming' => '<path d="M16 2v6h6"/><path d="m22 2-6 6"/><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"/>',
	'wand' => '<path d="M15 4V2"/><path d="M15 16v-2"/><path d="M8 9h2"/><path d="M20 9h2"/><path d="M17.8 11.8 19 13"/><path d="M15 9h.01"/><path d="M17.8 6.2 19 5"/><path d="m3 21 9-9"/><path d="M12.2 6.2 11 5"/>',
	'bot' => '<path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/>',
	'globe' => '<circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
	'flow' => '<rect width="8" height="8" x="3" y="3" rx="2"/><path d="M7 11v4a2 2 0 0 0 2 2h4"/><rect width="8" height="8" x="13" y="13" rx="2"/>',
	'message' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
	'smile' => '<circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><path d="M9 9h.01"/><path d="M15 9h.01"/>',
	'headphones' => '<path d="M3 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7a9 9 0 0 1 18 0v7a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3"/>',
	'calendar' => '<path d="M8 2v4"/><path d="M16 2v4"/><path d="M3 10h18"/><path d="M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/>',
	'calendar-check' => '<path d="M8 2v4"/><path d="M16 2v4"/><path d="M3 10h18"/><path d="M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><path d="m9 16 2 2 4-4"/>',
	'trending' => '<path d="m22 7-8.5 8.5-5-5L2 17"/><path d="M16 7h6v6"/>',
	'megaphone' => '<path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>',
	'list' => '<path d="M8 6h13"/><path d="M8 12h13"/><path d="M8 18h13"/><path d="M3 6h.01"/><path d="M3 12h.01"/><path d="M3 18h.01"/>',
	'bell' => '<path d="M3 20a1 1 0 0 1-1-1v-1a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v1a1 1 0 0 1-1 1z"/><path d="M20 16a8 8 0 1 0-16 0"/><path d="M12 4v4"/><path d="M10 4h4"/>',
	'zap' => '<path d="M13 2 3 14h9l-1 8 10-12h-9z"/>',
	'user-plus' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6"/><path d="M22 11h-6"/>',
	'send' => '<path d="m22 2-7 20-4-9-9-4z"/><path d="M22 2 11 13"/>',
	'sparkles' => '<path d="m12 3-1.9 5.8a2 2 0 0 1-1.3 1.3L3 12l5.8 1.9a2 2 0 0 1 1.3 1.3L12 21l1.9-5.8a2 2 0 0 1 1.3-1.3L21 12l-5.8-1.9a2 2 0 0 1-1.3-1.3z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/>',
	'repeat' => '<path d="m17 2 4 4-4 4"/><path d="M3 11v-1a4 4 0 0 1 4-4h14"/><path d="m7 22-4-4 4-4"/><path d="M21 13v1a4 4 0 0 1-4 4H3"/>',
	'inbox' => '<path d="M22 12h-6l-2 3h-4l-2-3H2"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>',
	'server' => '<rect width="20" height="8" x="2" y="2" rx="2"/><rect width="20" height="8" x="2" y="14" rx="2"/><path d="M6 6h.01"/><path d="M6 18h.01"/>',
	'flame' => '<path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.07-2.14-.22-4.05 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.15.43-2.29 1-3a2.5 2.5 0 0 0 2.5 2.5z"/>',
	'mail-check' => '<path d="M22 13V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h8"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/><path d="m16 19 2 2 4-4"/>',
	'eye' => '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>',
	'user-search' => '<circle cx="10" cy="7" r="4"/><path d="M10.3 15H7a4 4 0 0 0-4 4v2"/><circle cx="17" cy="17" r="3"/><path d="m21 21-1.9-1.9"/>',
	'heart' => '<path d="M19 14c1.5-1.5 3-3.2 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.8 0-3 .5-4.5 2-1.5-1.5-2.7-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4 3 5.5l7 7z"/><path d="M3.2 12h4.3l1.5-3 3 6 1.5-3h4.3"/>',
	'home' => '<path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/><path d="M9.5 21v-6h5v6"/>',
	'bank' => '<path d="M3 21h18"/><path d="M3 10h18"/><path d="m12 3 9 7H3z"/><path d="M6 10v8M10 10v8M14 10v8M18 10v8"/>',
	'headset' => '<path d="M3 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7a9 9 0 0 1 18 0v7a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3"/>',
	'wrench' => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.8-3.8a6 6 0 0 1-7.9 7.9l-6.9 6.9a2.1 2.1 0 0 1-3-3l6.9-6.9a6 6 0 0 1 7.9-7.9l-3.8 3.8z"/>',
	'car' => '<path d="M5 17h14v-5l-2-5H7l-2 5z"/><path d="M3 12h18"/><circle cx="7.5" cy="17" r="1.5"/><circle cx="16.5" cy="17" r="1.5"/>',
	'sun' => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
	'plane' => '<path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/>',
	'cap' => '<path d="M22 10 12 5 2 10l10 5 10-5z"/><path d="M6 12v5c3 2 9 2 12 0v-5"/>',
	'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
	'briefcase' => '<rect width="20" height="14" x="2" y="7" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
	'truck' => '<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.62L18.3 8.38A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/>',
	);

	$name = isset($paths[$name]) ? $name : 'grid';

	return sprintf(
		'<svg width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%2$s</svg>',
		(int) $size,
		$paths[$name]
	);
}

function botphonic_hub_body_class($classes)
{
	$key = botphonic_hub_active_key();

	if ('' !== $key) {
		$classes[] = 'bphub-page';
		$classes[] = 'bphub-page--' . sanitize_html_class($key);
	}

	return array_values(array_unique($classes));
}
add_filter('body_class', 'botphonic_hub_body_class');

function botphonic_hub_enqueue_assets()
{
	if ('' === botphonic_hub_active_key()) {
		return;
	}

	$dir = get_stylesheet_directory();
	$uri = get_stylesheet_directory_uri();
	$page = botphonic_hub_page_data();

	if (!empty($page['faqs'])) {
		if (wp_style_is('botphonic-faqs-css', 'registered')) {
			wp_enqueue_style('botphonic-faqs-css');
		}
		if (wp_script_is('botphonic-faqs-js', 'registered')) {
			wp_enqueue_script('botphonic-faqs-js');
		}
	}

	$css = '/assets/css/hub-pages.css';
	if (file_exists($dir . $css)) {
		wp_enqueue_style(BOTPHONIC_HUB_STYLE, $uri . $css, array('dtbwp_css_child'), (string) filemtime($dir . $css));
	}

	$js = '/assets/js/hub-pages.js';
	if (file_exists($dir . $js)) {
		wp_enqueue_script(BOTPHONIC_HUB_STYLE, $uri . $js, array(), (string) filemtime($dir . $js), array('in_footer' => true, 'strategy' => 'defer'));
	}
}
add_action('wp_enqueue_scripts', 'botphonic_hub_enqueue_assets', 30); // After FAQ registration

function botphonic_hub_key_for_post($post)
{
	$post = get_post($post);
	if (!$post instanceof WP_Post || 'page' !== $post->post_type) {
		return '';
	}

	$assigned = (string) get_page_template_slug($post);

	foreach (botphonic_hub_templates() as $key => $hub) {
		if (!empty($hub['assigned'])) {
			if ($assigned === $hub['file']) {
				return (string) $key;
			}
		} elseif ('' === $assigned && 0 === (int) $post->post_parent && $hub['slug'] === $post->post_name && '' !== locate_template($hub['file'])) {
			return (string) $key;
		}
	}

	return '';
}

function botphonic_hub_admin_notice()
{
	$screen = function_exists('get_current_screen') ? get_current_screen() : null;
	if (!$screen || 'post' !== $screen->base || 'page' !== $screen->post_type) {
		return;
	}

	$key = botphonic_hub_key_for_post(get_post());
	if ('' === $key) {
		return;
	}

	$templates = botphonic_hub_templates();

	printf(
		'<div class="notice notice-info"><p>%s</p></div>',
		esc_html(
			sprintf(
				__('Visitors see this hub through the %s theme template: its headline, cards, guide, FAQ and calls to action are edited there, and published child pages appear on it automatically. Anything you add in the editor below shows as an extra section above the FAQ. Yoast SEO settings on this screen still apply.', 'botphonic'),
				$templates[$key]['file']
			)
		)
	);
}
add_action('admin_notices', 'botphonic_hub_admin_notice');

function botphonic_hub_entity_facts()
{
	$facts = array(
		'product' => array(
			// No email pricing
			'ai-phone-call' => array('plan' => 'Starter'),
			'ai-agent-studio' => array('plan' => 'Starter', 'name' => 'AI Agent Studio'),
			'voice-agent-templates' => array('plan' => 'Starter'),
			'multilingual-voice-ai-agents' => array('plan' => 'Starter'),
			// Workflows need Pro
			'visual-workflow-builder' => array('plan' => 'Pro'),
		),
		'solution' => array(
			'sales-automation' => array('name' => 'AI Sales Automation'),
			'onboarding' => array('name' => 'AI Client Onboarding'),
		),
		'industry' => array(
			'logistic' => array('label' => 'Logistics'),
			'agency' => array('label' => 'Agencies'),
		),
	);

	return (array) apply_filters('botphonic_hub_entity_facts', $facts);
}

function botphonic_hub_plan_prices()
{
	static $prices = null;

	if (null !== $prices) {
		return $prices;
	}

	// Fallback prices
	$prices = array('Starter' => 12.0, 'Pro' => 36.0);
	// Pricing page cache
	$url = add_query_arg(array('billingCycle' => 'Yearly', 'currency' => 'USD'), 'https://api.botphonic.ai/api/plans');
	$plans = get_option('bp_pricing_' . md5($url) . '_last');

	if (is_array($plans)) {
		foreach ($plans as $plan) {
			if (is_array($plan) && !empty($plan['name']) && empty($plan['isWalletPlan']) && isset($plan['price']) && is_numeric($plan['price'])) {
				$prices[(string) $plan['name']] = (float) $plan['price'];
			}
		}
	}

	return $prices;
}

function botphonic_hub_plan_offer($plan)
{
	$prices = botphonic_hub_plan_prices();
	if (empty($prices[$plan]) || $prices[$plan] <= 0) {
		return array();
	}

	$price = number_format($prices[$plan], 2, '.', '');

	return array(
		'@type' => 'Offer',
		'name' => $plan . ' plan, billed yearly',
		'price' => $price,
		'priceCurrency' => 'USD',
		'priceSpecification' => array(
			'@type' => 'UnitPriceSpecification',
			'price' => $price,
			'priceCurrency' => 'USD',
			'billingIncrement' => 1,
			'unitText' => 'month',
			'billingDuration' => 12,
		),
		'availability' => 'https://schema.org/InStock',
		'url' => home_url('/pricing/'),
		'seller' => array('@id' => home_url('/#organization')),
	);
}

function botphonic_hub_entity($post, $hub_key)
{
	static $cache = array();

	$post = get_post($post);
	$hub_key = (string) $hub_key;
	if (!$post instanceof WP_Post || 'publish' !== $post->post_status || '' !== (string) $post->post_password) {
		return array();
	}

	$cache_key = $hub_key . ':' . $post->ID;
	if (isset($cache[$cache_key])) {
		return $cache[$cache_key];
	}

	$facts = botphonic_hub_entity_facts();
	$fact = isset($facts[$hub_key][$post->post_name]) ? (array) $facts[$hub_key][$post->post_name] : array();
	$url = (string) get_permalink($post);
	$title = html_entity_decode(wp_strip_all_tags(get_the_title($post)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
	$name = !empty($fact['name']) ? (string) $fact['name'] : $title;
	$description = html_entity_decode(botphonic_hub_page_description($post), ENT_QUOTES | ENT_HTML5, 'UTF-8');
	$image = has_post_thumbnail($post) ? (string) wp_get_attachment_image_url(get_post_thumbnail_id($post), 'full') : '';
	$organization = array('@id' => home_url('/#organization'));
	$entity = array();

	if ('product' === $hub_key) {
		$offer = !empty($fact['plan']) ? botphonic_hub_plan_offer((string) $fact['plan']) : array();
		if ($offer) {
			$entity = array(
				'@type' => 'Product',
				'@id' => $url . '#product',
				'name' => $name,
				'description' => $description,
				'url' => $url,
				'image' => $image,
				'brand' => array('@type' => 'Brand', 'name' => 'Botphonic'),
				'category' => !empty($fact['category']) ? (string) $fact['category'] : 'AI voice agent software',
				'offers' => $offer,
			);
		}
	} elseif ('solution' === $hub_key) {
		$entity = array(
			'@type' => 'Service',
			'@id' => $url . '#service',
			'name' => $name,
			'serviceType' => $name,
			'description' => $description,
			'url' => $url,
			'image' => $image,
			'provider' => $organization,
		);
	} elseif ('industry' === $hub_key) {
		$label = !empty($fact['label']) ? (string) $fact['label'] : $title;
		$entity = array(
			'@type' => 'Service',
			'@id' => $url . '#service',
			'name' => !empty($fact['name']) ? (string) $fact['name'] : 'AI voice agents for ' . $label,
			'serviceType' => 'AI voice agent',
			'description' => $description,
			'url' => $url,
			'image' => $image,
			'provider' => $organization,
			'audience' => array('@type' => 'BusinessAudience', 'audienceType' => $label),
		);
	}

	if ($entity) {
		$entity['mainEntityOfPage'] = array('@id' => $url);
		$entity = array_filter(
			$entity,
			static function ($value) {
				return '' !== $value;
			}
		);
	}

	$cache[$cache_key] = $entity;

	return $entity;
}

function botphonic_hub_child_schema_graph($graph, $context = null)
{
	if (!is_array($graph) || !is_page() || '' !== botphonic_hub_active_key()) {
		return $graph;
	}

	$post = get_queried_object();
	if (!$post instanceof WP_Post || !$post->post_parent) {
		return $graph;
	}

	$hub_key = botphonic_hub_key_for_post($post->post_parent);
	$entity = '' !== $hub_key ? botphonic_hub_entity($post, $hub_key) : array();
	if (!$entity) {
		return $graph;
	}

	// Keep existing markup
	foreach ($graph as $node) {
		$types = isset($node['@type']) ? (array) $node['@type'] : array();
		if (($node['@id'] ?? '') === $entity['@id'] || array_intersect($types, array('Product', 'Service', 'SoftwareApplication'))) {
			return $graph;
		}
	}

	if (is_object($context) && !empty($context->main_schema_id)) {
		$entity['mainEntityOfPage'] = array('@id' => (string) $context->main_schema_id);
	}

	$graph[] = $entity;

	return $graph;
}
add_filter('wpseo_schema_graph', 'botphonic_hub_child_schema_graph', 20, 2);

function botphonic_hub_schema_faqs()
{
	if ('' === botphonic_hub_active_key()) {
		return array();
	}

	$page = botphonic_hub_page_data();
	$rows = array();

	foreach ((array) ($page['faqs'] ?? array()) as $row) {
		$question = isset($row['question']) ? trim(wp_strip_all_tags((string) $row['question'])) : '';
		$answer = isset($row['answer']) ? trim(preg_replace('/\s+/u', ' ', wp_strip_all_tags((string) $row['answer']))) : '';

		if ('' !== $question && '' !== $answer) {
			$rows[] = array(
				'question' => html_entity_decode($question, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
				'answer' => html_entity_decode($answer, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
			);
		}
	}

	return $rows;
}

function botphonic_hub_schema_webpage_type($type)
{
	if ('' === botphonic_hub_active_key()) {
		return $type;
	}

	$types = (array) $type;
	$add = array('CollectionPage');
	if (botphonic_hub_schema_faqs()) {
		$add[] = 'FAQPage';
	}

	foreach ($add as $extra) {
		if (!in_array($extra, $types, true)) {
			$types[] = $extra;
		}
	}

	return $types;
}
add_filter('wpseo_schema_webpage_type', 'botphonic_hub_schema_webpage_type');

function botphonic_hub_schema_webpage($data)
{
	if ('' === botphonic_hub_active_key() || !is_array($data)) {
		return $data;
	}

	$page = botphonic_hub_page_data();
	$base = isset($data['@id']) ? (string) $data['@id'] : trailingslashit((string) get_permalink());

	$main = array();
	if (!empty($page['cards'])) {
		$main[] = array('@id' => $base . '#hub-list');
	}
	foreach (array_keys(botphonic_hub_schema_faqs()) as $index) {
		$main[] = array('@id' => $base . '#faq-' . ($index + 1));
	}

	if ($main && empty($data['mainEntity'])) {
		$data['mainEntity'] = $main;
	}
	if (empty($data['about'])) {
		$data['about'] = array('@id' => home_url('/#organization'));
	}

	return $data;
}
add_filter('wpseo_schema_webpage', 'botphonic_hub_schema_webpage');

function botphonic_hub_schema_graph($graph, $context = null)
{
	if ('' === botphonic_hub_active_key() || !is_array($graph)) {
		return $graph;
	}

	$page = botphonic_hub_page_data();
	$faqs = botphonic_hub_schema_faqs();
	$cards = (array) ($page['cards'] ?? array());

	if (!$cards && !$faqs) {
		return $graph;
	}

	$base = is_object($context) && !empty($context->main_schema_id)
		? (string) $context->main_schema_id
		: trailingslashit((string) get_permalink());

	$present = array();
	foreach ($graph as $node) {
		if (!empty($node['@id'])) {
			$present[(string) $node['@id']] = true;
		}
	}

	if ($cards && empty($present[$base . '#hub-list'])) {
		$elements = array();
		foreach (array_values($cards) as $index => $card) {
			$element = array(
				'@type' => 'ListItem',
				'position' => $index + 1,
				'name' => $card['title'],
				'url' => esc_url_raw($card['url']),
			);

			$entity = botphonic_hub_entity($card['id'], $page['key']);
			if ($entity) {
				$element['item'] = $entity;
			}

			$elements[] = $element;
		}

		$graph[] = array(
			'@type' => 'ItemList',
			'@id' => $base . '#hub-list',
			'name' => '' !== (string) $page['list_name'] ? (string) $page['list_name'] : (string) $page['label'],
			'numberOfItems' => count($elements),
			'itemListElement' => $elements,
		);
	}

	if ($faqs && empty($present[$base . '#faq-1'])) {
		$language = get_bloginfo('language');

		foreach ($faqs as $index => $faq) {
			$graph[] = array(
				'@type' => 'Question',
				'@id' => $base . '#faq-' . ($index + 1),
				'position' => $index + 1,
				'name' => $faq['question'],
				'answerCount' => 1,
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text' => $faq['answer'],
					'inLanguage' => $language,
				),
				'inLanguage' => $language,
			);
		}
	}

	return $graph;
}
add_filter('wpseo_schema_graph', 'botphonic_hub_schema_graph', 20, 2);