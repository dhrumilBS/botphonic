<?php
/**
 * FAQ, rendered by the shared accordion from the Botphonic Core plugin.
 *
 * With Yoast active the questions go into Yoast's graph
 * (inc/function-integration-pages.php), so the plugin's standalone FAQPage
 * output is switched off here to avoid a duplicate.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_page = isset($args['page']) ? (array) $args['page'] : array();
if (empty($bpi_page['faqs']) || !function_exists('botphonic_render_faq_group')) {
	return;
}

$bpi_faq_title = !empty($bpi_page['faq_title']) ? $bpi_page['faq_title'] : sprintf('%s questions', $bpi_page['label']);
?>
<section id="faq" class="bpi-section bpi-section--tint" aria-labelledby="bpi-faq-title">
	<div class="bpi-wrap bpi-faq">
		<div class="bpi-stack bpi-faq__intro">
			<p class="bpi-kicker">FAQ</p>
			<h2 id="bpi-faq-title"><?php echo esc_html($bpi_faq_title); ?></h2>
			<p class="bpi-lede">Still deciding? We’ll walk through your setup on a short call.</p>
			<a class="bpi-btn bpi-btn--ink" href="<?php echo esc_url($bpi_page['demo_url']); ?>">Book a 20-minute demo</a>
		</div>
		<div class="bpi-faq__list">
			<?php echo botphonic_render_faq_group($bpi_page['faqs'], array('schema' => !defined('WPSEO_VERSION'))); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by the shared renderer. ?>
		</div>
	</div>
</section>
<?php
unset($bpi_page, $bpi_faq_title);
