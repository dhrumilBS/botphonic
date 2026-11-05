<?php
/**
 * Hero visual for the Integrations hub: Botphonic at the center of the tools
 * it connects to, grouped by category. Pure HTML and CSS.
 *
 * Groups list directory keys (botphonic_integration_directory()); items with a
 * logo show it, the others show their name.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_page = isset($args['page']) ? (array) $args['page'] : array();
$bpi_visual = isset($bpi_page['visual']) ? (array) $bpi_page['visual'] : array();
if (empty($bpi_visual['groups'])) {
	return;
}

$bpi_items = botphonic_integration_directory();
?>
<figure class="bpi-flow bpi-hub" aria-labelledby="bpi-hub-caption">
	<figcaption class="bpi-flow__head">
		<span class="bpi-flow__bar">
			<span class="bpi-flow__chip"><?php echo botphonic_integration_brand_mark(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the helper. ?></span>
			<?php if (!empty($bpi_visual['status'])) : ?>
			<span class="bpi-flow__status"><span class="bpi-live-dot" aria-hidden="true"></span><?php echo esc_html($bpi_visual['status']); ?></span>
			<?php endif; ?>
		</span>
		<span id="bpi-hub-caption" class="bpi-flow__caption"><?php echo esc_html($bpi_visual['caption']); ?></span>
	</figcaption>

	<div class="bpi-hub__map">
		<span class="bpi-hub__core" aria-hidden="true"><?php echo botphonic_integration_icon('transfer', 26); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
		<ul class="bpi-hub__groups">
			<?php foreach ((array) $bpi_visual['groups'] as $bpi_group) : ?>
			<li class="bpi-hub__group">
				<span class="bpi-hub__label"><?php echo esc_html($bpi_group['label']); ?></span>
				<span class="bpi-hub__logos">
					<?php foreach ((array) $bpi_group['items'] as $bpi_key) : ?>
						<?php
						if (empty($bpi_items[$bpi_key])) {
							continue;
						}
						$bpi_item = $bpi_items[$bpi_key];
						?>
						<?php if (!empty($bpi_item['logo']) || !empty($bpi_item['mark'])) : ?>
							<?php echo botphonic_integration_item_logo($bpi_item, $bpi_item['name'], true); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the helper. ?>
						<?php else : ?>
					<span class="bpi-hub__chip"><?php echo esc_html($bpi_item['name']); ?></span>
						<?php endif; ?>
					<?php endforeach; ?>
				</span>
				<span class="bpi-hub__note"><?php echo esc_html($bpi_group['note']); ?></span>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>

	<?php if (!empty($bpi_visual['footnote'])) : ?>
	<p class="bpi-flow__footnote"><?php echo esc_html($bpi_visual['footnote']); ?></p>
	<?php endif; ?>
</figure>
<?php
unset($bpi_page, $bpi_visual, $bpi_items, $bpi_group, $bpi_key, $bpi_item);
