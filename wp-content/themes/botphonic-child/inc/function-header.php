<?php
/**
 * Site header: navigation data, icons, helpers and assets.
 *
 * Markup lives in template-parts/header/site-header.php. The same markup is the
 * desktop mega menu (1200px and up) and the full-screen menu below that.
 */

defined('ABSPATH') || exit;

if (!defined('BOTPHONIC_DEMO_URL')) {
	define('BOTPHONIC_DEMO_URL', 'https://calendly.com/contact-botphonic/product-discovery');
}
if (!defined('BOTPHONIC_SIGNIN_URL')) {
	define('BOTPHONIC_SIGNIN_URL', 'https://app.botphonic.ai/login');
}
if (!defined('BOTPHONIC_REGISTER_URL')) {
	define('BOTPHONIC_REGISTER_URL', 'https://app.botphonic.ai/register/');
}

/**
 * Primary navigation.
 *
 * Top-level entries are either a link (url) or a mega menu (columns). A column
 * holds rich items (icon, title, description) or plain links, plus an optional
 * "more" link. Filter with botphonic_header_nav to change content.
 */
function botphonic_header_nav()
{
	static $nav = null;
	if (null !== $nav) {
		return $nav;
	}

	$nav = array(
		'product'   => array(
			'label'   => 'Product',
			'columns' => array(
				array(
					'label' => 'Build',
					'items' => array(
						array('title' => 'Agent Studio', 'desc' => 'Build an agent without code', 'url' => home_url('/product/ai-agent-studio/'), 'icon' => 'wand'),
						array('title' => 'AI voice agents', 'desc' => 'Pre-built agents by use case', 'url' => home_url('/ai-voice-agents/'), 'icon' => 'bot'),
						array('title' => 'Agent templates', 'desc' => 'Start from a proven call flow', 'url' => home_url('/marketplace/'), 'icon' => 'grid'),
					),
				),
				array(
					'label' => 'Run',
					'items' => array(
						array('title' => 'AI phone call', 'desc' => 'Inbound and outbound calls', 'url' => home_url('/product/ai-phone-call/'), 'icon' => 'phone-call'),
						array('title' => '50+ languages', 'desc' => 'Switches language mid-call', 'url' => home_url('/product/multilingual-voice-ai-agents/'), 'icon' => 'globe'),
						array('title' => 'Security and compliance', 'desc' => 'HIPAA and PCI DSS ready', 'url' => home_url('/security/'), 'icon' => 'shield'),
					),
				),
				array(
					'label' => 'Integrations',
					'links' => array(
						array('title' => 'Salesforce', 'url' => home_url('/salesforce-integration/')),
						array('title' => 'HubSpot', 'url' => home_url('/hubspot-integration/')),
						array('title' => 'Zapier', 'url' => home_url('/zapier-integration/')),
						array('title' => 'Zoho', 'url' => home_url('/zoho-integration/')),
						array('title' => 'WhatsApp', 'url' => home_url('/whatsapp-integration/')),
					),
					'more'  => array('title' => 'All integrations', 'url' => home_url('/integrations/')),
				),
			),
			'feature' => array(
				'badge' => '14-day free trial',
				'title' => 'Live in minutes, not weeks',
				'text'  => 'Build your agent, connect a number and start taking calls the same day.',
				'cta'   => array('title' => 'Start free trial', 'url' => BOTPHONIC_REGISTER_URL),
			),
			'footer'  => array(
				'badge' => 'Live demo',
				'text'  => 'Picks up in under 300 ms. Hear it yourself.',
				'link'  => array('title' => 'Talk to our AI now', 'url' => 'https://app.botphonic.ai/voice-assistant'),
			),
		),
		'solutions' => array(
			'label'   => 'Solutions',
			'columns' => array(
				array(
					'span'  => 2,
					'items' => array(
						array('title' => 'AI receptionist', 'desc' => 'Answers, books, transfers', 'url' => home_url('/product/ai-receptionist/'), 'icon' => 'phone-incoming'),
						array('title' => 'AI answering service', 'desc' => 'Answers and logs every call', 'url' => home_url('/product/ai-answering-service/'), 'icon' => 'message'),
						array('title' => 'AI customer service', 'desc' => 'Resolves questions 24/7', 'url' => home_url('/product/ai-customer-service/'), 'icon' => 'smile'),
						array('title' => 'AI call center', 'desc' => 'High call volume, both ways', 'url' => home_url('/product/ai-call-center/'), 'icon' => 'headphones'),
						array('title' => 'AI appointment booking', 'desc' => 'Books callers into open slots', 'url' => home_url('/product/ai-appointment-booking/'), 'icon' => 'calendar-check'),
						array('title' => 'AI appointment setter', 'desc' => 'Books into your calendar', 'url' => home_url('/ai-appointment-setter/'), 'icon' => 'calendar'),
						array('title' => 'AI sales assistant', 'desc' => 'Qualifies every new lead', 'url' => home_url('/product/ai-sales-assistant/'), 'icon' => 'trending'),
						array('title' => 'AI telemarketing', 'desc' => 'Outbound campaigns at scale', 'url' => home_url('/product/ai-telemarketing/'), 'icon' => 'megaphone'),
						array('title' => 'AI IVR', 'desc' => 'Talk instead of press 1', 'url' => home_url('/product/ai-ivr/'), 'icon' => 'list'),
						array('title' => 'AI concierge', 'desc' => 'Personal help, 24/7', 'url' => home_url('/product/ai-concierge/'), 'icon' => 'bell'),
					),
				),
				array(
					'label' => 'Industries',
					'links' => array(
						array('title' => 'Healthcare', 'url' => home_url('/industry/healthcare/')),
						array('title' => 'Real estate', 'url' => home_url('/industry/real-estate/')),
						array('title' => 'Insurance', 'url' => home_url('/industry/insurance/')),
						array('title' => 'Financial services', 'url' => home_url('/industry/finance/')),
						array('title' => 'BPO customer service', 'url' => home_url('/industry/bpo-customer-service/')),
						array('title' => 'Home services', 'url' => home_url('/industry/home-services/')),
					),
					'more'  => array('title' => 'All industries', 'url' => home_url('/industry/')),
				),
			),
			'feature' => array(
				'badge' => 'Sample calls',
				'title' => 'Hear it answer real business calls',
				'text'  => 'Play recorded calls by industry, then talk to a live agent in your browser.',
				'cta'   => array('title' => 'Play sample calls', 'url' => home_url('/#listen'), 'icon' => 'play'),
			),
			'footer'  => array(
				'text' => 'Not sure which fits? We will map it to your call flow.',
				'link' => array('title' => 'Book a demo', 'url' => BOTPHONIC_DEMO_URL),
			),
		),
		'pricing'   => array(
			'label' => 'Pricing',
			'url'   => home_url('/pricing/'),
		),
		'resources' => array(
			'label'   => 'Resources',
			'columns' => array(
				array(
					'label' => 'Learn',
					'items' => array(
						array('title' => 'Blog', 'desc' => 'Playbooks for AI calling', 'url' => home_url('/blog/'), 'icon' => 'file'),
						array('title' => 'Customer stories', 'desc' => 'How teams use Botphonic', 'url' => home_url('/success-stories/'), 'icon' => 'users'),
						array('title' => 'About Botphonic', 'desc' => 'Who we are and why we build', 'url' => home_url('/about/'), 'icon' => 'info'),
					),
				),
				array(
					'label' => 'Compare',
					'links' => array(
						array('title' => 'Retell AI alternatives', 'url' => home_url('/blog/retell-ai-alternative/')),
						array('title' => 'Vapi alternatives', 'url' => home_url('/blog/vapi-alternative/')),
						array('title' => 'Bland AI alternatives', 'url' => home_url('/blog/bland-ai-alternative/')),
						array('title' => 'Synthflow alternatives', 'url' => home_url('/blog/synthflow-alternative/')),
					),
					'more'  => array('title' => 'All comparisons', 'url' => home_url('/alternatives/')),
				),
				array(
					'label' => 'Tools',
					'links' => array(
						array('title' => 'Documentation', 'url' => 'https://docs.botphonic.ai/'),
						array('title' => 'ROI calculator', 'url' => home_url('/roi-calculator/')),
						array('title' => 'Contact us', 'url' => home_url('/contact/')),
					),
				),
			),
			'feature' => array(
				'badge' => 'Customer story',
				'title' => 'Serenity sees 150% ROI with Botphonic AI',
				'stats' => array(
					array('value' => '25%', 'label' => 'Boost in conversion'),
					array('value' => '50%', 'label' => 'Less call handling time'),
					array('value' => '20%', 'label' => 'Fewer human errors'),
					array('value' => '15%', 'label' => 'Higher agent satisfaction'),
				),
				'cta'   => array('title' => 'Read the story', 'url' => home_url('/success-stories/ecommerce-ai-monetization-case-study/')),
			),
			'footer'  => array(
				'text' => 'Developer docs and API reference live at docs.botphonic.ai',
				'link' => array('title' => 'Open the docs', 'url' => 'https://docs.botphonic.ai/'),
			),
		),
	);

	$nav = apply_filters('botphonic_header_nav', $nav);

	return $nav;
}

/**
 * Lucide-style icons (24px grid, 2px stroke).
 */
function botphonic_header_icon($name, $size = 20)
{
	static $paths = array(
		'chevron-down'   => '<path d="m6 9 6 6 6-6"/>',
		'arrow-right'    => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
		'plus'           => '<path d="M12 5v14"/><path d="M5 12h14"/>',
		'minus'          => '<path d="M5 12h14"/>',
		'menu'           => '<path d="M4 6h16"/><path d="M4 12h16"/><path d="M4 18h16"/>',
		'close'          => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
		'play'           => '<path d="M8 5v14l11-7z" fill="currentColor" stroke="none"/>',
		'wand'           => '<path d="M15 4V2"/><path d="M15 16v-2"/><path d="M8 9h2"/><path d="M20 9h2"/><path d="M17.8 11.8 19 13"/><path d="M15 9h.01"/><path d="M17.8 6.2 19 5"/><path d="m3 21 9-9"/><path d="M12.2 6.2 11 5"/>',
		'bot'            => '<path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/>',
		'grid'           => '<rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>',
		'phone-call'     => '<path d="M14 2a9 9 0 0 1 8 8"/><path d="M14 6a5 5 0 0 1 4 4"/><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"/>',
		'phone-incoming' => '<path d="M16 2v6h6"/><path d="m22 2-6 6"/><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"/>',
		'globe'          => '<circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
		'shield'         => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>',
		'message'        => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
		'smile'          => '<circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><path d="M9 9h.01"/><path d="M15 9h.01"/>',
		'headphones'     => '<path d="M3 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7a9 9 0 0 1 18 0v7a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3"/>',
		'calendar'       => '<path d="M8 2v4"/><path d="M16 2v4"/><path d="M3 10h18"/><path d="M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/>',
		'calendar-check' => '<path d="M8 2v4"/><path d="M16 2v4"/><path d="M3 10h18"/><path d="M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><path d="m9 16 2 2 4-4"/>',
		'trending'       => '<path d="m22 7-8.5 8.5-5-5L2 17"/><path d="M16 7h6v6"/>',
		'megaphone'      => '<path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>',
		'list'           => '<path d="M8 6h13"/><path d="M8 12h13"/><path d="M8 18h13"/><path d="M3 6h.01"/><path d="M3 12h.01"/><path d="M3 18h.01"/>',
		'bell'           => '<path d="M3 20a1 1 0 0 1-1-1v-1a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v1a1 1 0 0 1-1 1z"/><path d="M20 16a8 8 0 1 0-16 0"/><path d="M12 4v4"/><path d="M10 4h4"/>',
		'file'           => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/>',
		'users'          => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
		'info'           => '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>',
	);

	if (!isset($paths[$name])) {
		return '';
	}

	return sprintf(
		'<svg class="bph-icon bph-icon--%1$s" width="%2$d" height="%2$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>',
		esc_attr($name),
		(int) $size,
		$paths[$name]
	);
}

/**
 * Whether a nav URL points at the page being viewed.
 */
function botphonic_header_is_current($url)
{
	static $current = null;
	if (null === $current) {
		global $wp;
		$request = isset($wp->request) ? $wp->request : '';
		$current = untrailingslashit(home_url($request ? '/' . $request : '/'));
	}

	if (false !== strpos($url, '#')) {
		return false;
	}

	return untrailingslashit(strtok($url, '?')) === $current;
}

/**
 * Link attributes shared by every nav link: escaped href and aria-current.
 */
function botphonic_header_link_attrs($url)
{
	$attrs = 'href="' . esc_url($url) . '"';
	if (botphonic_header_is_current($url)) {
		$attrs .= ' aria-current="page"';
	}

	return $attrs;
}

/**
 * Whether any link inside a mega menu is the current page.
 */
function botphonic_header_menu_has_current($menu)
{
	foreach ($menu['columns'] as $column) {
		$links = array_merge(
			isset($column['items']) ? $column['items'] : array(),
			isset($column['links']) ? $column['links'] : array(),
			isset($column['more']) ? array($column['more']) : array()
		);
		foreach ($links as $link) {
			if (botphonic_header_is_current($link['url'])) {
				return true;
			}
		}
	}

	return false;
}

/**
 * Header assets. Runs after the child stylesheet (priority 25) so the
 * --bpg-* tokens are available.
 */
add_action('wp_enqueue_scripts', 'botphonic_header_enqueue_assets', 26);
function botphonic_header_enqueue_assets()
{
	wp_enqueue_style('botphonic-header', get_stylesheet_directory_uri() . '/assets/css/header.css', array('dtbwp_css_child'), botphonic_child_asset_ver('/assets/css/header.css'));
	wp_enqueue_script('botphonic-header', get_stylesheet_directory_uri() . '/assets/js/header.js', array(), botphonic_child_asset_ver('/assets/js/header.js'), array('in_footer' => true, 'strategy' => 'defer'));
}

/**
 * The header replaces Max Mega Menu's front-end output, so its styles,
 * icon fonts and scripts are not needed. The menu stays in the database.
 */
add_action('wp_enqueue_scripts', 'botphonic_header_dequeue_megamenu', 999);
function botphonic_header_dequeue_megamenu()
{
	foreach (array('megamenu', 'megamenu-genericons', 'megamenu-fontawesome', 'megamenu-fontawesome5') as $handle) {
		wp_dequeue_style($handle);
	}
	foreach (array('megamenu', 'megamenu-pro') as $handle) {
		wp_dequeue_script($handle);
	}
}
