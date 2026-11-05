<?php
/**
 * Hero visual: an example sync between Botphonic and the integrated app.
 *
 * Pure HTML and CSS, so it stays sharp, readable by screen readers and costs
 * no image requests.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_page = isset($args['page']) ? (array) $args['page'] : array();
$bpi_visual = isset($bpi_page['visual']) ? (array) $bpi_page['visual'] : array();
if (empty($bpi_visual['events'])) {
	return;
}

$bpi_check = botphonic_integration_icon('check', 12);
?>
<figure class="bpi-flow" aria-labelledby="bpi-flow-caption">
	<figcaption class="bpi-flow__head">
		<span class="bpi-flow__bar">
			<span class="bpi-flow__chip"><?php echo botphonic_integration_brand_mark(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the helper. ?></span>
			<span class="bpi-flow__wire" aria-hidden="true"><span></span></span>
			<span class="bpi-flow__chip bpi-flow__chip--partner">
				<?php echo botphonic_integration_logo($bpi_page['key'], '', true); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the helper. ?>
				<span><?php echo esc_html($bpi_page['name']); ?></span>
			</span>
			<?php if (!empty($bpi_visual['status'])) : ?>
			<span class="bpi-flow__status"><span class="bpi-live-dot" aria-hidden="true"></span><?php echo esc_html($bpi_visual['status']); ?></span>
			<?php endif; ?>
		</span>
		<span id="bpi-flow-caption" class="bpi-flow__caption"><?php echo esc_html($bpi_visual['caption']); ?></span>
	</figcaption>

	<ol class="bpi-flow__events">
		<?php foreach ((array) $bpi_visual['events'] as $bpi_event) : ?>
		<?php $bpi_source = isset($bpi_event['source']) ? sanitize_html_class($bpi_event['source']) : 'botphonic'; ?>
		<li class="bpi-event bpi-event--<?php echo esc_attr($bpi_source); ?>">
			<span class="bpi-event__time"><?php echo esc_html($bpi_event['time']); ?></span>
			<span class="bpi-event__body">
				<span class="bpi-event__source"><?php echo esc_html($bpi_event['label']); ?></span>
				<strong class="bpi-event__title"><?php echo esc_html($bpi_event['title']); ?></strong>
				<?php if (!empty($bpi_event['detail'])) : ?>
				<span class="bpi-event__detail"><?php echo esc_html($bpi_event['detail']); ?></span>
				<?php endif; ?>
			</span>
		</li>
		<?php endforeach; ?>
	</ol>

	<?php if (!empty($bpi_visual['outcomes'])) : ?>
	<ul class="bpi-outcomes" aria-label="<?php echo esc_attr($bpi_visual['outcomes_label']); ?>">
		<?php foreach ((array) $bpi_visual['outcomes'] as $bpi_outcome) : ?>
		<li><?php echo $bpi_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?><?php echo esc_html($bpi_outcome); ?></li>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>

	<?php if (!empty($bpi_visual['footnote'])) : ?>
	<p class="bpi-flow__footnote"><?php echo esc_html($bpi_visual['footnote']); ?></p>
	<?php endif; ?>
</figure>
<?php
unset($bpi_page, $bpi_visual, $bpi_check, $bpi_event, $bpi_source, $bpi_outcome);
