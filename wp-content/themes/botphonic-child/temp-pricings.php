<?php
/* Template Name: Pricing Plans 
 */
get_header();
wp_enqueue_style('pricing');
wp_enqueue_style('botphonic-faqs-css');
?>
<style>
	@media screen and (max-width:1300px) {
		.container { max-width: 100%; }
	}
	.toggle-btn{ cursor: pointer; }
	.toggle-switch{ width: 40px; height: 24px; }
	.toggle-switch .billing-toggle{ width: 16px; height: 16px; top: 50%; left: 20px; transform: translateY(-50%); transition: left 0.3s; }

	.title span { background-image: linear-gradient(304deg, #FF37DF 21.73%, #6E00FF 56.6%); }
	.hero-section { background-image: radial-gradient(ellipse at 50% -10%, #ffffff 0%, transparent 85%), radial-gradient(circle at 100% 5%, #D6E7FF, transparent 0%), radial-gradient(circle at 10% 8%, #F9E4FE, transparent 11%), linear-gradient(#D6E7FF 0%, #f3f9fe 20%); }
	#shortPlanContainer .short-plan-card { border: 2px solid #fff;  display: flex; flex-direction: column; background: linear-gradient(0deg, #f5f6fe, #fff2); border-radius: 20px; padding: 1.5rem; box-shadow: 0 0 12px rgba(0, 0, 0, 0.05); position: relative; height: 100%; min-width: 300px; }

	#shortPlanContainer{padding-bottom: 8px;}
	#shortPlanContainer::-webkit-scrollbar { background: #f3f9fe; width: 8px; border-radius: 10px; }
	#shortPlanContainer::-webkit-scrollbar-track { border-radius: 10px;}
	#shortPlanContainer::-webkit-scrollbar-thumb { background: #919191; border-radius: 10px; border: 4px solid #f3f9fe; }
	#shortPlanContainer::-webkit-scrollbar-thumb:hover { background: #707078; }

	span.symbol{ margin-right: -0.1em; }
	.perMonth{ margin-left: -0.2em; }



	.short-plan-card .short-plan-header{ min-height: 116px; }

	#shortPlanContainer .short-plan-card.highlited { border-color: #c6f !important; position: relative; --accent:  #c6f }
	#shortPlanContainer .short-plan-card.highlited .highlted-badge { position: absolute; left:0; right:0; top:0; text-align: center; transform: translateY(-50%);  }
	#shortPlanContainer .short-plan-card.highlited .highlted-badge span { background: linear-gradient(304deg, #FF37DF 21.73%, #6E00FF 56.6%); color: #fff; padding: 4px 8px; font-weight: 600; }

	#shortPlanContainer .short-plan-card:hover { border-color: #4f46e5; }
	#shortPlanContainer .short-plan-strike { text-decoration: line-through; color: #888; font-size: 0.9rem; }
	#shortPlanContainer .short-plan-final { font-weight: 500; font-size: 1.2rem; }
	#shortPlanContainer .short-plan-badge { background: #28a745; color: white; padding: 4px 10px; border-radius: 12px; display: inline-block; font-size: 0.8rem; margin-top: 5px; }
	#shortPlanContainer .short-plan-main-features { list-style: none; padding: 0; flex-grow: 1; margin: 1rem 0 0; }
	#shortPlanContainer .short-plan-feature-item { display: flex; align-items: baseline; font-size: 0.95rem; padding: 6px 0; color: #3d3d3d; }
	#shortPlanContainer .short-plan-feature-item .check-icon { display: inline-block; width: 18px; height: 18px; margin-right: 4px; background-color: var(--accent); color: #fff; font-size: 0.75rem; font-weight: bold; text-align: center; line-height: 18px; border-radius: 50%; flex-shrink: 0; }
	#shortPlanContainer .short-plan-btn { margin-top: 10px; padding: 6px 14px; border-radius: 6px; cursor: pointer; text-align: center; }
</style>

<section class="hero-section section-padded pb-0">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="text-center">
					<div class="heading text-center">
						<h1 class="title">Botphonic AI Call Assistant At <span class="text-light-gradient">Unbeatable
							Prices</span> </h1>
						<p>Flexible pricing for the diverse business needs, enhancing business growth to the next level.
						</p>
					</div>
				</div>
			</div>
		</div>

		<div class="kpis-point">
			<ul class="list-unstyled d-flex flex-wrap justify-content-center">
				<li class="d-flex align-items-center me-4 mb-3">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/tick.svg" alt="Phone Icon"
						 class="me-2">
					<span>Unlimited Calls</span>
				</li>
				<li class="d-flex align-items-center me-4 mb-3">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/tick.svg" alt="AI Icon"
						 class="me-2">
					<span>AI-Powered</span>
				</li>
				<li class="d-flex align-items-center me-4 mb-3">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/tick.svg" alt="Support Icon"
						 class="me-2">
					<span>24/7 Support</span>
				</li>
			</ul>
		</div>

		<div class="hero-pricing section-padded">
			<div class="container">

				<!-- Short Plan Controls -->
				<div class="short-plan-header billing-currency-wrap text-center">

					<div class="d-flex align-items-center justify-content-center gap-2">
						<div class="d-flex align-items-center fw-semibold small gap-2">
							<div id="monthlyBtn" data-period="Monthly" class="text-dark text-decoration-none toggle-btn">Monthly</div>

							<div class="position-relative bg-dark rounded-pill toggle-btn toggle-switch">
								<span id="billingToggle" class="position-absolute bg-white rounded-circle billing-toggle"></span>
							</div>

							<div id="yearlyBtn" data-period="Yearly" class="text-dark text-decoration-none d-flex align-items-center gap-2 toggle-btn">
								Yearly
								<div class="badge-box title d-inline-flex" >
									<span class="badge">Saved Upto 20%</span>
								</div>
							</div>
						</div>
					</div>

					<div class="currency-controls mx-auto mt-3 mt-md-0">
						<label for="short-currency-select">Choose Currency</label>
						<select id="short-currency-select" class="currency-select short-currency-select">
							<option value="USD" selected="">USD</option>
							<option value="INR">INR</option>
						</select>
					</div>
				</div>

				<!-- Short Plans Display -->

				<div id="shortPlanContainer" class="short-plan-list mobile-wrap row flex-sm-nowrap  mt-4 g-4 gm-md-0"><div class="col"><div class="short-plan-card pricing-card">
					<div class="short-plan-header">

						<div class="short-plan-title h4">Starter</div>
						<div class="short-plan-final"> <span class="symbol">$</span> <span class="yearlyPrice h2">22</span> <small class="perMonth">/mo</small></div>

					</div>
					<ul class="short-plan-main-features"><li class="short-plan-feature-item"><span class="check-icon">✔</span> 50 mins</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> 5 Concurrent Calls</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> Voice API, LLM, transcriber costs</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> Unlimited Assistants</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> API &amp; Integrations</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> Real-Time Booking, Human Transfer &amp; More Actions</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> Voice AI Courses &amp; Community Support</li></ul><a href="https://app.botphonic.ai/register/" target="_blank" class="theme-btn short-plan-btn">Select</a></div></div><div class="col"><div class="short-plan-card pricing-card highlited">
					<div class="short-plan-header">
						<div class="highlted-badge"> <span class="rounded-pill px-3 py-1 "> Recommnded </span> </div>
						<div class="short-plan-title h4">Pro</div>
						<div class="short-plan-final"> <span class="symbol">$</span> <span class="yearlyPrice h2">290</span> <small class="perMonth">/mo</small></div>
						<span class="d-inline short-plan-strike old-price"><span class="symbol">$</span> <span class="monthlyPrice">350/mo</span></span>
					</div>
					<ul class="short-plan-main-features"><li class="short-plan-feature-item"><span class="check-icon">✔</span> 2,000 mins, then $0.13/min</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> 25 Concurrent Calls</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> 8,000 Custom Workflows</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> Everything in Starter</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> Team Access</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> Support via Ticketing</li></ul><a href="https://app.botphonic.ai/register/" target="_blank" class="theme-btn short-plan-btn">Select</a></div></div><div class="col"><div class="short-plan-card pricing-card">
					<div class="short-plan-header">

						<div class="short-plan-title h4">Growth</div>
						<div class="short-plan-final"> <span class="symbol">$</span> <span class="yearlyPrice h2">580</span> <small class="perMonth">/mo</small></div>
						<span class="d-inline short-plan-strike old-price"><span class="symbol">$</span> <span class="monthlyPrice">700/mo</span></span>
					</div>
					<ul class="short-plan-main-features"><li class="short-plan-feature-item"><span class="check-icon">✔</span> 4,000 mins, then $0.12/min</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> 50 Concurrent Calls</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> 42,000 Custom Workflows</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> 25 Subaccounts</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> Everything in Pro</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> Rebilling</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> Access to New Features</li></ul><a href="https://app.botphonic.ai/register/" target="_blank" class="theme-btn short-plan-btn">Select</a></div></div><div class="col"><div class="short-plan-card pricing-card">
					<div class="short-plan-header">

						<div class="short-plan-title h4">Agency</div>
						<div class="short-plan-final"> <span class="symbol">$</span> <span class="yearlyPrice h2">1020</span> <small class="perMonth">/mo</small></div>
						<span class="d-inline short-plan-strike old-price"><span class="symbol">$</span> <span class="monthlyPrice">1150/mo</span></span>
					</div>
					<ul class="short-plan-main-features"><li class="short-plan-feature-item"><span class="check-icon">✔</span> 6,000 mins, then $0.12/min</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> 80 Concurrent Calls</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> 100,000 Custom Workflows</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> Unlimited Subaccounts</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> White Label Platform</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> 30-Day Onboarding &amp; Private Slack Channel</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> Support via Ticketing</li></ul><a href="https://app.botphonic.ai/register/" target="_blank" class="theme-btn short-plan-btn">Select</a></div></div><div class="col"><div class="short-plan-card pricing-card">
					<div class="short-plan-header">

						<div class="short-plan-title h4">Enterprise</div>  
						<span class="short-plan-final">Custom Pricing</span>
					</div>
					<ul class="short-plan-main-features"><li class="short-plan-feature-item"><span class="check-icon">✔</span> Volume-based Price, as low as $0.08/min</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> SIP Trunk Integration</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> Guaranteed Uptime (SLA)</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> Custom Integrations</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> 200+ Concurrent Calls</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> Compliance (SOC2, HIPAA, GDPR)</li><li class="short-plan-feature-item"><span class="check-icon">✔</span> Enterprise Onboarding, Training, Support</li></ul><a href="https://app.botphonic.ai/register/" target="_blank" class="theme-btn short-plan-btn">Select</a></div></div>
				</div>







				<div class="small-note text-center text-md-end small mt-2">
					<p class="text-muted ">Local taxes (VAT, GST, etc.) will be charged in addition to the prices mentioned.
					</p>
				</div>

			</div>
		</div>
	</div>
</section>
<style>
	.stack-container { padding: 20px; border-radius: 12px; border: 1px solid rgba(0, 0, 0, 0.06); }
	.icon-row { display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; margin-bottom: 30px; }
	.icon-row img { width: 40px; height: 40px; object-fit: contain; filter: grayscale(0.2); }

	.stack-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-bottom: 40px; }
	.stack-box { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06); text-align: center; }
	.stack-box h4 { margin: 10px 0 8px; font-size: 1.1rem; color: #444; }
	.stack-box p { font-size: 0.9rem; color: #666; }

	.benefits { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; }
	.benefit-card { background: #f3f4f610; border: 1px solid #f3f4f6; padding: 20px; border-radius: 10px; }
	.benefit-card h3 { font-size: 1.1rem; margin-bottom: 10px; color: #222; }
	.benefit-card p { font-size: 0.95rem; color: #555; }
</style>

<section class="section-padded d-none">
	<div class="container">
		<div class="stack-container">
			<div class="icon-row d-none">
				<img src="https://upload.wikimedia.org/wikipedia/commons/6/6e/Eleven_Labs_logo.png" alt="Eleven Labs" />
				<img src="https://upload.wikimedia.org/wikipedia/commons/d/db/Descript_logo.png" alt="Descript" />
				<img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" alt="Google" />
				<img src="https://upload.wikimedia.org/wikipedia/commons/0/04/OpenAI_Logo.svg" alt="OpenAI" />
				<img src="https://upload.wikimedia.org/wikipedia/commons/3/3b/Twilio-logo-red.svg" alt="Twilio" />
			</div>

			<div class="stack-grid">
				<div class="stack-box">
					<h4>Platform</h4>
					<p>No-code builder, workflows, actions</p>
				</div>
				<div class="stack-box">
					<h4>Voice API</h4>
					<p>Tested voices in 30+ languages</p>
				</div>
				<div class="stack-box">
					<h4>LLM</h4>
					<p>OpenAI GPT-4o-based, low latency</p>
				</div>
				<div class="stack-box">
					<h4>Transcription API</h4>
					<p>Deepgram-powered speech-to-text</p>
				</div>
				<div class="stack-box">
					<h4>Telephony</h4>
					<p>Included for Synthflow numbers</p>
				</div>
			</div>

			<div class="benefits">
				<div class="benefit-card">
					<h3>
						Voice AI Bundle: Maximum Performance
					</h3>
					<p>Get full access to OpenAI LLM, voice API, and transcription tools for top-tier performance at a cost-effective rate.</p>
				</div>
				<div class="benefit-card">
					<h3>
						Plug & Play Setup

					</h3>
					<p>Skip trial-and-error with pre-tested, high-performing tech stack that saves time and money.</p>
				</div>
				<div class="benefit-card">
					<h3>
						Use Your Own API Keys
					</h3>
					<p>Integrate your API keys with complete control and customization using enterprise-grade features.</p>
				</div>
			</div>
		</div>
	</div>
</section>

<style>
	.bg-gradient-primary {
		background: linear-gradient(304deg, #0c324e 21.73%, #072032 56.6%);
	}
</style>

<section class="section-padded">
	<div class="container">
		<div class="text-center mx-auto mb-5" style="max-width: 700px;">
			<h2 class="display-5 fw-bold mb-3">Why Choose Botphonic?</h2>
			<p class="lead text-muted">Advanced AI technology meets enterprise-grade reliability</p>
		</div>

		<div class="row justify-content-center text-center g-4">
			<!-- Feature 1 -->
			<div class="col-md-4">
				<div class="mx-auto mb-4 rounded-circle d-flex align-items-center justify-content-center bg-gradient-primary" style="width: 64px; height: 64px;">
					<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
					</svg>
				</div>
				<h5 class="fw-semibold mb-2">Turnkey Solution</h5>
				<p class="text-muted">Pre-built software that requires minimal customization, deploy immediately and start your business services without delay. Implementation does not require technical support.  
				</p>
			</div>

			<!-- Feature 2 -->
			<div class="col-md-4">
				<div class="mx-auto mb-4 rounded-circle d-flex align-items-center justify-content-center bg-gradient-primary" style="width: 64px; height: 64px;">
					<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z" />
					</svg>
				</div>
				<h5 class="fw-semibold mb-2">Volume Discounting Model</h5>
				<p class="text-muted">Best AI assistant software that saves huge costs because this price model reduces average expenses as the volume of the services increases.</p>
			</div>

			<!-- Feature 3 -->
			<div class="col-md-4">
				<div class="mx-auto mb-4 rounded-circle d-flex align-items-center justify-content-center bg-gradient-primary" style="width: 64px; height: 64px;">
					<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M3 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7a9 9 0 0 1 18 0v7a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3" />
					</svg>
				</div>
				<h5 class="fw-semibold mb-2">Scalability </h5>
				<p class="text-muted">Our AI call assistant software has the extensive ability to match with the business demands without enhancing expenses or decreasing the software quality..</p>
			</div>
		</div>
	</div>
</section>




<section class="section-padded pricing-plans-wrapper ">
	<div class="container">
		<h2 class="text-center">Pricing Plans</h2>
		<div class="billing-currency-wrap text-center">
			<div class="billing-controls d-inline-flex">
				<div data-period="Monthly" class="pricing-tab billing-period-btn">Monthly</div>
				<div data-period="Yearly" class="pricing-tab billing-period-btn active">Yearly</div>
			</div>
			<div class="currency-controls mx-auto mt-3 mt-md-0">
				<label for="currency-select">Choose Currency</label>
				<select id="currency-select" class="currency-select">
					<option value="USD" selected>USD</option>
					<option value="INR">INR</option>
				</select>
			</div>
		</div>

		<div id="planCards" class="row">
			<div class="col-sm-4 col-lg-3 d-none d-sm-block">
				<div class="card-main featured-card" id="featuredCard">
					<div class="plan-card feature-title h4 mb-0">
						<div class="plan-name"> Features </div>
					</div>
					<!-- Feature list-->
					<div id="featureList"></div>
				</div>
			</div>
			<div class="col-sm mobile-wrap col-lg-9">
				<div class="row flex-sm-nowrap row-cols-1 row-cols-md-2 row-cols-lg-4" id="planCardsRow">
					<!-- Individual plan cards will be generated here -->
				</div>
			</div>
		</div>
	</div>
</section>

<section class="section-padded faq-section">
	<div class="container" style="max-width: 800px">

		<div class="text-center mx-auto mb-5">
			<h2 class="display-5 fw-bold mb-3">F.A.Q s</h2>
			<p class="lead text-muted"> Everything you need to know before choosing a Botphonic plan. </p>
		</div>

		<?php if (have_rows('faqs')) { ?>
		<div class="faq-section accordion-list">
			<?php
	$faq_icon = '<div class="faq-icon-plus">
					<svg xmlns="http://www.w3.322000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none" preserveAspectRatio="xMidYMid meet" aria-hidden="true" role="img">
						<path d="M8 16H24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
						<path d="M16 24V8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
					</svg>
				</div>
				<div class="faq-icon-minus">
					<svg xmlns="http://www.w3.322000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none" preserveAspectRatio="xMidYMid meet" aria-hidden="true" role="img">
						<path d="M8 16H24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
					</svg>
				</div>';

	$faqs = get_field('faqs')
			?>

			<div class="faq-content">
				<div class="faq-content-wrap">
					<?php foreach ($faqs as $faq) { ?>
					<div class="faq-item">
						<div class="faq-head">
							<div class="faq-item-heading h3"><?= $faq['question']; ?></div>
							<?= $faq_icon; ?>
						</div>
						<div class="faq-text">
							<div class="faq-paragraph"><?= ($faq['answer']); ?></div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
			<?php
	$json = [
		'@context' => 'https://schema.org',
		'@type' => 'FAQPage',
		'mainEntity' => [],
	];

	foreach (get_field('faqs') as $index => $item) {
		$json['mainEntity'][] = [
			'@type' => 'Question',
			'name' => $item['question'],
			'acceptedAnswer' => [
				'@type' => 'Answer',
				'text' => esc_html($item['answer']),
			],
		];
	}
	wp_enqueue_script('botphonic-faqs-js');
			?>
			<script type="application/ld+json">
                    <?= wp_json_encode($json); ?>
			</script>

			<script>
				jQuery(document).ready(function() {
					botPhonicFaqs()
				});
			</script>
		</div>
		<?php
} ?>
	</div>
</section>

<section class="cta-section">
	<?= do_shortcode('[elementor-template id="600"]'); ?>
</section>
<?php
wp_enqueue_script('pricing');
wp_enqueue_script('pricingNew');
get_footer();
?>