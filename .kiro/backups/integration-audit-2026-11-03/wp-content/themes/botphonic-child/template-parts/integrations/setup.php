<?php
/**
 * Setup requirements and links to the guides.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_page = isset($args['page']) ? (array) $args['page'] : array();
$bpi_setup = isset($bpi_page['setup']) ? (array) $bpi_page['setup'] : array();
if (empty($bpi_setup['needs'])) {
	return;
}

$bpi_check = botphonic_integration_icon('check', 14);
$bpi_arrow = botphonic_integration_icon('arrow', 16);
?>
<section id="setup" class="bpi-section" aria-labelledby="bpi-setup-title">
	<div class="bpi-wrap bpi-grid bpi-grid--setup">
		<div class="bpi-stack">
			<p class="bpi-kicker"><?php echo esc_html(!empty($bpi_setup['kicker']) ? $bpi_setup['kicker'] : 'Before you start'); ?></p>
			<h2 id="bpi-setup-title"><?php echo esc_html($bpi_setup['title']); ?></h2>
			<?php if (!empty($bpi_setup['lede'])) : ?>
			<p class="bpi-lede"><?php echo botphonic_integration_kses($bpi_setup['lede']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered by wp_kses. ?></p>
			<?php endif; ?>
			<?php if (!empty($bpi_setup['links'])) : ?>
			<div class="bpi-actions">
				<?php foreach ((array) $bpi_setup['links'] as $bpi_index => $bpi_link) : ?>
				<a class="bpi-btn <?php echo 0 === $bpi_index ? 'bpi-btn--ink' : 'bpi-btn--outline'; ?>" href="<?php echo esc_url($bpi_link['url']); ?>"><?php echo esc_html($bpi_link['label']); ?><?php echo 0 === $bpi_index ? $bpi_arrow : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></a>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</div>

		<div class="bpi-setup-card" data-bpi-reveal>
			<h3><?php echo esc_html(!empty($bpi_setup['needs_title']) ? $bpi_setup['needs_title'] : 'What you need'); ?></h3>
			<ul class="bpi-check-list bpi-check-list--plain">
				<?php foreach ((array) $bpi_setup['needs'] as $bpi_need) : ?>
				<li><span aria-hidden="true"><?php echo $bpi_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span><span><?php echo botphonic_integration_kses($bpi_need); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered by wp_kses. ?></span></li>
				<?php endforeach; ?>
			</ul>
			<?php if (!empty($bpi_setup['note'])) : ?>
			<p class="bpi-setup-card__note"><?php echo botphonic_integration_kses($bpi_setup['note']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered by wp_kses. ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php
unset($bpi_page, $bpi_setup, $bpi_check, $bpi_arrow, $bpi_index, $bpi_link, $bpi_need);
