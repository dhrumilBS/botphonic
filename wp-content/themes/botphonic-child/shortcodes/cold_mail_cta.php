<?php

/**
 * Shortcode: [cold_mail_cta]
 * Description: Renders a CTA section for cold email outreach
 */

add_shortcode('cold_mail_cta', 'botphonic_render_cold_mail_cta');

function botphonic_render_cold_mail_cta($atts)
{
	$a = shortcode_atts(
		[
			'title'    => 'Turn Cold Emails Into <span class="text-gradient">Conversations</span>',
			'text'     => '<p>Personalize smarter, send faster, and get more replies with Botphonic. Start building meaningful connections today.</p>',
			'btn_text' => 'Sign Up for Free Trial!!',
			'btn_link' => 'https://app.botphonic.ai/',
		],
		$atts
	);

	ob_start();
?>
<style>
	.mail-cta-section { position: relative; background: linear-gradient(135deg, rgba(59, 130, 246, 0.18) 0%, rgba(59, 130, 246, 0.10) 30%, rgba(99, 102, 241, 0.08) 50%, rgba(59, 130, 246, 0.04) 65%, transparent 75%); padding: 80px 0; }
	.cta-glow { display: none; }
	.cta-content { position: relative; z-index: 2; }
	.cta-inner { text-align: center; max-width: 750px; margin: 0 auto; }
	.cta-icons { display: flex; align-items: center; justify-content: center; gap: 16px; margin-bottom: 32px; }
	.cta-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; }
	.cta-icon.primary { width: 64px; height: 64px; background: linear-gradient(135deg, #3b82f6, #6366f1); color: #fff; box-shadow: 0 12px 32px rgba(59, 130, 246, 0.35); }
	.cta-subtitle { font-size: 1.25rem; margin: 24px 0; color: var(--text); }
	.cta-trust { display: flex; justify-content: center; gap: 32px; flex-wrap: wrap; margin-top: 40px; font-size: 0.95rem; color: var(--text); }
	.cta-trust span { display: flex; align-items: center; gap: 8px; }
	.cta-trust span::before { content: ""; width: 8px; height: 8px; background: #3b82f6; border-radius: 50%; }
	@media (max-width: 768px) {
		.cta-icons { gap: 24px; }
		.cta-icon { width: 36px; height: 36px; }
		.cta-icon svg { width: 20px; height: 20px; }
		.cta-subtitle { font-size: 1rem; margin: 12px 0; }
	}
</style>
<section class="mail-cta-section section-padded cta-cold-mail">
	<div class="container cta-content">
		<div class="cta-inner">
			<div class="cta-icons">
				<div class="cta-icon accent-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<rect width="20" height="16" x="2" y="4" rx="2"></rect>
						<path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
					</svg>
				</div>
				<div class="cta-icon primary">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"></path>
					</svg>
				</div>
				<div class="cta-icon secondary-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path>
					</svg>
				</div>
			</div>
			<h2><?= wp_kses_post($a['title']); ?></h2>
			<div class="cta-subtitle"><?= wp_kses_post($a['text']); ?></div>
			<div class="mt-4">
				<a class="theme-btn" href="<?= esc_url($a['btn_link']); ?>" id="cta-signup-btn" data-action="signup"><?= esc_html($a['btn_text']); ?></a>
			</div>
			<div class="cta-trust">
				<span>No credit card required</span>
				<span>Free 14-day trial</span>
				<span>Setup in 5 minutes</span>
			</div>
		</div>
	</div>
</section>
<?php
	return ob_get_clean();
}
