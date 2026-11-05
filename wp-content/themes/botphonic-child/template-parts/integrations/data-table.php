<?php
/**
 * What moves between Botphonic and the other app, as a table.
 *
 * Plain, valid table markup. Below 760px the CSS shows each row as a card and
 * labels each cell from its data-label (the matching column heading).
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$bpi_page = isset($args['page']) ? (array) $args['page'] : array();
$bpi_table = isset($bpi_page['data_table']) ? (array) $bpi_page['data_table'] : array();
if (empty($bpi_table['rows']) || empty($bpi_table['columns'])) {
	return;
}

$bpi_columns = array_values((array) $bpi_table['columns']);

// Tinted by default; 'tone' => 'white' for pages where the section before it is tinted.
$bpi_tone = isset($bpi_table['tone']) && 'white' === $bpi_table['tone'] ? '' : ' bpi-section--tint';
?>
<section id="data-sync" class="bpi-section<?php echo esc_attr($bpi_tone); ?>" aria-labelledby="bpi-data-title">
	<div class="bpi-wrap">
		<header class="bpi-heading">
			<p class="bpi-kicker"><?php echo esc_html(!empty($bpi_table['kicker']) ? $bpi_table['kicker'] : 'What syncs'); ?></p>
			<h2 id="bpi-data-title"><?php echo esc_html($bpi_table['title']); ?></h2>
			<?php if (!empty($bpi_table['lede'])) : ?>
			<p class="bpi-lede"><?php echo botphonic_integration_kses($bpi_table['lede']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered by wp_kses. ?></p>
			<?php endif; ?>
		</header>

		<div class="bpi-table-wrap" data-bpi-reveal>
			<table class="bpi-table">
				<?php if (!empty($bpi_table['caption'])) : ?>
				<caption><?php echo esc_html($bpi_table['caption']); ?></caption>
				<?php endif; ?>
				<thead>
					<tr>
						<?php foreach ($bpi_columns as $bpi_column) : ?>
						<th scope="col"><?php echo esc_html($bpi_column); ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ((array) $bpi_table['rows'] as $bpi_row) : ?>
					<tr>
						<?php foreach (array_values((array) $bpi_row) as $bpi_i => $bpi_cell) : ?>
							<?php if (0 === $bpi_i) : ?>
						<th scope="row"><?php echo botphonic_integration_kses($bpi_cell); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered by wp_kses. ?></th>
							<?php else : ?>
						<td data-label="<?php echo esc_attr(isset($bpi_columns[$bpi_i]) ? $bpi_columns[$bpi_i] : ''); ?>"><?php echo botphonic_integration_kses($bpi_cell); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered by wp_kses. ?></td>
							<?php endif; ?>
						<?php endforeach; ?>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<?php if (!empty($bpi_table['note'])) : ?>
		<p class="bpi-note"><?php echo botphonic_integration_kses($bpi_table['note']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered by wp_kses. ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
unset($bpi_page, $bpi_table, $bpi_columns, $bpi_tone, $bpi_column, $bpi_row, $bpi_i, $bpi_cell);
