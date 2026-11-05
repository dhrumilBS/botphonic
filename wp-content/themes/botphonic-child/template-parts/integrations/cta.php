<?php
/**
 * Closing call to action.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_page = isset($args['page']) ? (array) $args['page'] : array();
$bpi_cta = isset($bpi_page['cta']) ? (array) $bpi_page['cta'] : array();
if (empty($bpi_cta['title'])) {
	return;
}

$bpi_arrow = botphonic_integration_icon('arrow', 16);
?>
<section class="bpi-final" aria-labelledby="bpi-cta-title">
	<div class="bpi-final__inner">
		<h2 id="bpi-cta-title"><?php echo esc_html($bpi_cta['title']); ?></h2>
		<?php if (!empty($bpi_cta['text'])) : ?>
		<p><?php echo botphonic_integration_kses($bpi_cta['text']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered by wp_kses. ?></p>
		<?php endif; ?>
		<div class="bpi-actions bpi-actions--center">
			<a class="bpi-btn bpi-btn--coral" href="<?php echo esc_url($bpi_page['register_url']); ?>">Start 14-day free trial<?php echo $bpi_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></a>
			<a class="bpi-btn bpi-btn--outline" href="<?php echo esc_url($bpi_page['demo_url']); ?>">Book a demo</a>
		</div>
		<small>14-day free trial · 20 free minutes · $1 card check, refunded within 48 hours · Cancel anytime · <a href="<?php echo esc_url(home_url('/pricing/')); ?>">See pricing</a></small>
	</div>
</section>
<?php
unset($bpi_page, $bpi_cta, $bpi_arrow);
