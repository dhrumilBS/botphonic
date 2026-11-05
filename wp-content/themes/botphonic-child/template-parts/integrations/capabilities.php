<?php
/**
 * What the integration can do: icon cards.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_page = isset($args['page']) ? (array) $args['page'] : array();
$bpi_features = isset($bpi_page['capabilities']) ? (array) $bpi_page['capabilities'] : array();
if (empty($bpi_features['items'])) {
	return;
}
?>
<section id="features" class="bpi-section bpi-section--tint" aria-labelledby="bpi-features-title">
	<div class="bpi-wrap">
		<header class="bpi-heading">
			<p class="bpi-kicker"><?php echo esc_html(!empty($bpi_features['kicker']) ? $bpi_features['kicker'] : 'What you can automate'); ?></p>
			<h2 id="bpi-features-title"><?php echo esc_html($bpi_features['title']); ?></h2>
			<?php if (!empty($bpi_features['lede'])) : ?>
			<p class="bpi-lede"><?php echo botphonic_integration_kses($bpi_features['lede']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered by wp_kses. ?></p>
			<?php endif; ?>
		</header>

		<div class="bpi-grid bpi-grid--three">
			<?php foreach ((array) $bpi_features['items'] as $bpi_feature) : ?>
			<article class="bpi-card bpi-feature" data-bpi-reveal>
				<span class="bpi-icon" aria-hidden="true"><?php echo botphonic_integration_icon(isset($bpi_feature['icon']) ? $bpi_feature['icon'] : 'check'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
				<h3><?php echo esc_html($bpi_feature['title']); ?></h3>
				<p><?php echo botphonic_integration_kses($bpi_feature['text']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered by wp_kses. ?></p>
			</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php
unset($bpi_page, $bpi_features, $bpi_feature);
