<?php

/**
 * Comparison, "versus" layout — the feature comparison with a picker.
 *
 * The brand column is always shown; the reader picks which rivals stand next
 * to it, any number of them, or all at once. Values are shown more legibly
 * than in the classic table, and every character of every value is still on
 * the page:
 *
 *   - yes / no        a solid tick or cross, with any caveat underneath
 *   - not documented  the shared "Not documented" tag
 *   - star ratings    stars
 *   - lists           (integrations, compliance) one tag per item; the
 *                     original separators stay in the markup for screen
 *                     readers and copy-paste
 *   - figures         prices and measurements (300ms, 99.9%, 50+) are set in
 *                     bold inside the sentence
 *
 * The page sections ("At a glance" plus one per feature category) fold.
 *
 * Without JavaScript every platform and every section is shown and the picker
 * is hidden; assets/js/alternatives.js adds the picker and the folding. On
 * narrow screens each row's label moves above its values (alternatives.css
 * § Versus comparison), which is why the table elements carry explicit ARIA
 * roles: changing their display would otherwise drop the table semantics in
 * some browsers.
 *
 * Accepted $args:
 *   'alt'   array  botphonic_alt_data() for the guide.
 *   'brand' string Brand name.
 *
 * @package Botphonic
 */

defined('ABSPATH') || exit;

$alt = isset($args['alt']) && is_array($args['alt']) ? $args['alt'] : array();
$alt_brand = isset($args['brand']) ? (string) $args['brand'] : botphonic_alt_brand();
$alt_platforms = botphonic_alt_platforms($alt);
$alt_n = count($alt_platforms);
$alt_rivals = $alt_n - 1;

// The rivals shown before the reader picks; the phone layout starts at one.
$alt_default = min(3, $alt_rivals);

// Prices and measurements in bold. Runs on escaped text and only wraps
// matches, so nothing in the value is dropped.
$alt_vs_figures = static function ($text) {
	return preg_replace_callback(
		'/(\$\d[\d,]*(?:\.\d+)?(?:\/(?:min|mo|month|message|call))?)|(\b\d[\d,.]*(?:\+|%|\s?ms\b))/',
		static function ($m) {
			return '<b class="bpg-alt-vs__fig">' . ('' !== $m[1] ? $m[1] : $m[2]) . '</b>';
		},
		esc_html($text)
	);
};

// Split a list on ", " and "; " outside parentheses, keeping each separator so
// it can be printed back for screen readers.
$alt_vs_split = static function ($text) {
	$parts = array();
	$buffer = '';
	$depth = 0;
	$length = strlen($text);

	for ($i = 0; $i < $length; $i++) {
		$char = $text[$i];

		if ('(' === $char) {
			$depth++;
		} elseif (')' === $char) {
			$depth = max(0, $depth - 1);
		}

		if (0 === $depth && (',' === $char || ';' === $char) && ' ' === substr($text, $i + 1, 1)) {
			$parts[] = array('text' => trim($buffer), 'sep' => $char . ' ');
			$buffer = '';
			$i++;
			continue;
		}

		$buffer .= $char;
	}

	$parts[] = array('text' => trim($buffer), 'sep' => '');

	return array_values(
		array_filter(
			$parts,
			static function ($part) {
				return '' !== $part['text'];
			}
		)
	);
};

// A feature reduced to its label and one rendered cell per platform.
$alt_vs_row = static function ($label, $brand_raw, $competitor_values, callable $render) use ($alt_n, $alt_vs_figures, $alt_vs_split) {
	$values = is_array($competitor_values) ? array_values($competitor_values) : array();
	$raws = array(trim((string) $brand_raw));

	for ($i = 1; $i < $alt_n; $i++) {
		$raws[] = isset($values[$i - 1]['value']) ? trim((string) $values[$i - 1]['value']) : '';
	}

	$tags = (bool) preg_match('/integrat|complian|security|standard/i', (string) $label);
	$cells = array();

	foreach ($raws as $raw) {
		$html = $render($raw);

		if (preg_match('/bpg-alt-mark--(yes|no)/', $html, $m)) {
			// The tick is aria-hidden; say it.
			$html = preg_replace('#</svg>#', '</svg><span class="screen-reader-text">' . esc_html('yes' === $m[1] ? __('Yes', 'botphonic') : __('No', 'botphonic')) . '</span>', $html, 1);
		} elseif (false !== strpos($html, 'bpg-alt-cell__text')) {
			$parts = $tags ? $alt_vs_split($raw) : array();

			if (count($parts) > 1) {
				$html = '<span class="bpg-alt-vs__tags">';
				foreach ($parts as $part) {
					$text = $part['text'];

					if (preg_match('/^(including\s+)(.+)$/i', $text, $lead)) {
						$html .= '<span class="bpg-alt-vs__lead">' . esc_html($lead[1]) . '</span>';
						$text = $lead[2];
					}

					$html .= '<span class="bpg-alt-vs__tag">' . $alt_vs_figures($text) . '</span>';

					if ('' !== $part['sep']) {
						$html .= '<span class="bpg-alt-vs__sep screen-reader-text">' . esc_html($part['sep']) . '</span>';
					}
				}
				$html .= '</span>';
			} else {
				$html = '<span class="bpg-alt-vs__text">' . $alt_vs_figures($raw) . '</span>';
			}
		}

		$cells[] = $html;
	}

	return array(
		'label' => (string) $label,
		'cells' => $cells,
	);
};

$alt_sections = array();

$alt_glance = array();
foreach ((isset($alt['glance_rows']) ? $alt['glance_rows'] : array()) as $alt_g) {
	$alt_type = isset($alt_g['value_type']) ? $alt_g['value_type'] : 'text';
	$alt_glance[] = $alt_vs_row(
		isset($alt_g['label']) ? $alt_g['label'] : '',
		isset($alt_g['botphonic_value']) ? $alt_g['botphonic_value'] : '',
		isset($alt_g['competitor_values']) ? $alt_g['competitor_values'] : array(),
		static function ($raw) use ($alt_type) {
			return botphonic_alt_glance_value($raw, $alt_type);
		}
	);
}
if ($alt_glance) {
	$alt_sections[] = array(
		'title' => __('At a glance', 'botphonic'),
		'rows' => $alt_glance,
	);
}

foreach ((isset($alt['categories']) ? $alt['categories'] : array()) as $alt_cat) {
	$alt_rows = array();
	foreach (array_values($alt_cat['features']) as $alt_f) {
		$alt_rows[] = $alt_vs_row(
			isset($alt_f['feature_name']) ? $alt_f['feature_name'] : '',
			isset($alt_f['botphonic_value']) ? $alt_f['botphonic_value'] : '',
			isset($alt_f['competitor_values']) ? $alt_f['competitor_values'] : array(),
			'botphonic_alt_cell'
		);
	}
	$alt_sections[] = array(
		'title' => isset($alt_cat['category_name']) ? (string) $alt_cat['category_name'] : '',
		'rows' => $alt_rows,
	);
}

// Logo, or the platform's initial when it has none. The name is printed beside
// it, so the image is decorative.
$alt_vs_logo = static function (array $platform) {
	if ($platform['logo']) {
		$img = wp_get_attachment_image(
			$platform['logo'],
			'medium',
			false,
			array(
				'alt' => '',
				'loading' => 'lazy',
				'decoding' => 'async',
			)
		);

		if ($img) {
			return '<span class="bpg-alt-vs__logo">' . $img . '</span>';
		}
	}

	$initial = function_exists('mb_substr') ? mb_substr($platform['name'], 0, 1) : substr($platform['name'], 0, 1);

	return '<span class="bpg-alt-vs__logo bpg-alt-vs__logo--mono" aria-hidden="true"><span>' . esc_html($initial) . '</span></span>';
};

$alt_vs_check = botphonic_alt_icon('check');
?>

<div class="bpg-alt-vs" data-bpg-vs
	data-bpg-vs-msg-min="<?php echo esc_attr(sprintf(/* translators: %s: brand name. */ __('Keep at least one platform to compare with %s.', 'botphonic'), $alt_brand)); ?>"
	style="--bpg-alt-vs-cols:<?php echo (int) $alt_n; ?>">
	<div class="bpg-alt-vs__frame">

		<div class="bpg-alt-vs__top">
			<div class="bpg-alt-vs__picker-head">
				<p class="bpg-alt-vs__picker-title">
					<?php
					printf(
						/* translators: %s: brand name. */
						esc_html__('Compare %s with', 'botphonic'),
						esc_html($alt_brand)
					);
					?>
				</p>
				<p class="bpg-alt-vs__picker-meta">
					<?php
					printf(
						/* translators: 1: number selected, 2: number available. */
						esc_html__('%1$s of %2$s selected', 'botphonic'),
						'<b data-bpg-vs-count>' . esc_html(number_format_i18n($alt_default)) . '</b>',
						esc_html(number_format_i18n($alt_rivals))
					);
					?>
				</p>
			</div>
			<div class="bpg-alt-vs__chips" role="group" aria-label="<?php esc_attr_e('Platforms to compare', 'botphonic'); ?>">
				<button class="bpg-alt-vs__chip bpg-alt-vs__chip--all" type="button" aria-pressed="<?php echo $alt_default === $alt_rivals ? 'true' : 'false'; ?>" data-bpg-vs-all>
					<span class="bpg-alt-vs__chip-box" aria-hidden="true"><?php echo $alt_vs_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG from a fixed whitelist. ?></span>
					<?php esc_html_e('All platforms', 'botphonic'); ?>
				</button>
				<?php foreach ($alt_platforms as $alt_p => $alt_platform): ?>
					<?php if ($alt_platform['brand']) {
						continue;
					} ?>
					<button class="bpg-alt-vs__chip" type="button" aria-pressed="<?php echo $alt_p <= $alt_default ? 'true' : 'false'; ?>" data-bpg-vs-pick="<?php echo (int) $alt_p; ?>">
						<span class="bpg-alt-vs__chip-box" aria-hidden="true"><?php echo $alt_vs_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG from a fixed whitelist. ?></span>
						<?php echo esc_html($alt_platform['name']); ?>
					</button>
				<?php endforeach; ?>
			</div>
			<p class="bpg-alt-vs__note" data-bpg-vs-note aria-live="polite"></p>
		</div>

		<div class="bpg-alt-vs__scroll" tabindex="0" role="region" aria-label="<?php esc_attr_e('Feature comparison', 'botphonic'); ?>">
			<table class="bpg-alt-vs__table" role="table">

				<thead role="rowgroup">
					<tr class="bpg-alt-vs__head" role="row">
						<th class="bpg-alt-vs__corner" scope="col" role="columnheader"><?php esc_html_e('Feature', 'botphonic'); ?></th>
						<?php foreach ($alt_platforms as $alt_p => $alt_platform): ?>
							<th scope="col" role="columnheader" data-p="<?php echo (int) $alt_p; ?>"<?php echo $alt_platform['brand'] ? ' class="is-brand"' : ''; ?>>
								<span class="bpg-alt-vs__col">
									<?php if ($alt_platform['brand']): ?>
										<span class="bpg-alt-vs__pick"><?php esc_html_e('Top pick', 'botphonic'); ?></span>
									<?php else: ?>
										<button class="bpg-alt-vs__remove" type="button" data-bpg-vs-remove="<?php echo (int) $alt_p; ?>" aria-label="<?php echo esc_attr(sprintf(/* translators: %s: platform name. */ __('Remove %s from the comparison', 'botphonic'), $alt_platform['name'])); ?>" hidden>
											<?php echo botphonic_alt_icon('close'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG from a fixed whitelist. ?>
											<span aria-hidden="true"><?php esc_html_e('Remove', 'botphonic'); ?></span>
										</button>
									<?php endif; ?>
									<?php echo $alt_vs_logo($alt_platform); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the closure. ?>
									<span class="bpg-alt-vs__col-name"><?php echo esc_html($alt_platform['name']); ?></span>
								</span>
							</th>
						<?php endforeach; ?>
					</tr>
				</thead>

				<?php foreach ($alt_sections as $alt_s => $alt_section): ?>
					<?php $alt_body_id = 'bpg-alt-vs-body-' . $alt_s; ?>
					<tbody class="bpg-alt-vs__sec" role="rowgroup">
						<tr class="bpg-alt-vs__sec-head" role="row">
							<?php // One cell across the row; alternatives.js keeps the span equal to the visible columns. ?>
							<th class="bpg-alt-vs__sec-th" colspan="<?php echo (int) ($alt_n + 1); ?>" scope="colgroup" role="rowheader">
								<span class="bpg-alt-vs__pin">
									<?php if (0 === $alt_s): ?>
										<?php // "At a glance" is the summary every reader needs; it never folds. ?>
										<span class="bpg-alt-vs__sec-btn is-static">
											<span class="bpg-alt-vs__sec-name"><?php echo esc_html($alt_section['title']); ?></span>
										</span>
									<?php else: ?>
										<button class="bpg-alt-vs__sec-btn" type="button" aria-expanded="true" aria-controls="<?php echo esc_attr($alt_body_id); ?>" data-bpg-vs-sec>
											<span class="bpg-alt-vs__sec-name"><?php echo esc_html($alt_section['title']); ?></span>
											<span class="bpg-alt-vs__sec-chev" aria-hidden="true"><?php echo botphonic_alt_icon('chevron-down'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG from a fixed whitelist. ?></span>
										</button>
									<?php endif; ?>
								</span>
							</th>
						</tr>
					</tbody>
					<tbody class="bpg-alt-vs__body" id="<?php echo esc_attr($alt_body_id); ?>" role="rowgroup">
						<?php foreach ($alt_section['rows'] as $alt_row): ?>
							<tr class="bpg-alt-vs__row" role="row">
								<th class="bpg-alt-vs__label" scope="row" role="rowheader">
									<span class="bpg-alt-vs__label-in"><?php echo esc_html($alt_row['label']); ?></span>
								</th>
								<?php foreach ($alt_row['cells'] as $alt_p => $alt_cell): ?>
									<td role="cell" data-p="<?php echo (int) $alt_p; ?>"<?php echo 0 === $alt_p ? ' class="is-brand"' : ''; ?>>
										<?php echo $alt_cell; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in botphonic_alt_cell() / botphonic_alt_glance_value() or the closures above. ?>
									</td>
								<?php endforeach; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				<?php endforeach; ?>

				<?php // The CTA closes the brand column, under Botphonic's own values. ?>
				<tfoot role="rowgroup">
					<tr class="bpg-alt-vs__cta" role="row">
						<th scope="row" role="rowheader"><span class="screen-reader-text"><?php echo esc_html($alt_brand); ?></span></th>
						<?php for ($alt_p = 0; $alt_p < $alt_n; $alt_p++): ?>
							<td role="cell" data-p="<?php echo (int) $alt_p; ?>"<?php echo 0 === $alt_p ? ' class="is-brand"' : ''; ?>>
								<?php if (0 === $alt_p): ?>
									<a class="bpg-alt-matrix__btn bpg-alt-vs__btn" href="<?php echo esc_url($alt['cta_url']); ?>" <?php echo botphonic_alt_link_target($alt['cta_url']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed attribute string. ?>>
										<?php echo esc_html($alt['compare_cta']); ?>
									</a>
								<?php endif; ?>
							</td>
						<?php endfor; ?>
					</tr>
				</tfoot>

			</table>
		</div>

	</div>
</div>
