<?php /* Template Name: White Label Email */ ?>
<?php get_header(); ?>
<?php wp_enqueue_style('cold-mail', get_stylesheet_directory_uri() . '/assets/css/cold-mail.css', array(), '1.0'); ?>
<style>
	.feature-img { width: 100%; border-radius: 16px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08); }
	.info-card { background: #ffffff; padding: 24px; border-radius: 16px; box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06); }
</style>

<!-- Hero Section -->
<section class="hero-section section-padded">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-10">
				<div class="text-center">
					<h1 class="title">Launch Your <span class="custom-gradient-text">White-Label Cold Email Platform</span> with Botphonic</h1>
					<p>Botphonic AI allows agencies, consultancies, and B2B service providers to offer the tool they need by full branding cold email outreach to their clients. Whether you want to automate personalized campaigns at scale or want to streamline follow-ups with AI-powered insights, our platform ensures that it’s easy to elevate your services.</p>
					<div>
						<?= do_shortcode('[contact-form-7 id="fdc94f2" title="Sign UP Form"]'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- WHAT IS White Label -->
<section class="section-padded">
	<div class="container">
		<div class="row align-items-center g-5">
			<div class="col-lg-6">
				<h2>Why Choose a <span class="custom-gradient-text">White-Label Cold Email</span> Solution?</h2>
				<p>A white-label cold email solution allows agencies to provide a fully branded email outreach platform to their clients without any need to develop the technology themselves. Botphonic provides all the powerful tools that you might need, for instance AI-driven personalization, dynamic merge fields, automated workflows, and detailed performance analytics, they are all customized to your agency’s identity.</p>
				<p>You can scale your client outreach with ease and deliver personalized emails that drives engagement and enhances client trust with a seamless branded experience.</p>
			</div>
			<div class="col-lg-6">
				<!-- Replace src with actual image -->
				<img src="https://botphonic.ai/wp-content/uploads/2026/01/Why-Choose-a-White-Label-Cold-Email-Solution.webp" alt="White-Label Cold Email Solution" class="feature-img">
			</div>
		</div>
	</div>
</section>

<!-- HOW BOTPHONIC DOES IT -->
<section class="section-padded" style="background:#f1f5f9;">
	<div class="container">
		<div class="row align-items-center g-5 flex-lg-row-reverse">
			<div class="col-lg-6">
				<h2>What Are the <span class="custom-gradient-text">Key Features of Botphonic's White-Label</span> Cold Email Platform?</h2>
				<p>Start offering a smart cold email campaigns platform to your clients, with AI-powered personalization, dynamic automation, and seamless reporting you can offer it all under your name.</p>
				<div class="info-card mb-3">
					<strong>1. Dynamic Personalization at Scale</strong>
					<p class="mt-2">Optimize AI-driven personalization to add client-specific details like name, company, and role into each email. Merge fields and dynamic data smartly ensures that every message feels like a tailored and unique experience.</p>
				</div>
				<div class="info-card mb-3">
					<strong>2. Fully Branded Client Dashboards & Reporting</strong>
					<p class="mt-2">Deliver a seamless and branded experience with customized dashboards and reports that reflects your agency’s identity. Your clients can see your logo, colors, and branding while accessing detailed campaign performance analytics, ensuring transparency and trust.</p>
				</div>
			</div>

			<div class="col-lg-6">
				<!-- IMAGE IDEA -->
				<img src="https://botphonic.ai/wp-content/uploads/2026/01/What-Are-the-Key-Features-of-Botphonic-White-Label-Cold-Email-Platform.webp" alt="Botphonic White-Label Cold Email Workflow" class="feature-img">
			</div>
		</div>
	</div>
</section>

<!-- BENEFITS SECTION -->
<section>
	<?php echo do_shortcode('[botphonic_iconbox]'); ?>
</section>

<!-- STATS SECTION -->
<?php
$stats = [
	[
		'value' => '+40%',
		'text' => 'Average increase in client email open rates using branded AI-personalized campaigns.'
	],
	[
		'value' => '2×',
		'text' => 'Higher reply rates for clients compared to generic or template-based outreach.'
	],
	[
		'value' => '50%',
		'text' => 'Fewer spam complaints thanks to contextual, AI-driven personalization.'
	],
	[
		'value' => '3×',
		'text' => 'More meetings booked per campaign using AI-powered white-label outreach.'
	],
];
?>
<section class="section-padded">
	<div class="container">
		<div class="text-center mb-4">
			<h2><span class="custom-gradient-text">White-Label Cold Email Success</span> by the Numbers</h2>
			<p>Agencies offering branded Botphonic AI solutions consistently see better engagement and higher client ROI compared to standard outreach tools.</p>
		</div>
		<div class="row g-4 text-center">
			<?php foreach($stats as $stat): ?>
			<div class="col-md-3">
				<div class="stat-card">
					<div class="h2"><?= $stat['value'] ?></div>
					<span><?= $stat['text'] ?></span>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>


<!-- Testimonial SECTION -->
<section>
	<?php echo do_shortcode('[botphonic_testimonials]'); ?>
</section>

<!-- COMPARISON TABLE SECTION -->
<?php
$benchmarkTable = [
	['feature' => 'Personalization Depth', 'manual' => 'Low', 'templates' => 'Very Limited', 'botphonic' => 'High & Contextual for Each Client'],
	['feature' => 'Time Required', 'manual' => 'Very High', 'templates' => 'Low', 'botphonic' => 'Minimal with AI Automation'],
	['feature' => 'Scalability', 'manual' => 'Not Scalable', 'templates' => 'Limited', 'botphonic' => 'Unlimited Campaigns'],
	['feature' => 'AI-Generated Openers', 'manual' => '❌', 'templates' => '❌', 'botphonic' => 'Custom for Every Prospect'],
	['feature' => 'Smart Fallback Logic', 'manual' => '❌', 'templates' => '❌', 'botphonic' => 'Never Broken, Always Professional'],
	['feature' => 'Reply Rate Optimization', 'manual' => 'Inconsistent', 'templates' => 'Low', 'botphonic' => 'Optimized by AI & Fully Branded'],
	['feature' => 'Agency Branding', 'manual' => '❌', 'templates' => '❌', 'botphonic' => 'Fully Branded Client Dashboards & Reports'],
];
?>
<section class="section-padded">
	<div class="container">
		<div class="text-center mb-4">
			<h2>Botphonic <span class="custom-gradient-text">White-Label vs Traditional Cold Email</span> Methods</h2>
			<p>See why offering AI-powered, branded email personalization outperforms manual and template-based outreach for agencies and their clients.</p>
		</div>
		<div class="table-wrapper" role="region" aria-label="Benchmark comparison table">
			<table class="benchmark-table">
				<thead>
					<tr>
						<th>Feature</th>
						<th>Manual Personalization</th>
						<th>Basic Templates</th>
						<th>Botphonic AI White-Label</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($benchmarkTable as $row): ?>
					<tr>
						<td><?= $row['feature'] ?></td>
						<td><?= $row['manual'] ?></td>
						<td><?= $row['templates'] ?></td>
						<td><?= $row['botphonic'] ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</section>


<!-- FAQ SECTION -->
<section>
	<?php echo do_shortcode('[botphonic_faq]'); ?>
</section>

<!-- CTA -->
<section>
	<?php echo do_shortcode('[cold_mail_cta title="Start Your White-Label Cold Email Journey Today!" subtitle="Take your agency to the next level with Botphonic’s white-label cold email solution."]'); ?>
</section>

<?php get_footer(); ?>