<?php
/**
 * The template for displaying HTTP 410 Gone responses.
 *
 * Loaded by inc/function-410-gone.php via the `template_include` filter
 * whenever botphonic_is_410_request() is true. The 410 status header is
 * sent on `template_redirect`, so this file only handles presentation.
 *
 * @package Botphonic_Child
 */

defined('ABSPATH') || exit;

get_header(); ?>
<style>
	.error410-section .container { max-width: 900px; width: 100%; height: 90vh; display: flex; gap: 30px; }
	.error410-section .image { flex: 1 1 300px; display: flex; align-items: center; justify-content: center; }
	.error410-section .image img { max-width: 100%; height: auto; }
	.error410-section .content { flex: 1 1 300px; display: flex; flex-direction: column; justify-content: center; text-align: left; }
	.error410-section .content h1 { margin-bottom: 20px; }
	.error410-section .content .headline { font-size: 18px; margin-bottom: 30px; }
	@media (max-width: 768px) {
		.error410-section .container { flex-direction: column; text-align: center; }
		.error410-section .content { text-align: center; }
	}
</style>
<section class="error410-section">
	<div class="container">
		<div class="image">
			<img src="https://botphonic.ai/wp-content/uploads/2025/05/Botphonic-AI-404.webp" alt="AI Call Assistant Bot">
		</div>
		<div class="content">
			<h1>410 – This Line Has Been Retired</h1>
			<p class="headline">This page has been permanently removed and won&rsquo;t be coming back. Botphonic AI, on the other hand, never stops answering.</p>
			<a class="elementor-button" href="<?php echo esc_url(home_url('/')); ?>">
				<span class="elementor-button-text">Go To Homepage</span>
			</a>
		</div>
	</div>
</section>
<?php get_footer(); ?>
