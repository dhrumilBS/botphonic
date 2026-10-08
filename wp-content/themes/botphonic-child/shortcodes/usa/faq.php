<?php
/**
 * Shortcode: [botphonic_faq]
 */
function botphonic_faq_shortcode($atts)
{
	if (!function_exists('botphonic_get_faq_rows') || !function_exists('botphonic_render_faq_group')) {
		return '';
	}

	$post_id = absint(get_the_ID());
	if (!$post_id) {
		$post_id = absint(get_queried_object_id());
	}

	$faq_html = botphonic_render_faq_group(botphonic_get_faq_rows($post_id));
	if ('' === $faq_html) {
		return '';
	}

	ob_start();
	?>
	<section class="section-padded faq-section bg-transparent">
		<div class="container" style="max-width: 900px">
			<div class="text-center mx-auto mb-3">
				<h2 style="text-transform: none;">F.A.Q.s</h2>
			</div>
			<div class="accordion-list">
				<div class="faq-content">
					<?= $faq_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by the shared renderer. ?>
				</div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode('botphonic_faq', 'botphonic_faq_shortcode');
