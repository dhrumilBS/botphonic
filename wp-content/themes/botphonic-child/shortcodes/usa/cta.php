<?php
add_shortcode('usa_cta', 'usa_cta_fn');

function usa_cta_fn($attr)
{
	$d = shortcode_atts([
		'title' => 'See IVR Replacement in Action',
		'subtitle' => 'Get Started With AI Automation Today And Free Your Agents To Focus On Complicated Tasks.',
		'button_text' => 'Book a Demo',
		'button_link' => 'https://app.botphonic.ai/register/',
	], $attr);

	ob_start(); ?>

<div class="cta-section section-padded dark-bg">
	<div class="container" style="max-width:800px">
		<h2><?php echo wp_kses_post($d['title']); ?></h2>
		<p><?php echo wp_kses_post($d['subtitle']); ?></p>
		<a href="<?php echo esc_url($d['button_link']); ?>" target="_blank" class="theme-btn d-inline-block">
			<?php echo esc_html($d['button_text']); ?>
		</a>
	</div>
</div>

<style>
	.cta-section { text-align: center; margin: 30px; background-color: #132B3B; background-image: url('https://botphonic.ai/wp-content/uploads/2025/04/cta-background-light.webp'); background-position: top center; background-size: cover; border-radius: 24px; }
	.cta-section h2 { margin-bottom: 16px; }
	.cta-section p { font-size: 1.1rem; margin: 0 auto 24px; }
	.cta-section .theme-btn:hover { color: var(--accent); }
	@media screen and (max-width: 450px) { .cta-section { margin: 16px; } }
</style>


<?php
	return ob_get_clean();
}
