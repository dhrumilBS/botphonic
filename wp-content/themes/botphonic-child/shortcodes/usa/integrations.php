<?php
add_shortcode('usa_integrations', 'usa_integrations_fn');
function usa_integrations_fn($attr)
{
	$d = shortcode_atts([
		'title' => 'Effortless Integration With Your Tech Stack',
		'subtitle' => 'We offer seamless interaction with your CRM, telephony, and automation tools, making it easy for you to sync customer data and streamline operations.',
	], $attr);

	ob_start(); ?>
<style>
	#integrate .wp-block-cover { min-height: 42rem; padding: 0; }
	.integrations-section { --primary-color: #7760f9; --text-dark: #111827; --text-muted: #6b7280; --bg-light: #f9fafb; --border-light: #e5e7eb; }
	.integrations-bg { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.25; }
	.integrations-bg img { height: 100%; }

	.integrations-content { position: relative; z-index: 2; max-width: 720px; margin: 0 auto; text-align: center; }
	.integrations-content h2 { font-size: clamp(1.8rem, 2.5vw, 2.6rem); color: var(--text-dark); margin-bottom: 1rem; }
	.integrations-content p { font-size: 1.05rem; color: var(--text-muted); margin-bottom: 1.5rem; }
	.integrations-section .position-relative { min-height: 650px; padding: 6rem 1rem; display: flex; align-items: center; justify-content: center; }
	.btn-outline { display: inline-block; padding: 0.85rem 1.6rem; border: 2px solid var(--primary-color); color: var(--primary-color); border-radius: 0.75rem; text-decoration: none; font-weight: 600; transition: all 0.25s ease; }
	.btn-outline:hover { background: var(--primary-color); color: #fff; }
	.integration-logos { position: absolute; inset: 0; pointer-events: none; z-index: 1; }
	.logos-wrap>.wp-block-group .wp-block-image { border-radius: .75rem; border: 1px solid #f3f4f6; background: #fff; box-shadow: 10px 30px 38px 0px rgba(0, 0, 0, .08); padding: .83rem; max-width: 4.5rem !important; min-width: 4.5rem !important; min-height: 4.5rem; max-height: 4.5rem; transform-style: preserve-3d; display: flex; align-items: center; justify-content: center; margin-bottom: 0; }
	.logos-wrap>.wp-block-group .wp-block-image { position: absolute; width: 72px; height: 72px; background: #fff; border-radius: 0.75rem; border: 1px solid var(--border-light); box-shadow: 0 20px 35px rgba(0, 0, 0, 0.08); display: flex; align-items: center; justify-content: center; animation: float 3s linear infinite; will-change: transform; }
	.logos-wrap>.wp-block-group .wp-block-image:nth-child(8n) { animation-delay: 290ms; }
	.logos-wrap>.wp-block-group .wp-block-image:nth-child(8n-1) { animation-delay: 050ms; }
	.logos-wrap>.wp-block-group .wp-block-image:nth-child(8n-2) { animation-delay: 100ms; }
	.logos-wrap>.wp-block-group .wp-block-image:nth-child(8n-3) { animation-delay: 370ms; }
	.logos-wrap>.wp-block-group .wp-block-image:nth-child(8n-5) { animation-delay: 200ms; }
	.logos-wrap>.wp-block-group .wp-block-image:nth-child(8n-6) { animation-delay: 540ms; }
	.logos-wrap>.wp-block-group .wp-block-image:nth-child(8n-7) { animation-delay: 270ms; }
	.logos-wrap .wp-block-image img { margin: 0; width: 5rem !important; max-height: 3.33rem; }
	@media screen and (min-width:782px) {
		#integrate .wp-block-cover__inner-container { height: 100%; min-height: 100%; position: absolute; display: flex; align-items: center; justify-content: center; }
		.logos-wrap { inset: 0; max-width: 100% !important; position: absolute; z-index: 0; }
		.logos-wrap>.wp-block-group { width: 100%; height: 100%; padding: 10px; box-sizing: border-box; transform-style: preserve-3d; pointer-events: none; transition: opacity 3s; position: relative; }
		.logos-wrap>.wp-block-group .wp-block-image { transform-style: preserve-3d; transition: 1s; position: absolute; }
		.logos-wrap>.wp-block-group .wp-block-image.ac { right: 21.4%; top: 4.5rem; }
		.logos-wrap>.wp-block-group .wp-block-image.clickup { left: 36%; top: 0; }
		.logos-wrap>.wp-block-group .wp-block-image.asana { bottom: .5%; top: auto; right: 35.7%; }
		.logos-wrap>.wp-block-group .wp-block-image.excel { right: 7%; bottom: 38.5%; }
		.logos-wrap>.wp-block-group .wp-block-image.gradient { top: 25%; right: 0; }
		.logos-wrap>.wp-block-group .wp-block-image.hubspot { top: 0; right: 7%; }
		.logos-wrap>.wp-block-group .wp-block-image.intercom { bottom: 13%; right: 7%; }
		.logos-wrap>.wp-block-group .wp-block-image.slack { left: 7.5%; top: 0; }
		.logos-wrap>.wp-block-group .wp-block-image.zapier { bottom: 25.5%; left: 14.8%; }
		.logos-wrap>.wp-block-group .wp-block-image.zoho { left: 7.5%; bottom: .6%; }
		.logos-wrap>.wp-block-group .wp-block-image.zendesk { left: 29%; bottom: 13%; }
		.logos-wrap>.wp-block-group .wp-block-image.salesforce { left: .5%; bottom: 37.5%; }
		.logos-wrap>.wp-block-group .wp-block-image.pipedrive { bottom: 25.5%; right: 21.2%; }
		.logos-wrap>.wp-block-group .wp-block-image.monday { top: 25%; left: 14.5%; }	
	}

	@keyframes scroll {
		from { transform: translateX(0); }
		to { transform: translateX(-50%); }	
	}

	@keyframes float {
		0%,
		100% { transform: translateY(0); }
		50% { transform: translateY(-12px); }	
	}

	@media (max-width: 768px) {
		.integrations-section .position-relative { padding: 0; min-height: 100%; }
		.logos-wrap { display: none !important; }
	}
</style>
<section id="integrations" class="integrations-section section-padded">
	<div class="container">
		<div class="position-relative">
			<div class="integrations-bg">
				<img src="https://botphonic.ai/wp-content/themes/botphonic-child/assets/img/dots.webp" alt="pattern" />
			</div>
			<div class="integrations-content">
				<h2><?= $d['title']; ?></h2>
				<p><?= $d['subtitle']; ?></p>
				<a href="/integrations/" class="btn-outline"> See all integrations </a>
			</div>
			<div class="integration-logos logos-wrap">
				<div class="wp-block-group integrations-row is-nowrap is-layout-flex wp-container-core-group-is-layout-6c531013 wp-block-group-is-layout-flex">
					<figure class="wp-block-image size-large ac"><img width="54" height="54" src="https://botphonic.ai/wp-content/themes/botphonic-child/assets/img/activecampaign.svg" alt="activecampaign"></figure>
					<figure class="wp-block-image size-large asana"><img loading="lazy" width="155" height="144" src="https://botphonic.ai/wp-content/themes/botphonic-child/assets/img/Asana-Symbol-Coral-SVG.svg" alt="asana"></figure>
					<figure class="wp-block-image size-large salesforce"><img loading="lazy" width="58" height="40" src="https://botphonic.ai/wp-content/themes/botphonic-child/assets/img/salesforce.svg" alt="salesforce"></figure>
					<figure class="wp-block-image size-large slack"><img loading="lazy" width="54" height="54" src="https://botphonic.ai/wp-content/themes/botphonic-child/assets/img/slack.svg" alt="slack"></figure>
					<figure class="wp-block-image size-large clickup"><img loading="lazy" width="49" height="58" src="https://botphonic.ai/wp-content/themes/botphonic-child/assets/img/clickup-2.svg" alt="clickup"></figure>
					<figure class="wp-block-image size-large excel"><img loading="lazy" width="40" height="54" src="https://botphonic.ai/wp-content/themes/botphonic-child/assets/img/excel.svg" alt="excel"></figure>
					<figure class="wp-block-image size-large intercom"><img loading="lazy" width="54" height="54" src="https://botphonic.ai/wp-content/themes/botphonic-child/assets/img/intercom.svg" alt="intercom"></figure>
					<figure class="wp-block-image size-large pipedrive"><img loading="lazy" width="54" height="54" src="https://botphonic.ai/wp-content/themes/botphonic-child/assets/img/pipedrive.svg" alt="pipedrive"></figure>
					<figure class="wp-block-image size-large monday"><img loading="lazy" width="58" height="36" src="https://botphonic.ai/wp-content/themes/botphonic-child/assets/img/monday.svg" alt="monday"></figure>
					<figure class="wp-block-image size-large zoho"><img loading="lazy" width="54" height="55" src="https://botphonic.ai/wp-content/themes/botphonic-child/assets/img/zoho.svg" alt="zoho"></figure>
					<figure class="wp-block-image size-large zendesk"><img loading="lazy" width="58" height="41" src="https://botphonic.ai/wp-content/themes/botphonic-child/assets/img/zendesk.svg" alt="zendesk"></figure>
					<figure class="wp-block-image size-large hubspot"><img loading="lazy" width="54" height="54" src="https://botphonic.ai/wp-content/themes/botphonic-child/assets/img/hubspot.svg" alt="hubspot"></figure>
					<figure class="wp-block-image size-large zapier"><img loading="lazy" width="54" height="54" src="https://botphonic.ai/wp-content/themes/botphonic-child/assets/img/zapier.svg" alt="zapier"></figure>
					<figure class="wp-block-image size-large gradient"><img loading="lazy" width="54" height="39" src="https://botphonic.ai/wp-content/themes/botphonic-child/assets/img/gradient-logo.svg" alt="make"></figure>
				</div>
			</div>
		</div>
	</div>
</section>

<?php return ob_get_clean();
}
