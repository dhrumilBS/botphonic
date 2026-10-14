<?php /* Template name: Sitemap */
get_header();

/*
 * Sitemap links, grouped by section: 'Label' => 'page-slug'.
 *
 * - Slugs are turned into full URLs with home_url(), so the same list works on local and live.
 * - Use '' for the homepage. Full URLs (https://...) are output as-is, e.g. for external links.
 * - Industry and Use Cases come from botphonic_solution_page_registry(), so add those pages there.
 */
$sitemap_sections = [
	'Company' => [
		'Home' => '',
		'Contact' => 'contact',
		'Pricing & Plans' => 'pricing',
	],

	'Industry' => botphonic_solution_sitemap_links('industry'),

	'Use Cases' => botphonic_solution_sitemap_links('use-case'),

	'Cold Email' => [
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
	],

	'Extra' => [
		'Customer Retention' => 'customer-retention',
		'Onboarding' => 'onboarding',
		'Sales Automation' => 'sales-automation',
		'Audience Segmentation' => 'audience-segmentation',
		'Cross-sell & Upsell' => 'cross-sell-upsell',
		'SMS Marketing' => 'sms-marketing',
		'AI Agent Studio' => 'ai-agent-studio',
		'Visual Workflow Builder' => 'visual-workflow-builder',
		'Multilingual Voice AI agents' => 'multilingual-voice-ai-agents',
		'ROI Calculator' => 'roi-calculator',
		'Sequence Score' => 'sequence-score',
		'Email Infrastructure' => 'email-infrastructure',
	],

	'Resources' => [
		'Blog' => 'blog',
		'Success Stories' => 'success-stories',
		'Become a Partner' => 'become-a-partner',
		'Marketplace' => 'marketplace',
		'Affiliate Program' => 'affiliate-program',
		'Security' => 'security',
		'Integrations' => 'integrations',
		'Sitemap' => 'sitemap',
	],

	// Comparison articles are blog posts, so they live under /blog/.
	'Comparisons' => [
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
	],

	'Integration' => [
		'Hubspot Integration' => 'hubspot-integration',
		'Zapier Integration' => 'zapier-integration',
		'Salesforce Integration' => 'salesforce-integration',
		'Zoho Integration' => 'zoho-integration',
		'Whatsapp Integration' => 'whatsapp-integration',
	],

	'Legal' => [
		'Privacy Policy' => 'privacy-policy',
		'Terms & Condition' => 'terms-condition',
		'Affiliate Terms' => 'affiliate-terms',
		'Disclaimer' => 'disclaimer',
	],
];

// Build an environment-aware URL from a slug. Absolute URLs pass through unchanged.
$sitemap_url = function ($path) {
	if (preg_match('#^https?://#i', $path)) {
		return $path;
	}

	return home_url(user_trailingslashit('/' . trim($path, '/')));
};
?>

<div class="sitemap-page">
	<div class="section-padded dark-bg--bg1 text-center">
		<h1>Botphonic Navigator</h1>
		<p class="m-2">This is your comprehensive navigator of tools and resources across our website. Solely designed for enhancing efficiency and <br> clarity that connect you to everything that drives Botphonic forward.</p>
	</div>
	<div class="sitemap-search-wrap">
		<input type="search" id="sitemapSearch" placeholder="Search pages, industries, integrations, or comparisons…" aria-label="Search sitemap pages">
		<p id="sitemapNoResults" style="display:none;">No matching pages found.</p>
	</div>

	<div class="container section-padded">
		<ul class="section-list" role="list">
			<?php foreach ($sitemap_sections as $section_title => $links) : ?>
			<li class="section-item">
				<h3><?php echo esc_html($section_title); ?></h3>
				<div class="section-body">
					<?php foreach ($links as $label => $path) : ?>
					<a href="<?php echo esc_url($sitemap_url($path)); ?>">
						<?php echo esc_html($label); ?>
					</a>
					<?php endforeach; ?>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>

<?php get_footer(); ?>
