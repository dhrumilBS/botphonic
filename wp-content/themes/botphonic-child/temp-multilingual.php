<?php
/* Template Name: Multilingual */
?>
<?php get_header(); ?>

<!-- HERO -->
<section class="section-padded hero">
	<div class="hero-bg"></div>
	<div class="hero-grid"></div>
	<div class="hero-content">
		<div class="hero-badge"> <span class="badge-dot"></span> Real-Time Speech Intelligence </div>
		<h1>Multilingual Voice AI Platform for <span class="grad-text">Global Customer Conversations</span></h1>
		<p class="hero-sub">Easily deploy production ready multilingual AI voice assistant that understands multiple languages and regional accents instantly.</p>
		<div class="hero-actions">
			<a class="btn-primary" href="https://app.botphonic.ai/register" target="_blank">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
					<path d="M12 2L2 7l10 5 10-5-10-5z" />
					<path d="M2 17l10 5 10-5" />
					<path d="M2 12l10 5 10-5" />
				</svg>
				Start Building Free →
			</a>
		</div>
	</div>
</section>

<!-- ENTERPRISE TRUST -->
<div class="trust-section">
	<div class="section-inner">
		<div class="trust-header">
			<p>TRUSTED BY FORWARD-THINKING ENTERPRISES WORLDWIDE</p>
		</div>

		<div class="trust-stats">
			<div class="trust-stat">
				<div class="trust-stat-icon">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M22 12h-4l-3 9L9 3l-3 9H2" />
					</svg>
				</div>
				<div class="trust-stat-text"> <strong>99.99% Uptime</strong> <span>Enterprise reliability</span> </div>
			</div>
			<div class="trust-stat">
				<div class="trust-stat-icon"> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<circle cx="12" cy="12" r="10" />
					<path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z" />
					</svg> </div>
				<div class="trust-stat-text"> <strong>Enterprise Security</strong> <span>SOC 2 & GDPR compliant</span> </div>
			</div>
			<div class="trust-stat">
				<div class="trust-stat-icon"> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<rect x="3" y="11" width="18" height="11" rx="2" />
					<path d="M7 11V7a5 5 0 0110 0v4" />
					</svg> </div>
				<div class="trust-stat-text"> <strong>Real-Time Processing</strong> <span>Sub-200ms latency</span> </div>
			</div>
			<div class="trust-stat">
				<div class="trust-stat-icon"> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
					</svg> </div>
				<div class="trust-stat-text"> <strong>Global Infrastructure</strong> <span>Edge nodes worldwide</span> </div>
			</div>
		</div>
	</div>
</div>


<?php

$languages = [
	["name" => "Afrikaans", "region" => "South Africa", "code" => "af"],
	["name" => "Arabic", "region" => "Saudi Arabia, UAE, Egypt", "code" => "ar"],
	["name" => "Bengali", "region" => "India, Bangladesh", "code" => "bn-IN"],
	["name" => "Chinese, Cantonese", "region" => "Hong Kong, Guangdong", "code" => "yue"],
	["name" => "Chinese, Mandarin", "region" => "China, Taiwan, Singapore", "code" => "zh"],
	["name" => "Croatian", "region" => "Croatia", "code" => "hr"],
	["name" => "Czech", "region" => "Czech Republic", "code" => "cs"],
	["name" => "Danish", "region" => "Denmark", "code" => "da"],
	["name" => "Dutch", "region" => "Netherlands, Belgium", "code" => "nl"],
	["name" => "English", "region" => "US, UK, India, Australia", "code" => "en"],
	["name" => "Filipino", "region" => "Philippines", "code" => "fil"],
	["name" => "Finnish", "region" => "Finland", "code" => "fi"],
	["name" => "French", "region" => "France, Canada, West Africa", "code" => "fr"],
	["name" => "German", "region" => "Germany, Austria, Switzerland", "code" => "de"],
	["name" => "Gujarati", "region" => "India", "code" => "gu-IN"],
	["name" => "Hebrew", "region" => "Israel", "code" => "he"],
	["name" => "Hindi", "region" => "India", "code" => "hi"],
	["name" => "Indonesian", "region" => "Indonesia", "code" => "id"],
	["name" => "Italian", "region" => "Italy, Switzerland", "code" => "it"],
	["name" => "Japanese", "region" => "Japan", "code" => "ja"],
	["name" => "Kannada", "region" => "India", "code" => "kn-IN"],
	["name" => "Korean", "region" => "South Korea", "code" => "ko"],
	["name" => "Malay", "region" => "Malaysia, Brunei", "code" => "ms"],
	["name" => "Malayalam", "region" => "India", "code" => "ml-IN"],
	["name" => "Marathi", "region" => "India", "code" => "mr-IN"],
	["name" => "Norwegian Bokmål", "region" => "Norway", "code" => "nb"],
	["name" => "Persian", "region" => "Iran", "code" => "fa"],
	["name" => "Polish", "region" => "Poland", "code" => "pl"],
	["name" => "Portuguese", "region" => "Brazil, Portugal", "code" => "pt"],
	["name" => "Romanian", "region" => "Romania", "code" => "ro"],
	["name" => "Russian", "region" => "Russia, CIS", "code" => "ru"],
	["name" => "Serbian", "region" => "Serbia", "code" => "sr"],
	["name" => "Spanish", "region" => "Spain, Mexico, Latin America", "code" => "es"],
	["name" => "Swedish", "region" => "Sweden", "code" => "sv"],
	["name" => "Tamil", "region" => "India, Sri Lanka", "code" => "ta-IN"],
	["name" => "Telugu", "region" => "India", "code" => "te-IN"],
	["name" => "Thai", "region" => "Thailand", "code" => "th"],
	["name" => "Turkish", "region" => "Turkey", "code" => "tr"],
	["name" => "Ukrainian", "region" => "Ukraine", "code" => "uk"],
	["name" => "Urdu", "region" => "Pakistan, India", "code" => "ur"],
	["name" => "Vietnamese", "region" => "Vietnam", "code" => "vi"],
	["name" => "Zulu", "region" => "South Africa", "code" => "zu"],
];
?>


<section class="lang-section" id="languages">
	<div class="container">
		
		<div class="text-center mb-5 mx-auto" style="max-width: 850px;">
			<div class="section-label">Multilingual Support</div>
			<h2 class="fw-bold display-6">Why Accent-Intelligent Multilingual AI Voice Platform for Global Enterprises</h2>
			<p class="lead text-muted mt-3">Expand into global markets without rebuilding your whole voice systems. With 20 languages and natural accent recognition, Botphonic has built-for-scale infrastructure.</p>
		</div>
		 
		<!-- Grid -->
		<div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-0">
			<?php foreach($languages as $language) { ?>
			<div class="col lang-grid-col">
				<div class="lang-card">
					<div class="lang-card-name"> <?= $language['name']; ?></div>
					<div class="lang-card-region"><?= $language['region']; ?></div>
					<span class="lang-code-badge"><?= $language['code']; ?></span>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</section>



<div class="mb-4">
	<?php echo do_shortcode('[botphonic_faq]'); ?>
</div>

<?= do_shortcode('[cold_mail_cta title="Build AI voice agents <br> that speak every market" text="Create accent-aware4 and real-timemultilingual voice assistants that is designed for scale." btn_text="Get Started with Multilingual Voice AI"]') ?>

<?php get_footer(); ?>