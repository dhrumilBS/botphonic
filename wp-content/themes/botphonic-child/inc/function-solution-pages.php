<?php
/**
 * Reusable Industries and Use Cases page templates.
 *
 * Existing pages are matched by their full root-level URI. Future pages can
 * opt in by selecting either named page template in the editor. Elementor
 * content is transformed only in memory to add semantic presentation hooks;
 * post content and _elementor_data are never changed.
 *
 * @package BotphonicChild
 */

defined('ABSPATH') || exit;

if (!defined('BOTPHONIC_SOLUTION_STYLE')) {
	define('BOTPHONIC_SOLUTION_STYLE', 'botphonic-solution-pages');
}

/**
 * Canonical solution page registry.
 *
 * IDs document the current production records for audits, while runtime
 * routing uses the full page URI so the templates remain portable and do not
 * accidentally match regional child pages with duplicate leaf slugs.
 *
 * @return array<string, array<string, array{label:string,id:int}>>
 */
function botphonic_solution_page_registry()
{
	$pages = array(
		'industry' => array(
			'healthcare' => array('label' => 'Healthcare', 'id' => 506),
			'real-estate' => array('label' => 'Real Estate', 'id' => 294),
			'finance' => array('label' => 'Financial Services', 'id' => 3086),
			'bpo-customer-service' => array('label' => 'BPO Customer Services', 'id' => 5299),
			'car-dealerships' => array('label' => 'Car Dealerships', 'id' => 1201),
			'recruitment' => array('label' => 'Recruitment', 'id' => 2577),
			'home-services' => array('label' => 'Home Services', 'id' => 5535),
			'travel-and-hospitality' => array('label' => 'Travel and Hospitality', 'id' => 5470),
			'education' => array('label' => 'Education', 'id' => 3345),
			'solar' => array('label' => 'Solar', 'id' => 1221),
			'insurance' => array('label' => 'Insurance', 'id' => 5373),
			'logistic' => array('label' => 'Logistics', 'id' => 5392),
			'agency' => array('label' => 'Agency', 'id' => 1240),
			'technology' => array('label' => 'Technology', 'id' => 20765),
		),
		'use-case' => array(
			'ai-concierge' => array('label' => 'AI Concierge', 'id' => 175),
			'ai-receptionist' => array('label' => 'AI Receptionist', 'id' => 1032),
			'ai-answering-service' => array('label' => 'AI Answering Service', 'id' => 1130),
			'ai-customer-service' => array('label' => 'AI Customer Service', 'id' => 2820),
			'ai-phone-call' => array('label' => 'AI Phone Call', 'id' => 4550),
			'ai-sales-assistant' => array('label' => 'AI Sales Assistant', 'id' => 5588),
			'ai-call-centre' => array('label' => 'AI Call Centre', 'id' => 5655),
			'ai-appointment-setter' => array('label' => 'AI Appointment Setter', 'id' => 13555),
			'ai-telemarketing' => array('label' => 'AI Telemarketing', 'id' => 13653),
			'ai-appointment-booking' => array('label' => 'AI Appointment Booking', 'id' => 6304),
			'ai-ivr' => array('label' => 'AI IVR', 'id' => 6382),
		),
	);

	/**
	 * Filters the canonical solution page registry.
	 *
	 * @param array $pages Industries and Use Cases grouped by family.
	 */
	return apply_filters('botphonic_solution_page_registry', $pages);
}

/**
 * Resolve a page to its solution family.
 *
 * @param int|WP_Post|null $post Page object or ID; queried page by default.
 * @return string `industry`, `use-case`, or an empty string.
 */
function botphonic_solution_family($post = null)
{
	if (null === $post) {
		$post = get_queried_object();
	} elseif (!$post instanceof WP_Post) {
		$post = get_post($post);
	}

	if (!$post instanceof WP_Post || 'page' !== $post->post_type) {
		return '';
	}

	// Resolved several times per request (routing, body class, assets and
	// each Elementor document), so memoize per page for the request.
	static $resolved = array();

	if (isset($resolved[$post->ID])) {
		return $resolved[$post->ID];
	}

	$template_family = array(
		'page-industry.php' => 'industry',
		'page-use-case.php' => 'use-case',
	);
	$template = (string) get_page_template_slug($post);

	if (isset($template_family[$template])) {
		$family = $template_family[$template];
	} else {
		$family = '';
		$page_uri = trim((string) get_page_uri($post), '/');

		foreach (botphonic_solution_page_registry() as $candidate => $pages) {
			if (isset($pages[$page_uri])) {
				$family = $candidate;
				break;
			}
		}
	}

	/**
	 * Filters the family resolved for a page.
	 *
	 * @param string  $family `industry`, `use-case`, or empty.
	 * @param WP_Post $post   Page being resolved.
	 */
	$family = (string) apply_filters('botphonic_solution_page_family', $family, $post);

	$resolved[$post->ID] = in_array($family, array('industry', 'use-case'), true) ? $family : '';

	return $resolved[$post->ID];
}

/**
 * Whether the current request is a reusable solution page.
 *
 * @param string $family Optional family constraint.
 * @return bool
 */
function botphonic_is_solution_page($family = '')
{
	if (is_admin() || !is_page()) {
		return false;
	}

	$current_family = botphonic_solution_family();

	return '' !== $current_family && ('' === $family || $family === $current_family);
}

/**
 * Route canonical pages to their reusable family template.
 *
 * Explicit template selection and legacy URI routing share the same files.
 * Priority 50 leaves room for special terminal templates (for example 410)
 * to override this later in the template_include chain.
 *
 * @param string $template Template selected by WordPress.
 * @return string
 */
function botphonic_solution_template_include($template)
{
	if (!botphonic_is_solution_page()) {
		return $template;
	}

	$filename = 'industry' === botphonic_solution_family() ? 'page-industry.php' : 'page-use-case.php';
	$solution_template = locate_template($filename);

	return '' !== $solution_template ? $solution_template : $template;
}
add_filter('template_include', 'botphonic_solution_template_include', 50);

/**
 * Add stable page-family hooks for header behavior and scoped CSS.
 *
 * @param string[] $classes Existing body classes.
 * @return string[]
 */
function botphonic_solution_body_class($classes)
{
	$family = botphonic_solution_family();

	if ($family) {
		$classes[] = 'bps-solution-page';
		$classes[] = 'bps-solution-page--' . $family;
	}

	return array_values(array_unique($classes));
}
add_filter('body_class', 'botphonic_solution_body_class');

/**
 * Build environment-aware links from the canonical registry.
 *
 * @param string $family Solution family.
 * @return array<string, string> Label-to-URL map.
 */
function botphonic_solution_sitemap_links($family)
{
	$registry = botphonic_solution_page_registry();
	$links = array();

	if (empty($registry[$family]) || !is_array($registry[$family])) {
		return $links;
	}

	foreach ($registry[$family] as $uri => $page) {
		$label = isset($page['label']) ? (string) $page['label'] : ucwords(str_replace('-', ' ', $uri));
		$links[$label] = home_url('/' . user_trailingslashit($uri));
	}

	return $links;
}

/**
 * Determine whether an Elementor node contains a non-empty H1 widget.
 *
 * @param array $node Elementor node.
 * @return bool
 */
function botphonic_solution_node_has_h1($node)
{
	if (!is_array($node)) {
		return false;
	}

	$settings = isset($node['settings']) && is_array($node['settings']) ? $node['settings'] : array();
	$title = isset($settings['title']) ? trim(wp_strip_all_tags((string) $settings['title'])) : '';

	if ('heading' === ($node['widgetType'] ?? '') && 'h1' === ($settings['header_size'] ?? '') && '' !== $title) {
		return true;
	}

	foreach ((array) ($node['elements'] ?? array()) as $child) {
		if (botphonic_solution_node_has_h1($child)) {
			return true;
		}
	}

	return false;
}

/**
 * Count non-empty H1 widgets in an Elementor branch.
 *
 * @param array $node Elementor node.
 * @return int
 */
function botphonic_solution_node_h1_count($node)
{
	if (!is_array($node)) {
		return 0;
	}

	$count = botphonic_solution_node_has_h1(array_merge($node, array('elements' => array()))) ? 1 : 0;

	foreach ((array) ($node['elements'] ?? array()) as $child) {
		$count += botphonic_solution_node_h1_count($child);
	}

	return $count;
}

/**
 * Determine whether an Elementor branch contains hero media.
 *
 * @param array $node Elementor node.
 * @return bool
 */
function botphonic_solution_node_has_media($node)
{
	if (!is_array($node)) {
		return false;
	}

	$widget_type = (string) ($node['widgetType'] ?? '');
	if ('image' === $widget_type || false !== strpos($widget_type, 'botphonic-audio')) {
		return true;
	}

	foreach ((array) ($node['elements'] ?? array()) as $child) {
		if (botphonic_solution_node_has_media($child)) {
			return true;
		}
	}

	return false;
}

/**
 * Append semantic classes to an Elementor node without replacing editor data.
 *
 * @param array    $node    Elementor node, by reference.
 * @param string[] $classes Classes to append.
 * @return void
 */
function botphonic_solution_add_node_classes(&$node, $classes)
{
	if (!isset($node['settings']) || !is_array($node['settings'])) {
		$node['settings'] = array();
	}

	// Elementor V3 uses `css_classes` for layout elements and
	// `_css_classes` for widgets. Both controls have an empty prefix_class,
	// so the resulting tokens are rendered unchanged on the wrapper.
	$setting_key = in_array(($node['elType'] ?? ''), array('container', 'section', 'column'), true)
		? 'css_classes'
		: '_css_classes';
	$existing = isset($node['settings'][$setting_key]) && is_string($node['settings'][$setting_key])
		? preg_split('/\s+/', trim($node['settings'][$setting_key]))
		: array();
	$existing = array_filter((array) $existing);

	foreach ((array) $classes as $class) {
		$class = sanitize_html_class($class);
		if ($class) {
			$existing[] = $class;
		}
	}

	$node['settings'][$setting_key] = implode(' ', array_values(array_unique($existing)));
}

/**
 * Add shared role classes to widgets in one hero branch.
 *
 * @param array  $node   Elementor node, by reference.
 * @param string $branch `content` or `media`.
 * @return void
 */
function botphonic_solution_mark_hero_branch(&$node, $branch)
{
	if (!is_array($node)) {
		return;
	}

	$widget_type = (string) ($node['widgetType'] ?? '');
	$settings = isset($node['settings']) && is_array($node['settings']) ? $node['settings'] : array();

	if ('content' === $branch) {
		if ('heading' === $widget_type) {
			$title = isset($settings['title']) ? trim(wp_strip_all_tags((string) $settings['title'])) : '';
			if ('' !== $title) {
				botphonic_solution_add_node_classes($node, array('h1' === ($settings['header_size'] ?? '') ? 'bps-hero__title' : 'bps-hero__eyebrow'));
			}
		} elseif ('text-editor' === $widget_type) {
			botphonic_solution_add_node_classes($node, array('bps-hero__copy'));
		} elseif ('shortcode' === $widget_type) {
			botphonic_solution_add_node_classes($node, array('bps-hero__actions'));
		} elseif ('button' === $widget_type) {
			botphonic_solution_add_node_classes($node, array('bps-hero__cta'));
		} elseif ('icon-list' === $widget_type) {
			botphonic_solution_add_node_classes($node, array('bps-hero__eyebrow'));
		} elseif ('icon-box' === $widget_type) {
			botphonic_solution_add_node_classes($node, array('bps-hero__proof-item'));
		}

		$direct_icon_boxes = 0;
		foreach ((array) ($node['elements'] ?? array()) as $child) {
			if ('icon-box' === ($child['widgetType'] ?? '')) {
				$direct_icon_boxes++;
			}
		}
		if ($direct_icon_boxes > 1) {
			botphonic_solution_add_node_classes($node, array('bps-hero__proof'));
		}
	} else {
		if ('image' === $widget_type) {
			botphonic_solution_add_node_classes($node, array('bps-hero__image'));
		} elseif (false !== strpos($widget_type, 'botphonic-audio')) {
			botphonic_solution_add_node_classes($node, array('bps-hero__audio'));
		} elseif (in_array($widget_type, array('heading', 'text-editor'), true)) {
			botphonic_solution_add_node_classes($node, array('bps-hero__media-label'));
		}
	}

	if (empty($node['elements']) || !is_array($node['elements'])) {
		return;
	}

	foreach ($node['elements'] as &$child) {
		botphonic_solution_mark_hero_branch($child, $branch);
	}
	unset($child);
}

/**
 * Add the shared hero structure to the first Elementor container in memory.
 *
 * Every canonical document has one non-empty H1 in its first top-level
 * container and none in the following container. If that invariant changes,
 * the transform fails closed and leaves Elementor's output untouched.
 *
 * @param array $data    Elementor builder data.
 * @param int   $post_id Elementor document ID.
 * @return array
 */
function botphonic_solution_elementor_content_data($data, $post_id)
{
	$post_id = absint($post_id);

	// An empty ID would make get_post() fall back to the global post.
	if (!$post_id || !is_array($data)) {
		return $data;
	}

	$family = botphonic_solution_family($post_id);

	if (!$family || empty($data[0]) || !is_array($data[0]) || 'container' !== ($data[0]['elType'] ?? '')) {
		return $data;
	}

	$hero =& $data[0];
	if (1 !== botphonic_solution_node_h1_count($hero) || (isset($data[1]) && botphonic_solution_node_has_h1($data[1]))) {
		return $data;
	}

	$content_indexes = array();
	foreach ((array) ($hero['elements'] ?? array()) as $index => $branch) {
		if (botphonic_solution_node_has_h1($branch)) {
			$content_indexes[] = $index;
		}
	}

	if (1 !== count($content_indexes)) {
		return $data;
	}

	$content_index = reset($content_indexes);
	$media_indexes = array();

	foreach ((array) ($hero['elements'] ?? array()) as $index => $branch) {
		if ($index !== $content_index && botphonic_solution_node_has_media($branch)) {
			$media_indexes[] = $index;
		}
	}

	botphonic_solution_add_node_classes(
		$hero,
		array(
			'bps-hero',
			'bps-hero--' . $family,
			$media_indexes ? 'bps-hero--has-media' : 'bps-hero--centered',
		)
	);

	botphonic_solution_add_node_classes($hero['elements'][$content_index], array('bps-hero__content'));
	botphonic_solution_mark_hero_branch($hero['elements'][$content_index], 'content');

	foreach ($media_indexes as $media_index) {
		botphonic_solution_add_node_classes($hero['elements'][$media_index], array('bps-hero__media'));
		botphonic_solution_mark_hero_branch($hero['elements'][$media_index], 'media');
	}

	return $data;
}
add_filter('elementor/frontend/builder_content_data', 'botphonic_solution_elementor_content_data', 20, 2);
