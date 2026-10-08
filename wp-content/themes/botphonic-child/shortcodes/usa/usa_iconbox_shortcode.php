<?php
/**
 * Shortcode: [usa_iconbox]
 * Description: Renders Icon Box sections from ACF Flexible Content
 */

add_shortcode('usa_iconbox', 'botphonic_usa_iconbox');

function botphonic_usa_iconbox($atts)
{
	ob_start();

	$atts = shortcode_atts([
		'index' => null,
	], $atts);

	$current_index = 0;

	wp_enqueue_style('iconbox');

	// Reset ACF repeater pointer
	if (have_rows('usa_page_sections')) {
		// Important: reset the pointer to start from the top
		reset_rows();

		while (have_rows('usa_page_sections')) {
			the_row();

			if (get_row_layout() !== 'icon_box_section') 
				continue;

			$current_index++;

			if ($atts['index'] && (int)$atts['index'] !== $current_index) {
				continue;
			}

			$pretitle = get_sub_field('section_pre_title');
			$title    = get_sub_field('section_title');
			$text     = get_sub_field('section_text');
			$grid     = (int) get_sub_field('box_grid') ?: 3;
			$features = get_sub_field('icon_box');

			switch ($grid) {
				case 3: $colClass = 'col-md-4'; break;
				case 4: $colClass = 'col-md-3'; break;
				case 6: $colClass = 'col-md-2'; break;
				case 5:
				case 7: $colClass = 'custom-grid'; break;
			}

			if (empty($features)) continue;
?>

<section class="icon-box-section section-padded">
	<div class="container">
		<div class="text-center mb-5">
			<div class="text-gradient small-text text-uppercase h6"><?= esc_html($pretitle); ?></div>
			<h2 class="fw-bold display-6"><?php echo wp_kses_post($title); ?></h2>
			<p class="lead text-muted mx-auto mt-3" style="max-width:900px;"><?= esc_html($text); ?></p>
		</div>
		<div class="row g-4 icon-grid-<?= esc_attr($grid); ?>">
			<?php foreach ($features as $feature) : ?>
			<div class="<?= esc_attr($colClass); ?>">
				<div class="icon-box p-4 h-100">
					<div class="d-flex align-items-center mb-2">
						<div class="icon me-2"><?= $feature['icon']; ?></div>
						<div class="fw-semibold h5 mb-0"><?= $feature['title']; ?></div>
					</div>
					<div class="text-muted"><?= $feature['text']; ?></div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
			if ($atts['index']) break;
		}
	}

	return ob_get_clean();
}