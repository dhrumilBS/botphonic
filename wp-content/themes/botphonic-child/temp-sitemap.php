<?php
/**
 * Template Name: Sitemap
 * Template Post Type: page
 *
 * Botphonic HTML sitemap.
 */

defined('ABSPATH') || exit;

get_header();

/**
 * Convert local paths and published WordPress URLs to their canonical permalinks.
 * Unknown paths are retained to support custom rewrite endpoints.
 * No URL-specific exclusions or remote HTTP calls are performed.
 */
$botphonic_sitemap_url = static function ($path) {
	if (!is_scalar($path)) {
		return '';
	}

	$path = trim((string) $path);

	if (preg_match('~^https?://~i', $path)) {
		$site_host = strtolower((string) wp_parse_url(home_url('/'), PHP_URL_HOST));
		$link_host = strtolower((string) wp_parse_url($path, PHP_URL_HOST));
		if ($site_host !== $link_host) {
			return '';
		}

		$url_path = (string) wp_parse_url($path, PHP_URL_PATH);
		$url_query = (string) wp_parse_url($path, PHP_URL_QUERY);
		$path = ltrim($url_path, '/');
		if ($url_query !== '') {
			$path .= '?' . $url_query;
		}
	}

	$parsed = wp_parse_url('/' . ltrim($path, '/'));
	if (!is_array($parsed)) {
		return '';
	}

	$slug = trim((string) ($parsed['path'] ?? ''), '/');
	$query = (string) ($parsed['query'] ?? '');

	// Only published WordPress pages may be linked through ?page_id=.
	if ($query !== '') {
		parse_str($query, $args);
		if (!isset($args['page_id']) || !is_scalar($args['page_id']) || !ctype_digit((string) $args['page_id'])) {
			return '';
		}
		$page = get_post((int) $args['page_id']);
		if (!$page || $page->post_type !== 'page' || $page->post_status !== 'publish') {
			return '';
		}
		$permalink = get_permalink($page);
		return $permalink ? esc_url_raw($permalink) : '';
	}

	if ($slug === '') {
		return home_url('/');
	}

	$url = home_url(user_trailingslashit('/' . $slug));
	$post_id = url_to_postid($url);
	if ($post_id) {
		$post = get_post($post_id);
		if (!$post || $post->post_status !== 'publish') {
			return '';
		}
		$permalink = get_permalink($post_id);
		return $permalink ? esc_url_raw($permalink) : '';
	}

	return esc_url_raw($url);
};

$botphonic_sitemap_solution_links = static function ($kind) {
	if (!function_exists('botphonic_solution_sitemap_links')) {
		return array();
	}
	$links = botphonic_solution_sitemap_links($kind);
	return is_array($links) ? $links : array();
};

$sitemap_sections = array(
	'Company' => array(
		'Home' => '',
		'Contact' => 'contact',
		'Pricing & Plans' => 'pricing',
	),
	'Industry' => $botphonic_sitemap_solution_links('industry'),
	'Use Cases' => $botphonic_sitemap_solution_links('use-case'),
	'Cold Email' => array(
		'Cold Email' => 'cold-email',
		'Cold Email Follow-Ups' => 'automatic-follow-up-emails',
		'Cold Email Personalization' => 'email-personalization',
		'White-Label Cold Email' => 'white-label-cold-mail',
		'Cold Email Warm-Up' => 'warm-up-mail',
		'Unified Inbox' => 'unified-mail',
		'Cold Email Sequences' => 'ai-cold-email-sequences',
		'AI Prospect Enrichment' => 'ai-prospect-enrichment',
		'Email Verifier' => 'email-verifier',
		'Gmail Tracking' => 'gmail-email-tracking',
	),
	'Extra' => array(
		'Onboarding' => 'onboarding',
		'Sales Automation' => 'sales-automation',
		'AI Agent Studio' => 'ai-agent-studio',
		'Visual Workflow Builder' => 'visual-workflow-builder',
		'Multilingual Voice AI Agents' => 'product/multilingual-voice-ai-agents',
		'ROI Calculator' => 'roi-calculator',
		'Email Infrastructure' => 'email-infrastructure',
	),
	'Resources' => array(
		'Blog' => 'blog',
		'Success Stories' => 'success-stories',
		'Alternatives & Comparisons' => 'alternatives',
		'Become a Partner' => 'become-a-partner',
		'Marketplace' => 'marketplace',
		'Affiliate Program' => 'affiliate-program',
		'Security' => 'security',
		'Integrations' => 'integrations',
		'Sitemap' => 'sitemap',
	),
	'Comparisons' => array(
		'CallHippo AI Alternative' => 'blog/callhippo-ai-alternative',
		'Verloop.io Alternative' => 'blog/verloop-io-alternative',
		'Smith.ai Alternative' => 'blog/smith-ai-alternative',
		'Aircall Alternative' => 'blog/aircall-alternative',
		'VoiceSpin Alternative' => 'blog/voicespin-alternative',
		'Goodcall Alternative' => 'blog/goodcall-alternative',
		'Lindy Alternative' => 'blog/lindy-alternative',
		'Voiceflow Alternative' => 'blog/voiceflow-alternative',
		'Retell AI Alternative' => 'blog/retell-ai-alternative',
		'Vapi Alternative' => 'blog/vapi-alternative',
		'Synthflow Alternative' => 'blog/synthflow-alternative',
		'Bland AI Alternative' => 'blog/bland-ai-alternative',
		'Aivo Alternative' => 'blog/aivo-alternative',
		'Twilio Alternative' => 'blog/twilio-alternative',
	),
	'Integration' => array(
		'HubSpot Integration' => 'hubspot-integration',
		'Zapier Integration' => 'zapier-integration',
		'Salesforce Integration' => 'salesforce-integration',
		'Zoho Integration' => 'zoho-integration',
		'WhatsApp Integration' => 'whatsapp-integration',
	),
	'Legal' => array(
		'Privacy Policy' => 'privacy-policy',
		'Terms & Conditions' => 'terms-condition',
		'Affiliate Terms' => 'affiliate-terms',
		'Disclaimer' => 'disclaimer',
	),
);

$render_sections = array();
foreach ($sitemap_sections as $title => $links) {
	if (!is_array($links)) {
		continue;
	}
	$items = array();
	$seen = array();
	foreach ($links as $label => $path) {
		$url = $botphonic_sitemap_url($path);
		if ($url === '' || isset($seen[$url])) {
			continue;
		}
		$seen[$url] = true;
		$items[] = array('label' => (string) $label, 'url' => $url);
	}
	if ($items) {
		$render_sections[$title] = $items;
	}
}
?>
<div class="sitemap-page">
	<div class="section-padded dark-bg--bg1 text-center">
		<h1>Botphonic Navigator</h1>
		<p class="m-2">Explore Botphonic's tools, solutions, comparisons and resources in one place.</p>
	</div>
	<div class="sitemap-search-wrap">
		<input type="search" id="sitemapSearch" placeholder="Search pages, industries, integrations, or comparisons…" aria-label="Search sitemap pages">
		<p id="sitemapNoResults" style="display:none;" role="status">No matching pages found.</p>
	</div>
	<div class="container section-padded">
		<ul class="section-list" role="list">
			<?php foreach ($render_sections as $section_title => $links) : ?>
			<li class="section-item">
				<h2><?php echo esc_html($section_title); ?></h2>
				<div class="section-body">
					<?php foreach ($links as $link) : ?>
					<a href="<?php echo esc_url($link['url']); ?>"><?php echo esc_html($link['label']); ?></a>
					<?php endforeach; ?>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<?php get_footer(); ?>
