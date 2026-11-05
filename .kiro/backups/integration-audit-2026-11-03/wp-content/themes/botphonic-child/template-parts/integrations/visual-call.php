<?php
/**
 * Hero visual: an example call transcript with the actions it completed.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_page = isset($args['page']) ? (array) $args['page'] : array();
$bpi_visual = isset($bpi_page['visual']) ? (array) $bpi_page['visual'] : array();
if (empty($bpi_visual['messages'])) {
	return;
}

$bpi_check = botphonic_integration_icon('check', 12);
?>
<figure class="bpi-call" aria-labelledby="bpi-call-title">
	<figcaption class="bpi-call__head">
		<?php echo botphonic_integration_logo($bpi_page['key'], '', true); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the helper. ?>
		<span class="bpi-call__identity">
			<strong id="bpi-call-title"><?php echo esc_html($bpi_visual['title']); ?></strong>
			<span><?php echo esc_html($bpi_visual['meta']); ?></span>
		</span>
		<?php if (!empty($bpi_visual['badge'])) : ?>
		<span class="bpi-call__badge"><?php echo esc_html($bpi_visual['badge']); ?></span>
		<?php endif; ?>
	</figcaption>

	<div class="bpi-call__messages">
		<?php foreach ((array) $bpi_visual['messages'] as $bpi_message) : ?>
		<?php $bpi_is_agent = isset($bpi_message['who']) && 'agent' === $bpi_message['who']; ?>
		<p class="bpi-message bpi-message--<?php echo $bpi_is_agent ? 'agent' : 'caller'; ?>"><span><?php echo $bpi_is_agent ? 'Botphonic agent' : 'Caller'; ?></span><?php echo esc_html($bpi_message['text']); ?></p>
		<?php endforeach; ?>
	</div>

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
unset($bpi_page, $bpi_visual, $bpi_check, $bpi_message, $bpi_is_agent, $bpi_outcome);
