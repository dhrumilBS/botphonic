<?php
/**
 * Plugin Name: Botphonic Core
 * Description: Custom Elementor widgets.
 * Version: 1.1
 * Author: BotPhonic
 *
 * @package BotPhonic
 */

if (!defined('ABSPATH')) {
	exit;
}

define('BOTPHONIC_URL', plugin_dir_url(__FILE__));
define('BOTPHONIC_PATH', plugin_dir_path(__FILE__));

function botphonic_asset_ver($relative_path)
{
	$file = BOTPHONIC_PATH . $relative_path;

	return file_exists($file) ? (string) filemtime($file) : '1';
}

/**
 * Convert FAQ content to safe plain text for structured data.
 *
 * @param mixed $value Raw FAQ value.
 * @return string
 */
function botphonic_normalize_faq_schema_text($value)
{
	if (!is_scalar($value)) {
		return '';
	}

	$text = strip_shortcodes((string) $value);
	$text = wp_strip_all_tags($text);
	$charset = get_bloginfo('charset') ?: 'UTF-8';
	$text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, $charset);
	$collapsed = preg_replace('/\s+/u', ' ', $text);

	return trim(is_string($collapsed) ? $collapsed : $text);
}

/**
 * Validate and normalize ACF/SCF FAQ repeater rows for every renderer.
 *
 * @param mixed $rows Raw repeater value.
 * @return array<int, array<string, string>>
 */
function botphonic_normalize_faq_rows($rows)
{
	if (!is_array($rows)) {
		return array();
	}

	$normalized = array();

	foreach ($rows as $row) {
		if (
			!is_array($row)
			|| !array_key_exists('question', $row)
			|| !array_key_exists('answer', $row)
			|| !is_scalar($row['question'])
			|| !is_scalar($row['answer'])
		) {
			continue;
		}

		$question = botphonic_normalize_faq_schema_text($row['question']);
		$answer = trim(wp_kses_post((string) $row['answer']));
		$schema_answer = botphonic_normalize_faq_schema_text($answer);

		if ('' === $question || '' === $schema_answer) {
			continue;
		}

		$normalized[] = array(
			'question' => $question,
			'answer' => $answer,
			'schema_question' => $question,
			'schema_answer' => $schema_answer,
		);
	}

	return $normalized;
}

/**
 * Read and normalize FAQ rows for an explicit post context.
 *
 * @param int $post_id Post ID. Defaults to the queried object.
 * @return array<int, array<string, string>>
 */
function botphonic_get_faq_rows($post_id = 0)
{
	if (!function_exists('get_field')) {
		return array();
	}

	$post_id = absint($post_id);
	if (!$post_id) {
		$post_id = absint(get_queried_object_id());
	}

	if (!$post_id) {
		return array();
	}

	return botphonic_normalize_faq_rows(get_field('faqs', $post_id));
}

/**
 * Return the shared decorative chevron used by every FAQ renderer.
 *
 * @return string
 */
function botphonic_faq_icon_html()
{
	return '<span class="faq-icon" aria-hidden="true">
		<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" focusable="false">
			<path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
		</svg>
	</span>';
}

/**
 * Render the canonical site-wide FAQ accordion group.
 *
 * @param mixed $rows FAQ rows containing question and answer keys.
 * @param array $args Optional rendering options. Supports schema (bool).
 * @return string
 */
function botphonic_render_faq_group($rows, $args = array())
{
	$faqs = botphonic_normalize_faq_rows($rows);
	if (!$faqs) {
		return '';
	}

	$args = wp_parse_args(
		$args,
		array(
			'schema' => true,
		)
	);

	wp_enqueue_style('botphonic-faqs-css');
	wp_enqueue_script('botphonic-faqs-js');

	if ($args['schema']) {
		botphonic_register_faq_schema($faqs);
	}

	$group_name = wp_unique_id('botphonic-faq-');
	$icon = botphonic_faq_icon_html();

	ob_start();
	?>
	<div class="faq-content-wrap" data-botphonic-faq>
		<?php foreach ($faqs as $index => $faq): ?>
			<details class="faq-item" name="<?= esc_attr($group_name); ?>"<?= 0 === $index ? ' open' : ''; ?>>
				<summary class="faq-head">
					<span class="faq-item-heading"><?= esc_html($faq['question']); ?></span>
					<?= $icon; ?>
				</summary>
				<div class="faq-text">
					<div class="faq-paragraph"><?= wp_kses_post($faq['answer']); ?></div>
				</div>
			</details>
		<?php endforeach; ?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Add rendered FAQ rows to the request-level schema registry.
 * Duplicate questions keep the first visibly rendered answer.
 *
 * @param mixed $rows FAQ rows, raw or already normalized.
 * @return void
 */
function botphonic_register_faq_schema($rows)
{
	$rows = botphonic_normalize_faq_rows($rows);
	if (!$rows) {
		return;
	}

	if (!isset($GLOBALS['botphonic_faq_schema_entities']) || !is_array($GLOBALS['botphonic_faq_schema_entities'])) {
		$GLOBALS['botphonic_faq_schema_entities'] = array();
	}

	foreach ($rows as $row) {
		$key = hash('sha256', strtolower($row['schema_question']));
		if (isset($GLOBALS['botphonic_faq_schema_entities'][$key])) {
			continue;
		}

		$GLOBALS['botphonic_faq_schema_entities'][$key] = array(
			'@type' => 'Question',
			'name' => $row['schema_question'],
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text' => $row['schema_answer'],
			),
		);
	}
}

/**
 * Recursively detect an FAQPage node in an existing schema graph.
 *
 * @param mixed $value Graph or graph node.
 * @return bool
 */
function botphonic_schema_contains_faq_page($value)
{
	if (!is_array($value)) {
		return false;
	}

	if (isset($value['@type'])) {
		$types = (array) $value['@type'];
		if (in_array('FAQPage', $types, true)) {
			return true;
		}
	}

	foreach ($value as $child) {
		if (is_array($child) && botphonic_schema_contains_faq_page($child)) {
			return true;
		}
	}

	return false;
}

/**
 * Remember when Yoast already owns FAQPage schema for this request.
 *
 * @param array $graph Yoast schema graph.
 * @return array
 */
function botphonic_observe_yoast_faq_schema($graph)
{
	if (botphonic_schema_contains_faq_page($graph)) {
		$GLOBALS['botphonic_yoast_has_faq_schema'] = true;
	}

	return $graph;
}
add_filter('wpseo_schema_graph', 'botphonic_observe_yoast_faq_schema', PHP_INT_MAX, 1);

/**
 * Print one safely encoded FAQPage for all rendered BotPhonic FAQ groups.
 *
 * @return void
 */
function botphonic_output_faq_schema()
{
	if (!empty($GLOBALS['botphonic_yoast_has_faq_schema'])) {
		return;
	}

	$registered = $GLOBALS['botphonic_faq_schema_entities'] ?? array();
	if (!$registered || !is_array($registered)) {
		return;
	}

	$json = wp_json_encode(
		array(
			'@context' => 'https://schema.org',
			'@type' => 'FAQPage',
			'mainEntity' => array_values($registered),
		),
		JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE
	);

	if (!is_string($json)) {
		return;
	}

	echo "\n<script type=\"application/ld+json\">{$json}</script>\n";
}
add_action('wp_footer', 'botphonic_output_faq_schema', PHP_INT_MAX);

/**
 * Decide whether the base FAQ stylesheet is needed before wp_head runs.
 *
 * @return bool
 */
function botphonic_should_enqueue_faq_style()
{
	if (!is_singular()) {
		return false;
	}

	$post_id = absint(get_queried_object_id());
	if (!$post_id || 'alternatives' === get_post_type($post_id)) {
		return false;
	}

	$post = get_post($post_id);
	$has_shortcode = $post instanceof WP_Post && has_shortcode($post->post_content, 'botphonic_faq');
	$has_rows = (bool) botphonic_get_faq_rows($post_id);

	return (bool) apply_filters('botphonic_should_enqueue_faq_style', $has_shortcode || $has_rows, $post_id);
}

add_action('wp_enqueue_scripts', 'botphonic_enqueue_assets');
function botphonic_enqueue_assets()
{

	wp_enqueue_style('botphonic', BOTPHONIC_URL . 'assets/css/botphonic.css', array(), botphonic_asset_ver('assets/css/botphonic.css'));
	wp_register_script('botphonic-wavesurfer-js', 'https://unpkg.com/wavesurfer.js@7.8.6/dist/wavesurfer.min.js', array(), '7.8.6', true);
	wp_register_style('botphonic-faqs-css', BOTPHONIC_URL . 'assets/css/botphonic-faqs.css', array(), botphonic_asset_ver('assets/css/botphonic-faqs.css'));
	wp_register_script('botphonic-faqs-js', BOTPHONIC_URL . 'assets/js/botphonic-faqs.js', array('jquery'), botphonic_asset_ver('assets/js/botphonic-faqs.js'), true);

	if (botphonic_should_enqueue_faq_style()) {
		wp_enqueue_style('botphonic-faqs-css');
	}

	// How It Works
	wp_register_style('botphonic-how-it-works', BOTPHONIC_URL . 'assets/css/botphonic-how-it-works.css', array(), botphonic_asset_ver('assets/css/botphonic-how-it-works.css'));

	// Social Rating
	wp_register_style('botphonic-social-rating', BOTPHONIC_URL . 'assets/css/botphonic-social-rating.css', array(), botphonic_asset_ver('assets/css/botphonic-social-rating.css'));

	// Status Badge
	wp_register_style('botphonic-available-badge', BOTPHONIC_URL . 'assets/css/botphonic-available-badge.css', array(), botphonic_asset_ver('assets/css/botphonic-available-badge.css'));

	// Integration
	wp_register_style('botphonic-integration-css', BOTPHONIC_URL . 'assets/css/integration.css', array(), botphonic_asset_ver('assets/css/integration.css'));
	wp_register_script('botphonic-integration-js', BOTPHONIC_URL . 'assets/js/integration.js', array('jquery'), botphonic_asset_ver('assets/js/integration.js'), true);

	// Industry Slider
	wp_register_style('botphonic-industry-slider-css', BOTPHONIC_URL . 'assets/css/botphonic-industry-slider.css', array(), botphonic_asset_ver('assets/css/botphonic-industry-slider.css'));
	wp_register_script('botphonic-industry-slider-js', BOTPHONIC_URL . 'assets/js/botphonic-industry-slider.js', array('jquery', 'swiper', 'botphonic-wavesurfer-js'), botphonic_asset_ver('assets/js/botphonic-industry-slider.js'), true);

	// Audio Agent Grid
	wp_register_style('botphonic-audio-agent-grid', BOTPHONIC_URL . 'assets/css/botphonic-audio-agent-grid.css', array(), botphonic_asset_ver('assets/css/botphonic-audio-agent-grid.css'));
	wp_register_script('botphonic-audio-agent-grid', BOTPHONIC_URL . 'assets/js/botphonic-audio-agent-grid.js', array('jquery', 'botphonic-wavesurfer-js'), botphonic_asset_ver('assets/js/botphonic-audio-agent-grid.js'), true);

	// Icon Box
	wp_register_style('botphonic-how-to', BOTPHONIC_URL . 'assets/css/botphonic-how-to.css', array(), botphonic_asset_ver('assets/css/botphonic-how-to.css'));
	wp_register_script('botphonic-how-to', BOTPHONIC_URL . 'assets/js/botphonic-how-to.js', array('jquery'), botphonic_asset_ver('assets/js/botphonic-how-to.js'), true);

	// BotPhonic Accordion
	wp_register_style('botphonic-accordion', BOTPHONIC_URL . 'assets/css/botphonic-accordion.css', array(), botphonic_asset_ver('assets/css/botphonic-accordion.css'));
	wp_register_script('botphonic-accordion', BOTPHONIC_URL . 'assets/js/botphonic-accordion.js', array('jquery'), botphonic_asset_ver('assets/js/botphonic-accordion.js'), true);

	// BF Scroll Tabs
	wp_register_style('bf-scroll-tabs', BOTPHONIC_URL . 'assets/css/bf-scroll-tabs.css', array(), botphonic_asset_ver('assets/css/bf-scroll-tabs.css'));
	wp_register_script('bf-scroll-tabs', BOTPHONIC_URL . 'assets/js/bf-scroll-tabs.js', array('jquery'), botphonic_asset_ver('assets/js/bf-scroll-tabs.js'), true);
}

add_action('elementor/elements/categories_registered', 'botphonic_add_custom_widget_category');
function botphonic_add_custom_widget_category($elements_manager)
{
	$elements_manager->add_category(
		'botphonic-widgets',
		array(
			'title' => __('BotPhonic Widgets', 'botphonic'),
			'icon' => 'fa fa-plug',
		)
	);
}

add_action('elementor/widgets/register', 'botphonic_register_widget');
function botphonic_register_widget($widgets_manager)
{

	$widgets = array(
		'botphonic-faqs.php',
		'botphonic-integration.php',
		'botphonic-audio-button.php',
		'botphonic-audio-sample-box.php',
		'botphonic-available-badge.php',
		'botphonic-industry-slidder.php',
		'botphonic-audio-agent-grid.php',
		'how-it-works.php',
		'botphonic-how-to.php',
		'social-rating.php',
		'botphonic-accordion.php',
		'bf-fancy-box-widget.php',
		'bf-scroll-tabs.php',
	);

	foreach ($widgets as $widget_file) {
		$path = BOTPHONIC_PATH . 'widgets/' . $widget_file;

		if (file_exists($path)) {
			require_once $path;
		}
	}
}