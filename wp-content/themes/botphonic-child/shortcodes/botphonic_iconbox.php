<?php

/**
 * Shortcode: [botphonic_iconbox]
 * Description: Renders a static audio player with hardcoded audio file and label text.
 */

add_shortcode('botphonic_iconbox', 'botphonic_render_iconbox');

function botphonic_render_iconbox()
{
	ob_start();
	wp_enqueue_style('iconbox');

	// Fallback content
	$pretitle = get_field('section_pre_title') ?: "BENEFITS OF AI VOICE AGENTS";
	$title = get_field('section_title') ?: 'Why <span class="custom-gradient-text">AI Voice Agents </span><br>Make a Difference';
	$text = get_field('section_text') ?: 'AI Voice Agents help teams stay available 24/7, reduce call volume, and cut costs without sacrificing service.';

	// Get icon boxes
	$botphonic_features = get_field('icon_box') ?: [];
	if (empty($botphonic_features)) return '';

	// Get grid columns from ACF
	$showBox = (int) get_field('box_grid') ?: 3;
	$colClass = 'col-md-4';

	// Map number of boxes to Bootstrap classes
	switch ($showBox) {
		case 2: $colClass = 'col-md-6'; break;
		case 3: $colClass = 'col-md-4'; break;
		case 4: $colClass = 'col-md-3'; break;
		case 5: $colClass = 'custom-grid'; break;
	}
?>

<section class="icon-box-section section-padded">
	<div class="container">
		<div class="text-center mb-5 mx-auto" style="max-width: 800px;">
			<div class="text-gradient small-text text-uppercase h6"><?= esc_html($pretitle); ?></div>
			<h2 class="fw-bold display-6"><?= wp_kses_post($title); ?></h2>
			<p class="lead text-muted mt-3"><?= esc_html($text); ?></p>
		</div>

		<div class="row g-4 icon-grid icon-grid-<?= esc_attr($showBox); ?>">
			<?php foreach ($botphonic_features as $feature) : ?>
			<div class="<?= esc_attr($colClass); ?>">
				<div class="icon-box p-4 h-100">
					<div class="d-flex align-items-center mb-1">
						<div class="icon me-2"><?= ($feature['icon']); ?></div>
						<div class="fw-semibold h5 mb-0"><?= esc_html($feature['title']); ?></div>
					</div>
					<div class="text-muted"><?= esc_html($feature['text']); ?></div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
	return ob_get_clean();
}