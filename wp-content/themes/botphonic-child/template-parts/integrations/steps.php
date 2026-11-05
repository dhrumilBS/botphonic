<?php
/**
 * How the integration works, as numbered steps.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_page = isset($args['page']) ? (array) $args['page'] : array();
$bpi_steps = isset($bpi_page['steps']) ? (array) $bpi_page['steps'] : array();
if (empty($bpi_steps['items'])) {
	return;
}

// Optional tinted background, for pages where the section before it is white.
$bpi_tone = !empty($bpi_steps['tone']) && 'tint' === $bpi_steps['tone'] ? ' bpi-section--tint' : '';
?>
<section id="how-it-works" class="bpi-section<?php echo esc_attr($bpi_tone); ?>" aria-labelledby="bpi-steps-title">
	<div class="bpi-wrap">
		<header class="bpi-heading bpi-heading--center">
			<p class="bpi-kicker"><?php echo esc_html(!empty($bpi_steps['kicker']) ? $bpi_steps['kicker'] : 'How it works'); ?></p>
			<h2 id="bpi-steps-title"><?php echo esc_html($bpi_steps['title']); ?></h2>
			<?php if (!empty($bpi_steps['lede'])) : ?>
			<p class="bpi-lede"><?php echo botphonic_integration_kses($bpi_steps['lede']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered by wp_kses. ?></p>
			<?php endif; ?>
		</header>

		<ol class="bpi-steps">
			<?php foreach ((array) $bpi_steps['items'] as $bpi_index => $bpi_step) : ?>
			<li class="bpi-step" data-bpi-reveal>
				<span class="bpi-step__marker" aria-hidden="true">
					<span class="bpi-step__num"><?php echo esc_html(str_pad((string) ($bpi_index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
					<?php echo botphonic_integration_icon(isset($bpi_step['icon']) ? $bpi_step['icon'] : 'check', 20); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
				</span>
				<h3><?php echo esc_html($bpi_step['title']); ?></h3>
				<p><?php echo botphonic_integration_kses($bpi_step['text']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered by wp_kses. ?></p>
			</li>
			<?php endforeach; ?>
		</ol>

		<?php if (!empty($bpi_steps['more']['url'])) : ?>
		<p class="bpi-more"><a class="bpi-text-link" href="<?php echo esc_url($bpi_steps['more']['url']); ?>"><?php echo esc_html($bpi_steps['more']['label']); ?></a></p>
		<?php endif; ?>
	</div>
</section>
<?php
unset($bpi_page, $bpi_steps, $bpi_tone, $bpi_index, $bpi_step);
