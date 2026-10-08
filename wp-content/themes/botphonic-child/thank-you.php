<?php
/*
 * Template name: Thank you 
 * */

get_header(); 
?>
<style>
	.thankyou-section { text-align: center; background: linear-gradient(336deg, #FF80000E 9%, #3770FF0D 88%); }
	.thankyou-hero { background: #ffffff; border-radius: 16px; padding: 36px 16px; box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08); margin-bottom: 40px; }
	.success-icon { display: inline-flex; align-items: center; justify-content: center; width: 60px; height: 60px; border-radius: 50%; background: #ecfdf5; margin-bottom: 20px; }
	.success-icon svg { stroke: #10b981; }
	.features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-top: 40px; }
	.feature-card { background: #ffffff; border-radius: 14px; padding: 30px 22px; text-align: center; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); transition: transform 0.3s ease, box-shadow 0.3s ease; }
	.feature-image { background: var(--accent); display: inline-flex; padding: 12px; border-radius: 8px; margin-bottom: 16px; }
	.feature-image svg { stroke: #fff; }
	.btn-wrap { margin-top: 40px; }
	.thankyou-footer { margin-top: 60px; padding: 24px 0; border-top: 1px solid #e5e7eb; }
	.thankyou-footer p { font-size: 14px; margin: 0; }
	.thankyou-footer a { color: var(--accent); text-decoration: none; }
</style>

<section class="thankyou-section section-padded">
	<div class="container">
		<div class="thankyou-hero">
			<div class="success-icon">
				<svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<circle cx="12" cy="12" r="10"></circle>
					<path d="M9 12l2 2 4-4"></path>
				</svg>
			</div>
			<h1>Thank You, We Received Your Request.</h1>
			<p>Our sales team will contact you shortly for better understanding your requirements, offering the custom-made AI Assistant solution.</p>
		</div>

		<div class="features-grid">
			<div class="feature-card">
				<div class="feature-image">
					<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="12" cy="12" r="10"></circle>
						<polyline points="12 6 12 12 16 14"></polyline>
					</svg>
				</div>
				<h4>Immediate Response</h4>
				<p>We respect your time and don’t want you to wait longer. Usually answer to customers on the same business day. For urgent queries, you can directly call our sales team.</p>
			</div>
			<div class="feature-card">
				<div class="feature-image">
					<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path>
					</svg>
				</div>
				<h4>Rapid Deployment</h4>
				<p>Ease to install in any operating device due to automation tools that quickly download the necessary elements, decreasing the set-up time.</p>
			</div>
			<div class="feature-card">
				<div class="feature-image">
					<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
					</svg>
				</div>
				<h4>Safe Execution</h4>
				<p>Botphonic adapts in the evolving work environment and business operations. We modified it without affecting the existing framework, which leads to save costs.</p>
			</div>
		</div>

		<div class="btn-wrap">
			<a class="theme-btn" href="/">Back to Home</a>
		</div>
		<div class="thankyou-footer">
			<p> Any Questions? Email us at <a href="mailto:contact@botphonic.ai">contact@botphonic.ai</a> or visit our <a href="https://botphonic.ai">website</a>.
			</p>
		</div>
	</div>
</section>
<?php get_footer(); ?>