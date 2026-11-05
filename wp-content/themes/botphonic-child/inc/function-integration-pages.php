<?php
/**
 * Integration pages: the hub (/integrations/) and the HubSpot, Salesforce,
 * Zapier, Zoho and WhatsApp pages beneath it.
 *
 * Each page is rendered by its own theme template, page-{slug}.php, which
 * WordPress picks from the page slug (page-integrations.php for the hub). The
 * template holds that page's copy; shared sections live in
 * template-parts/integrations/. The integration list shown on the hub and in
 * "More integrations" comes from botphonic_integration_directory() below.
 *
 * Everything below runs only while one of those templates renders a page:
 * - assets/css/integration-pages.css and assets/js/integration-pages.js load;
 * - Elementor's frontend CSS/JS is not loaded for that page. The page's
 *   Elementor data stays in the database untouched, as a backup;
 * - the Yoast WebPage node gains FAQPage (and CollectionPage on the hub), with
 *   Question nodes and, on the hub, an ItemList of the integrations.
 *
 * Elementor preview requests (editor iframe) keep the default page template,
 * so the old Elementor layout can still be opened as a reference.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

if (!defined('BOTPHONIC_INTEGRATION_STYLE')) {
	define('BOTPHONIC_INTEGRATION_STYLE', 'botphonic-integration-pages');
}

if (!defined('BOTPHONIC_INTEGRATION_HUB')) {
	// Active key of the Integrations hub (/integrations/).
	define('BOTPHONIC_INTEGRATION_HUB', 'hub');
}

if (!defined('BOTPHONIC_INTEGRATION_HUB_SLUG')) {
	// Page slug of the Integrations hub; its template is page-integrations.php.
	define('BOTPHONIC_INTEGRATION_HUB_SLUG', 'integrations');
}

if (!defined('BOTPHONIC_SIGNUP_FORM')) {
	// Contact Form 7 form 3104 ("Sign UP Form"): email capture that continues into the app (assets/js/custom.js).
	define('BOTPHONIC_SIGNUP_FORM', '[contact-form-7 id="fdc94f2" title="Sign UP Form"]');
}

/**
 * Link to a page of the Botphonic docs, e.g. botphonic_integration_docs_url('/integrations/hubspot').
 *
 * docs.botphonic.ai is a static export. Opened directly (from another site),
 * a clean URL such as /integrations/hubspot shows the docs home page; only
 * /integrations/hubspot.html opens the page itself (checked 3 Nov 2026). Every
 * docs link on the integration pages goes through here, so remove the suffix
 * in one place once the docs serve clean URLs.
 *
 * @param string $path Path of the docs page, starting with a slash.
 * @return string
 */
function botphonic_integration_docs_url($path)
{
	$path = '/' . trim((string) $path, '/');

	return 'https://docs.botphonic.ai' . ('/' === $path ? '/' : $path . '.html');
}

/**
 * Shared facts about each integration page: slug, logo, cross-link summary and
 * the entity named in the schema. Page copy lives in the page templates.
 *
 * @return array<string, array<string, mixed>>
 */
function botphonic_integration_registry()
{
	static $registry = null;

	if (null !== $registry) {
		return $registry;
	}

	$registry = array(
		'hubspot' => array(
			'slug' => 'hubspot-integration',
			'name' => 'HubSpot',
			'label' => 'HubSpot integration',
			'logo' => 'hubspot.svg',
			'logo_w' => 54,
			'logo_h' => 54,
			'summary' => 'Call new HubSpot contacts automatically and write the lead status, lifecycle stage and a call note back to the record.',
			'entity' => array('name' => 'HubSpot', 'url' => 'https://www.hubspot.com/'),
		),
		'salesforce' => array(
			'slug' => 'salesforce-integration',
			'name' => 'Salesforce',
			'label' => 'Salesforce integration',
			'logo' => 'salesforce.svg',
			'logo_w' => 58,
			'logo_h' => 40,
			'summary' => 'Look up the Salesforce record before the agent answers, update it during the call and log the outcome as a task.',
			'entity' => array('name' => 'Salesforce', 'url' => 'https://www.salesforce.com/'),
		),
		'zapier' => array(
			'slug' => 'zapier-integration',
			'name' => 'Zapier',
			'label' => 'Zapier integration',
			'logo' => 'zapier.svg',
			'logo_w' => 54,
			'logo_h' => 54,
			'summary' => 'Start AI calls from any Zap with Webhooks by Zapier, then send call results on to the rest of your apps.',
			'entity' => array('name' => 'Zapier', 'url' => 'https://zapier.com/'),
		),
		'zoho' => array(
			'slug' => 'zoho-integration',
			'name' => 'Zoho CRM',
			'label' => 'Zoho CRM integration',
			'logo' => 'zoho.svg',
			'logo_w' => 54,
			'logo_h' => 55,
			'summary' => 'Recognize callers from Zoho CRM, create or update leads from the conversation and log each call in the Calls module.',
			'entity' => array('name' => 'Zoho CRM', 'url' => 'https://www.zoho.com/crm/'),
		),
		'whatsapp' => array(
			'slug' => 'whatsapp-integration',
			'name' => 'WhatsApp',
			'label' => 'WhatsApp integration',
			'logo' => '',
			'mark' => 'whatsapp',
			'logo_w' => 48,
			'logo_h' => 48,
			'summary' => 'Send an automated WhatsApp message when someone submits a form, then run the follow-up workflow.',
			'entity' => array('name' => 'WhatsApp Business', 'url' => 'https://whatsappbusiness.com/'),
		),
	);

	/**
	 * Filters the integration page registry.
	 *
	 * @param array $registry Integration pages keyed by short name.
	 */
	$registry = (array) apply_filters('botphonic_integration_registry', $registry);

	return $registry;
}

/**
 * Categories of the integration directory, in filter order.
 *
 * `label` names the filter button; `card` is the short label on each card.
 *
 * @return array<string, array{label:string, card:string}>
 */
function botphonic_integration_directory_categories()
{
	return array(
		'crm' => array('label' => 'CRM', 'card' => 'CRM'),
		'calendar' => array('label' => 'Calendars', 'card' => 'Calendar'),
		'calling' => array('label' => 'Calling & numbers', 'card' => 'Calling'),
		'automation' => array('label' => 'Automation & API', 'card' => 'Automation & API'),
	);
}

/**
 * Every integration listed on the hub, in display order.
 *
 * Items with `page` take their name, summary, logo and URL from the registry
 * and link to their page under /integrations/. The others link to their guide
 * on docs.botphonic.ai. Facts follow the Botphonic docs (integrations,
 * phone numbers, email, actions, developers) and plan data, checked 2 Nov 2026.
 *
 * Fields: name, category (a key of botphonic_integration_directory_categories()),
 * badge (how it connects), tone (native|api|dev), summary, url, cta,
 * logo (file in assets/img/) or icon (botphonic_integration_icon() name),
 * keywords (extra search terms, not shown).
 *
 * @return array<string, array<string, mixed>>
 */
function botphonic_integration_directory()
{
	static $items = null;

	if (null !== $items) {
		return $items;
	}

	$raw = array(
		'hubspot' => array(
			'page' => 'hubspot',
			'category' => 'crm',
			'badge' => 'Native connector',
			'tone' => 'native',
			'keywords' => 'crm contacts lifecycle stage lead status notes oauth marketing',
		),
		'salesforce' => array(
			'page' => 'salesforce',
			'category' => 'crm',
			'badge' => 'Via REST API',
			'keywords' => 'crm leads contacts tasks opportunities flow sales cloud',
		),
		'zoho' => array(
			'page' => 'zoho',
			'category' => 'crm',
			'badge' => 'Via REST API',
			'keywords' => 'crm leads calls module workflow rules upsert',
		),
		'zapier' => array(
			'page' => 'zapier',
			'category' => 'automation',
			'badge' => 'Webhooks by Zapier',
			'keywords' => 'zaps automation no-code apps triggers forms sheets',
		),
		// Messages and follow-up workflows only: Botphonic doesn't do WhatsApp calling.
		'whatsapp' => array(
			'page' => 'whatsapp',
			'category' => 'automation',
			'badge' => 'Messages & workflows',
			'keywords' => 'whatsapp business messages templates forms leads follow-up workflow reminders notifications',
		),
		'calcom' => array(
			'name' => 'Cal.com',
			'category' => 'calendar',
			'badge' => 'Native connector',
			'tone' => 'native',
			'icon' => 'calendar',
			'summary' => 'Connect with an API key so the agent can check live availability and book meetings while the caller is on the line.',
			'url' => botphonic_integration_docs_url('/integrations/cal-com'),
			'cta' => 'Read the Cal.com setup guide',
			'keywords' => 'calendar booking appointments scheduling meetings real-time google meet',
		),
		'calendly' => array(
			'name' => 'Calendly',
			'category' => 'calendar',
			'badge' => 'Native connector',
			'tone' => 'native',
			'icon' => 'calendar',
			'summary' => 'Connect with a personal access token so the agent can offer open times and book meetings during the call, with Google Meet or Zoom links.',
			'url' => botphonic_integration_docs_url('/integrations/calendly'),
			'cta' => 'Read the Calendly setup guide',
			'keywords' => 'calendar booking appointments scheduling meetings real-time zoom google meet',
		),
		'twilio' => array(
			'name' => 'Twilio',
			'category' => 'calling',
			'badge' => 'Bring your own number',
			'tone' => 'native',
			'logo' => 'twilio.svg',
			'logo_w' => 64,
			'logo_h' => 64,
			'summary' => 'Use your existing Twilio numbers for inbound and outbound AI calls. Your active numbers are imported when you connect.',
			'url' => botphonic_integration_docs_url('/integrations/twilio'),
			'cta' => 'Read the Twilio setup guide',
			'keywords' => 'phone numbers telephony carrier voice byon sip',
		),
		'telnyx' => array(
			'name' => 'Telnyx',
			'category' => 'calling',
			'badge' => 'Bring your own number',
			'tone' => 'native',
			'logo' => 'telnyx.svg',
			'logo_w' => 27,
			'logo_h' => 25,
			'summary' => 'Bring your Telnyx numbers to Botphonic through a TeXML application, for inbound and outbound AI calls.',
			'url' => botphonic_integration_docs_url('/integrations/telnyx'),
			'cta' => 'Read the Telnyx setup guide',
			'keywords' => 'phone numbers telephony carrier voice byon texml',
		),
		'plivo' => array(
			'name' => 'Plivo',
			'category' => 'calling',
			'badge' => 'Bring your own number',
			'tone' => 'native',
			'logo' => 'plivo.svg',
			'logo_w' => 58,
			'logo_h' => 64,
			'summary' => 'Connect with your Plivo Auth ID and Auth Token to run inbound and outbound AI calls on your Plivo numbers.',
			'url' => botphonic_integration_docs_url('/integrations/plivo'),
			'cta' => 'Read the Plivo setup guide',
			'keywords' => 'phone numbers telephony carrier voice byon',
		),
		'exotel' => array(
			'name' => 'Exotel',
			'category' => 'calling',
			'badge' => 'Bring your own number',
			'tone' => 'native',
			'icon' => 'transfer',
			'summary' => 'Use your verified ExoPhones for inbound and outbound AI calls, connected through an Exotel Voicebot app.',
			'url' => botphonic_integration_docs_url('/integrations/exotel'),
			'cta' => 'Read the Exotel setup guide',
			'keywords' => 'phone numbers telephony carrier voice byon exophone india',
		),
		'email' => array(
			'name' => 'Email over SMTP',
			'category' => 'automation',
			'badge' => 'SMTP',
			'tone' => 'native',
			'icon' => 'mail',
			'summary' => 'Connect Gmail, Outlook or a business mailbox over SMTP to send follow-up emails from your workflows.',
			'url' => botphonic_integration_docs_url('/docs/build/email'),
			'cta' => 'Read the email setup guide',
			'keywords' => 'email mailbox gmail outlook smtp follow-up pool',
		),
		'custom-actions' => array(
			'name' => 'Custom actions',
			'category' => 'automation',
			'badge' => 'Developer',
			'tone' => 'dev',
			'icon' => 'flow',
			'summary' => 'Call any REST API before or during a call, for example to look up an order or create a record, and use the response in the conversation.',
			'url' => botphonic_integration_docs_url('/docs/build/actions/custom-http-action'),
			'cta' => 'Read the custom actions guide',
			'keywords' => 'http request api endpoint lookup crm database order status',
		),
		'webhooks' => array(
			'name' => 'Webhooks',
			'category' => 'automation',
			'badge' => 'Developer',
			'tone' => 'dev',
			'icon' => 'webhook',
			'summary' => 'Get signed HTTP callbacks when contacts join or leave a segment, so your own systems stay in step with Botphonic.',
			'url' => botphonic_integration_docs_url('/docs/developers/webhooks'),
			'cta' => 'Read the webhooks guide',
			'keywords' => 'events callbacks hmac signed segments',
		),
		'rest-api' => array(
			'name' => 'REST API',
			'category' => 'automation',
			'badge' => 'Developer',
			'tone' => 'dev',
			'icon' => 'code',
			'summary' => 'Create, update and look up contacts and manage segments from your own systems, with scoped API keys.',
			'url' => botphonic_integration_docs_url('/docs/developers/public-api'),
			'cta' => 'Read the API reference',
			'keywords' => 'api developer contacts segments json keys public',
		),
	);

	$registry = botphonic_integration_registry();
	$home_host = wp_parse_url(home_url('/'), PHP_URL_HOST);
	$items = array();

	foreach ($raw as $key => $item) {
		if (!empty($item['page'])) {
			if (empty($registry[$item['page']])) {
				continue;
			}

			$linked = $registry[$item['page']];
			$item = array_merge(
				array(
					'name' => $linked['name'],
					'summary' => $linked['summary'],
					'logo' => $linked['logo'],
					'mark' => $linked['mark'] ?? '',
					'logo_w' => $linked['logo_w'],
					'logo_h' => $linked['logo_h'],
					'url' => home_url('/integrations/' . $linked['slug'] . '/'),
					'cta' => sprintf('Explore the %s integration', $linked['name']),
				),
				$item
			);
		}

		$item = wp_parse_args(
			$item,
			array(
				'name' => '',
				'category' => '',
				'badge' => '',
				'tone' => 'api',
				'summary' => '',
				'url' => '',
				'cta' => '',
				'logo' => '',
				'mark' => '',
				'logo_w' => 48,
				'logo_h' => 48,
				'icon' => '',
				'keywords' => '',
			)
		);

		if ('' === $item['name'] || '' === $item['url']) {
			continue;
		}

		$item['key'] = (string) $key;
		$item['external'] = wp_parse_url($item['url'], PHP_URL_HOST) !== $home_host;
		$items[$key] = $item;
	}

	/**
	 * Filters the integration directory.
	 *
	 * @param array $items Integrations keyed by short name, in display order.
	 */
	$items = (array) apply_filters('botphonic_integration_directory', $items);

	return $items;
}

/**
 * Registry key for a page slug, or an empty string.
 *
 * @param string $slug Page slug.
 * @return string
 */
function botphonic_integration_key_for_slug($slug)
{
	foreach (botphonic_integration_registry() as $key => $item) {
		if (isset($item['slug']) && $item['slug'] === $slug) {
			return (string) $key;
		}
	}

	return '';
}

/**
 * Key of the integration template rendering this request.
 *
 * Set once from template_include; empty on every other request.
 *
 * @param string|null $set Key to store, or null to read.
 * @return string
 */
function botphonic_integration_active_key($set = null)
{
	static $key = '';

	if (null !== $set) {
		$key = sanitize_key((string) $set);
	}

	return $key;
}

/**
 * Whether the Integrations hub template renders this request.
 *
 * @return bool
 */
function botphonic_integration_is_hub()
{
	return BOTPHONIC_INTEGRATION_HUB === botphonic_integration_active_key();
}

/**
 * Page copy for the current request, registered by the page template before
 * get_header() so the schema filters can read the FAQ.
 *
 * @param array|null $set Page data to store, or null to read.
 * @return array
 */
function botphonic_integration_page_data($set = null)
{
	static $page = array();

	if (is_array($set)) {
		$page = $set;
	}

	return $page;
}

/**
 * Called at the top of each page template.
 *
 * Fills in the registry facts, links and defaults the template parts expect,
 * and stores the result for the asset and schema hooks.
 *
 * @param string $key  Registry key, e.g. "hubspot", or BOTPHONIC_INTEGRATION_HUB.
 * @param array  $page Page copy from the template.
 * @return array The completed page data.
 */
function botphonic_integration_boot($key, $page)
{
	$registry = botphonic_integration_registry();
	$key = sanitize_key((string) $key);
	$item = isset($registry[$key]) ? $registry[$key] : array();

	$page = wp_parse_args(
		(array) $page,
		array(
			'key' => $key,
			'name' => isset($item['name']) ? $item['name'] : '',
			'label' => isset($item['label']) ? $item['label'] : '',
			'entity' => isset($item['entity']) ? $item['entity'] : array(),
			'register_url' => defined('BOTPHONIC_REGISTER_URL') ? BOTPHONIC_REGISTER_URL : 'https://app.botphonic.ai/register/',
			'demo_url' => defined('BOTPHONIC_DEMO_URL') ? BOTPHONIC_DEMO_URL : 'https://calendly.com/contact-botphonic/product-discovery',
			'hub_url' => home_url('/' . BOTPHONIC_INTEGRATION_HUB_SLUG . '/'),
		)
	);

	botphonic_integration_active_key($key);
	botphonic_integration_page_data($page);

	return $page;
}

/**
 * Remember which integration template renders the page and detach Elementor.
 *
 * Priority 99 sees the final template after other template_include filters.
 *
 * @param string $template Template path chosen by WordPress.
 * @return string
 */
function botphonic_integration_template_include($template)
{
	$key = '';

	if (is_page() && is_string($template)) {
		$file = wp_basename($template);

		if ('page-' . BOTPHONIC_INTEGRATION_HUB_SLUG . '.php' === $file) {
			$key = BOTPHONIC_INTEGRATION_HUB;
		} else {
			foreach (botphonic_integration_registry() as $candidate => $item) {
				if (isset($item['slug']) && 'page-' . $item['slug'] . '.php' === $file) {
					$key = (string) $candidate;
					break;
				}
			}
		}
	}

	botphonic_integration_active_key($key);

	if ('' !== $key) {
		botphonic_integration_detach_elementor();
	}

	return $template;
}
add_filter('template_include', 'botphonic_integration_template_include', 99);

/**
 * Stop Elementor loading its frontend CSS and JS for this request.
 *
 * Elementor hooks Frontend::enqueue_styles() on wp_enqueue_scripts for pages
 * built with it (see Frontend::init(), run on template_redirect). The
 * integration templates never render that content, so the hook is removed.
 * Its footer scripts only load when Elementor content renders, so they stay
 * off too. Nothing changes on any other page.
 *
 * @return void
 */
function botphonic_integration_detach_elementor()
{
	if (!class_exists('\Elementor\Plugin') || !isset(\Elementor\Plugin::$instance->frontend)) {
		return;
	}

	$priority = defined('Elementor\Frontend::ENQUEUED_STYLES_PRIORITY') ? (int) constant('Elementor\Frontend::ENQUEUED_STYLES_PRIORITY') : 20;

	remove_action('wp_enqueue_scripts', array(\Elementor\Plugin::$instance->frontend, 'enqueue_styles'), $priority);
}

/**
 * Whether Elementor's editor preview is loading the page.
 *
 * @return bool
 */
function botphonic_integration_is_builder_preview()
{
	return class_exists('\Elementor\Plugin')
		&& isset(\Elementor\Plugin::$instance->preview)
		&& \Elementor\Plugin::$instance->preview->is_preview_mode();
}

/**
 * Keep the default page template inside Elementor's editor preview.
 *
 * Without this the editor would load page-{slug}.php, which has no content
 * area. Visitors always get the custom template: is_preview_mode() requires a
 * logged-in user who can edit the page.
 *
 * @param string[] $templates Candidate templates for the page.
 * @return string[]
 */
function botphonic_integration_template_hierarchy($templates)
{
	if (!botphonic_integration_is_builder_preview()) {
		return $templates;
	}

	$skip = array('page-' . BOTPHONIC_INTEGRATION_HUB_SLUG . '.php');
	foreach (botphonic_integration_registry() as $item) {
		if (!empty($item['slug'])) {
			$skip[] = 'page-' . $item['slug'] . '.php';
		}
	}

	return array_values(array_diff((array) $templates, $skip));
}
add_filter('page_template_hierarchy', 'botphonic_integration_template_hierarchy');

/**
 * Body classes for scoped styling.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function botphonic_integration_body_class($classes)
{
	$key = botphonic_integration_active_key();

	if ('' !== $key) {
		$classes[] = 'bpi-page';
		$classes[] = 'bpi-page--' . sanitize_html_class($key);
	}

	return array_values(array_unique($classes));
}
add_filter('body_class', 'botphonic_integration_body_class');

/**
 * Assets, only on the integration pages.
 *
 * Priority 30: runs after the child theme registers the shared landing
 * stylesheet (priority 25) and the Botphonic Core plugin registers the shared
 * FAQ component (priority 10). Versions come from file modification times.
 *
 * @return void
 */
function botphonic_integration_enqueue_assets()
{
	if ('' === botphonic_integration_active_key()) {
		return;
	}

	$dir = get_stylesheet_directory();
	$uri = get_stylesheet_directory_uri();

	// Shared FAQ accordion from the Botphonic Core plugin, loaded in the head instead of mid-page.
	if (wp_style_is('botphonic-faqs-css', 'registered')) {
		wp_enqueue_style('botphonic-faqs-css');
	}
	if (wp_script_is('botphonic-faqs-js', 'registered')) {
		wp_enqueue_script('botphonic-faqs-js');
	}

	$css = '/assets/css/integration-pages.css';
	if (file_exists($dir . $css)) {
		$deps = array();
		if (defined('BOTPHONIC_LANDING_STYLE') && wp_style_is(BOTPHONIC_LANDING_STYLE, 'registered')) {
			$deps[] = BOTPHONIC_LANDING_STYLE;
		}
		wp_enqueue_style(BOTPHONIC_INTEGRATION_STYLE, $uri . $css, $deps, (string) filemtime($dir . $css));
	}

	$js = '/assets/js/integration-pages.js';
	if (file_exists($dir . $js)) {
		wp_enqueue_script(
			BOTPHONIC_INTEGRATION_STYLE,
			$uri . $js,
			array(),
			(string) filemtime($dir . $js),
			array('in_footer' => true, 'strategy' => 'defer')
		);
	}
}
add_action('wp_enqueue_scripts', 'botphonic_integration_enqueue_assets', 30);

/**
 * Editor note on the integration pages' edit screens, so nobody edits copy
 * that visitors no longer see.
 *
 * @return void
 */
function botphonic_integration_admin_notice()
{
	$screen = function_exists('get_current_screen') ? get_current_screen() : null;
	if (!$screen || 'post' !== $screen->base || 'page' !== $screen->post_type) {
		return;
	}

	$post = get_post();
	if (!$post instanceof WP_Post) {
		return;
	}

	$is_hub = BOTPHONIC_INTEGRATION_HUB_SLUG === $post->post_name && 0 === (int) $post->post_parent;
	if (!$is_hub && '' === botphonic_integration_key_for_slug($post->post_name)) {
		return;
	}

	$file = 'page-' . $post->post_name . '.php';
	if ('' === locate_template($file)) {
		return;
	}

	printf(
		'<div class="notice notice-info"><p>%s</p></div>',
		esc_html(
			sprintf(
				/* translators: %s: template file name. */
				__('Visitors see this page through the %s theme template, so edit its copy there. The content and Elementor layout below are kept as a backup and are not shown on the site. Yoast SEO settings on this screen still apply.', 'botphonic'),
				$file
			)
		)
	);
}
add_action('admin_notices', 'botphonic_integration_admin_notice');

/**
 * FAQ rows of the current page with plain-text answers for the schema.
 *
 * @return array<int, array{question:string, answer:string}>
 */
function botphonic_integration_schema_faqs()
{
	if ('' === botphonic_integration_active_key()) {
		return array();
	}

	$page = botphonic_integration_page_data();
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

/**
 * Yoast: add FAQPage when the page has an FAQ, and CollectionPage on the hub.
 *
 * Google stopped showing FAQ rich results on 7 May 2026. The markup stays
 * valid schema.org and still describes the visible content for other consumers.
 *
 * @param string|string[] $type WebPage type(s).
 * @return string|string[]
 */
function botphonic_integration_schema_webpage_type($type)
{
	$add = array();
	if (botphonic_integration_is_hub()) {
		$add[] = 'CollectionPage';
	}
	if (botphonic_integration_schema_faqs()) {
		$add[] = 'FAQPage';
	}

	if (!$add) {
		return $type;
	}

	$types = (array) $type;
	foreach ($add as $extra) {
		if (!in_array($extra, $types, true)) {
			$types[] = $extra;
		}
	}

	return $types;
}
add_filter('wpseo_schema_webpage_type', 'botphonic_integration_schema_webpage_type');

/**
 * Yoast: point the WebPage at its main content (the integration list on the
 * hub, the questions) and at the integrated product.
 *
 * @param array $data WebPage piece.
 * @return array
 */
function botphonic_integration_schema_webpage($data)
{
	if ('' === botphonic_integration_active_key() || !is_array($data)) {
		return $data;
	}

	$page = botphonic_integration_page_data();
	$base = isset($data['@id']) ? (string) $data['@id'] : trailingslashit((string) get_permalink());

	$main = array();
	if (botphonic_integration_is_hub() && botphonic_integration_directory()) {
		$main[] = array('@id' => $base . '#integration-list');
	}
	foreach (array_keys(botphonic_integration_schema_faqs()) as $index) {
		$main[] = array('@id' => $base . '#faq-' . ($index + 1));
	}
	if ($main && empty($data['mainEntity'])) {
		$data['mainEntity'] = $main;
	}

	if (!empty($page['entity']['name']) && empty($data['mentions'])) {
		$entity = array(
			'@type' => 'Brand',
			'name' => (string) $page['entity']['name'],
		);
		if (!empty($page['entity']['url'])) {
			$entity['url'] = esc_url_raw($page['entity']['url']);
		}
		$data['mentions'] = array($entity);
	}

	return $data;
}
add_filter('wpseo_schema_webpage', 'botphonic_integration_schema_webpage');

/**
 * Yoast: add one Question node per visible FAQ item and, on the hub, an
 * ItemList of the integrations shown in the directory.
 *
 * @param array $graph   Schema graph.
 * @param mixed $context Yoast meta tags context.
 * @return array
 */
function botphonic_integration_schema_graph($graph, $context = null)
{
	$faqs = botphonic_integration_schema_faqs();
	$list = botphonic_integration_is_hub() ? botphonic_integration_directory() : array();

	if ((!$faqs && !$list) || !is_array($graph)) {
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

	if ($list && empty($present[$base . '#integration-list'])) {
		$elements = array();
		foreach (array_values($list) as $index => $item) {
			$elements[] = array(
				'@type' => 'ListItem',
				'position' => $index + 1,
				'name' => $item['name'],
				'url' => esc_url_raw($item['url']),
			);
		}

		$graph[] = array(
			'@type' => 'ItemList',
			'@id' => $base . '#integration-list',
			'name' => 'Botphonic integrations',
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
add_filter('wpseo_schema_graph', 'botphonic_integration_schema_graph', 20, 2);

/**
 * Small inline icon set for the integration pages (24px grid, 2px stroke).
 *
 * @param string $name Icon name.
 * @param int    $size Rendered size in px.
 * @return string SVG markup, or an empty string for unknown names.
 */
function botphonic_integration_icon($name, $size = 22)
{
	static $paths = array(
		'check' => '<path d="M5 12l5 5L20 7"/>',
		'arrow' => '<path d="M5 12h14"/><path d="m13 6 6 6-6 6"/>',
		'external' => '<path d="M7 17 17 7"/><path d="M8 7h9v9"/>',
		'link' => '<path d="M10 13a5 5 0 0 0 7.5.5l3-3a5 5 0 0 0-7-7l-1.7 1.7"/><path d="M14 11a5 5 0 0 0-7.5-.5l-3 3a5 5 0 0 0 7 7l1.7-1.7"/>',
		'user-plus' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6"/><path d="M22 11h-6"/>',
		'trending' => '<path d="m22 7-8.5 8.5-5-5L2 17"/><path d="M16 7h6v6"/>',
		'tag' => '<path d="M12.6 2.6A2 2 0 0 0 11.2 2H4a2 2 0 0 0-2 2v7.2a2 2 0 0 0 .6 1.4l8.7 8.7a2.4 2.4 0 0 0 3.4 0l6.6-6.6a2.4 2.4 0 0 0 0-3.4z"/><circle cx="7.5" cy="7.5" r="1.5"/>',
		'phone-off' => '<path d="M10.7 13.3a16 16 0 0 0 3.4 2.6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-3.2-2.6"/><path d="M6.5 13.6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8"/><path d="M22 2 2 22"/>',
		'note' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/>',
		'refresh' => '<path d="M21 12a9 9 0 0 0-15-6.7L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 15 6.7l3-2.7"/><path d="M16 16h5v5"/>',
		'search' => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
		'edit' => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>',
		'list-check' => '<path d="M11 18H3"/><path d="m15 18 2 2 4-4"/><path d="M16 12H3"/><path d="M16 6H3"/>',
		'flow' => '<rect width="8" height="8" x="3" y="3" rx="2"/><path d="M7 11v4a2 2 0 0 0 2 2h4"/><rect width="8" height="8" x="13" y="13" rx="2"/>',
		'calendar' => '<path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="m9 16 2 2 4-4"/>',
		'transfer' => '<path d="M14 2a9 9 0 0 1 8 8"/><path d="M14 6a5 5 0 0 1 4 4"/><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"/>',
		'zap' => '<path d="M13 2 3 14h9l-1 8 10-12h-9z"/>',
		'webhook' => '<path d="M18 16.98h-5.99c-1.1 0-1.95.94-2.48 1.9A4 4 0 0 1 2 17c.01-.7.2-1.4.57-2"/><path d="m6 17 3.13-5.78c.53-.97.1-2.18-.5-3.1a4 4 0 1 1 6.89-4.06"/><path d="m12 6 3.13 5.73C15.66 12.7 16.9 13 18 13a4 4 0 0 1 0 8"/>',
		'key' => '<circle cx="7.5" cy="15.5" r="5.5"/><path d="m21 2-9.6 9.6"/><path d="m15.5 7.5 3 3L22 7l-3-3"/>',
		'copy' => '<rect width="14" height="14" x="8" y="8" rx="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/>',
		'layers' => '<path d="m12 2 10 5-10 5L2 7z"/><path d="m2 17 10 5 10-5"/><path d="m2 12 10 5 10-5"/>',
		'clock' => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
		'message' => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22z"/>',
		'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>',
		'lock' => '<rect width="18" height="11" x="3" y="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
		'globe' => '<circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
		'database' => '<ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14a9 3 0 0 0 18 0V5"/><path d="M3 12a9 3 0 0 0 18 0"/>',
		'mail' => '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-10 6L2 7"/>',
		'code' => '<path d="m16 18 6-6-6-6"/><path d="m8 6-6 6 6 6"/>',
		'plug' => '<path d="M12 22v-5"/><path d="M9 8V2"/><path d="M15 8V2"/><path d="M18 8v5a4 4 0 0 1-4 4h-4a4 4 0 0 1-4-4V8z"/>',
	);

	if (!isset($paths[$name])) {
		return '';
	}

	return sprintf(
		'<svg class="bpi-svg" width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%2$s</svg>',
		(int) $size,
		$paths[$name]
	);
}

/**
 * Logo markup for a directory or registry item.
 *
 * SVG files from assets/img/ are used as images. WhatsApp gets a drawn
 * chat-and-phone tile in WhatsApp green rather than a copy of its logo, and
 * items without a logo file get their icon on a tinted tile.
 *
 * @param array  $item  Item with logo/logo_w/logo_h, mark or icon.
 * @param string $alt   Alt text; pass an empty string when the name is visible next to it.
 * @param bool   $eager Load immediately (hero) instead of lazily.
 * @return string
 */
function botphonic_integration_item_logo($item, $alt = '', $eager = false)
{
	$item = (array) $item;
	$label = '' !== $alt ? ' role="img" aria-label="' . esc_attr($alt) . '"' : ' aria-hidden="true"';

	if ('whatsapp' === ($item['mark'] ?? '')) {
		return '<span class="bpi-logo bpi-logo--whatsapp"' . $label . '><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false" aria-hidden="true"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22z"/><path d="M15.6 13.7v1.3a.9.9 0 0 1-1 .9 8.6 8.6 0 0 1-3.7-1.3 8.5 8.5 0 0 1-2.6-2.6A8.6 8.6 0 0 1 7 8.3a.9.9 0 0 1 .9-1h1.3a.9.9 0 0 1 .9.8c.1.4.2.8.3 1.2a.9.9 0 0 1-.2.9l-.5.6a7 7 0 0 0 2.6 2.6l.6-.5a.9.9 0 0 1 .9-.2c.4.1.8.3 1.2.3a.9.9 0 0 1 .8.9z" stroke-width="1.4"/></svg></span>';
	}

	if (!empty($item['logo'])) {
		$file = '/assets/img/' . $item['logo'];
		if (file_exists(get_stylesheet_directory() . $file)) {
			return sprintf(
				'<img class="bpi-logo" src="%1$s" alt="%2$s" width="%3$d" height="%4$d"%5$s decoding="async">',
				esc_url(get_stylesheet_directory_uri() . $file),
				esc_attr($alt),
				(int) ($item['logo_w'] ?? 48),
				(int) ($item['logo_h'] ?? 48),
				$eager ? '' : ' loading="lazy"'
			);
		}
	}

	if (!empty($item['icon'])) {
		$svg = botphonic_integration_icon((string) $item['icon'], 24);
		if ('' !== $svg) {
			return '<span class="bpi-logo bpi-logo--icon"' . $label . '>' . $svg . '</span>';
		}
	}

	return '';
}

/**
 * Logo markup for an integration page in the registry.
 *
 * @param string $key   Registry key.
 * @param string $alt   Alt text; pass an empty string when the name is visible next to it.
 * @param bool   $eager Load immediately (hero) instead of lazily.
 * @return string
 */
function botphonic_integration_logo($key, $alt = '', $eager = false)
{
	$registry = botphonic_integration_registry();
	if (empty($registry[$key])) {
		return '';
	}

	return botphonic_integration_item_logo($registry[$key], $alt, $eager);
}

/**
 * Botphonic wordmark (the site's custom logo), with a text fallback.
 *
 * @return string
 */
function botphonic_integration_brand_mark()
{
	$logo_id = (int) get_theme_mod('custom_logo');
	$src = $logo_id ? wp_get_attachment_image_url($logo_id, 'full') : '';

	if ($src) {
		return sprintf(
			'<img class="bpi-brand" src="%s" alt="Botphonic" width="150" height="30" decoding="async">',
			esc_url($src)
		);
	}

	return '<span class="bpi-brand bpi-brand--text">Botphonic</span>';
}

/**
 * Allow the limited inline markup used in page copy (links and emphasis).
 *
 * @param string $text Copy from a page template.
 * @return string
 */
function botphonic_integration_kses($text)
{
	return wp_kses(
		(string) $text,
		array(
			'a' => array('href' => true, 'class' => true),
			'strong' => array(),
			'em' => array(),
			'code' => array(),
			'br' => array(),
		)
	);
}
