<?php
/**
 * Use cases as accessible tabs (integration-pages.js). Without JavaScript every
 * panel is shown in order and the tab buttons are hidden.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_page = isset($args['page']) ? (array) $args['page'] : array();
$bpi_cases = isset($bpi_page['use_cases']) ? (array) $bpi_page['use_cases'] : array();
if (empty($bpi_cases['items'])) {
	return;
}

$bpi_check = botphonic_integration_icon('check', 14);
$bpi_arrow = botphonic_integration_icon('arrow', 16);
$bpi_first = true;
?>
<section id="use-cases" class="bpi-section" aria-labelledby="bpi-cases-title">
	<div class="bpi-wrap">
		<header class="bpi-heading bpi-heading--center">
			<p class="bpi-kicker"><?php echo esc_html(!empty($bpi_cases['kicker']) ? $bpi_cases['kicker'] : 'Use cases'); ?></p>
			<h2 id="bpi-cases-title"><?php echo esc_html($bpi_cases['title']); ?></h2>
			<?php if (!empty($bpi_cases['lede'])) : ?>
			<p class="bpi-lede"><?php echo botphonic_integration_kses($bpi_cases['lede']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered by wp_kses. ?></p>
			<?php endif; ?>
		</header>

		<div class="bpi-tabs" role="tablist" aria-label="<?php echo esc_attr(!empty($bpi_cases['tabs_label']) ? $bpi_cases['tabs_label'] : 'Choose a use case'); ?>" data-bpi-tabs>
			<?php foreach ((array) $bpi_cases['items'] as $bpi_case) : ?>
			<?php $bpi_id = sanitize_html_class($bpi_case['id']); ?>
			<button class="bpi-tab" type="button" role="tab" id="bpi-tab-<?php echo esc_attr($bpi_id); ?>" aria-controls="bpi-panel-<?php echo esc_attr($bpi_id); ?>" aria-selected="<?php echo $bpi_first ? 'true' : 'false'; ?>"<?php echo $bpi_first ? '' : ' tabindex="-1"'; ?>><?php echo esc_html($bpi_case['label']); ?></button>
			<?php $bpi_first = false; ?>
			<?php endforeach; ?>
		</div>

		<div class="bpi-panels">
			<?php foreach ((array) $bpi_cases['items'] as $bpi_case) : ?>
			<?php $bpi_id = sanitize_html_class($bpi_case['id']); ?>
			<div class="bpi-panel" id="bpi-panel-<?php echo esc_attr($bpi_id); ?>" role="tabpanel" aria-labelledby="bpi-tab-<?php echo esc_attr($bpi_id); ?>" tabindex="0" data-bpi-panel>
				<div class="bpi-stack">
					<p class="bpi-panel__label"><?php echo esc_html($bpi_case['label']); ?></p>
					<h3><?php echo esc_html($bpi_case['title']); ?></h3>
					<p><?php echo botphonic_integration_kses($bpi_case['text']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered by wp_kses. ?></p>
					<?php if (!empty($bpi_case['link']['url'])) : ?>
					<a class="bpi-btn bpi-btn--ink" href="<?php echo esc_url($bpi_case['link']['url']); ?>"><?php echo esc_html($bpi_case['link']['label']); ?><?php echo $bpi_arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></a>
					<?php endif; ?>
				</div>
				<?php if (!empty($bpi_case['points'])) : ?>
				<ul class="bpi-check-list">
					<?php foreach ((array) $bpi_case['points'] as $bpi_point) : ?>
					<li><span aria-hidden="true"><?php echo $bpi_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span><?php echo botphonic_integration_kses($bpi_point); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered by wp_kses. ?></li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php
unset($bpi_page, $bpi_cases, $bpi_check, $bpi_arrow, $bpi_first, $bpi_case, $bpi_id, $bpi_point);
