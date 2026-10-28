<?php
/**
 * Botphonic Call Abandonment Rate Calculator.
 * Shortcode: [botphonic_abandonment_calculator]
 * @package Botphonic
 */
defined('ABSPATH') || exit;
if (!function_exists('botphonic_abandonment_enqueue_assets')) {
	function botphonic_abandonment_enqueue_assets()
	{
		$theme_dir = get_stylesheet_directory();
		$theme_uri = get_stylesheet_directory_uri();
		$css = '/assets/css/call-abandonment-calculator.css';
		$js = '/assets/js/call-abandonment-calculator.js';
		if (file_exists($theme_dir . $css)) {
			wp_enqueue_style('botphonic-abandonment-calculator', $theme_uri . $css, array(), filemtime($theme_dir . $css));
		}

		if (file_exists($theme_dir . $js)) {
			wp_enqueue_script('botphonic-abandonment-calculator', $theme_uri . $js, array(), filemtime($theme_dir . $js), true);
		}
	}
}

if (!function_exists('botphonic_render_abandonment_calculator')) {
	function botphonic_render_abandonment_calculator($atts = array())
	{
		$atts = shortcode_atts(array('title' => 'Calculate your abandonment rate'),$atts,'botphonic_abandonment_calculator');
		botphonic_abandonment_enqueue_assets();
		$id = wp_unique_id('bpg-abnd-');
		$title_id = $id . '-title';
		$abandoned_id = $id . '-abandoned';
		$incoming_id = $id . '-incoming';
		$error_id = $id . '-error';
		$result_id = $id . '-result';
		ob_start();
?>
<section class="bpg-abnd" aria-labelledby="<?php echo esc_attr($title_id); ?>">
	<div class="bpg-abnd__heading">
		<span class="bpg-abnd__icon" aria-hidden="true">
			<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
				<path d="M22 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 3.1 5.2 2 2 0 0 1 5.1 3h3a2 2 0 0 1 2 1.7c.13.95.36 1.88.69 2.77a2 2 0 0 1-.45 2.11L9.1 10.9a16 16 0 0 0 4 4l1.32-1.24a2 2 0 0 1 2.1-.45 18 18 0 0 0 2.78.69A2 2 0 0 1 22 16.9Z" />
			</svg>
		</span>
		<div>
			<h3 id="<?php echo esc_attr($title_id); ?>" class="bpg-abnd__title"><?php echo esc_html($atts['title']); ?></h3>
			<p class="bpg-abnd__subtitle">Enter your call numbers to get an instant result.</p>
		</div>
	</div>

	<form class="bpg-abnd__form" novalidate>
		<div class="bpg-abnd__field">
			<label for="<?php echo esc_attr($abandoned_id); ?>">Total number of abandoned calls</label>
			<input id="<?php echo esc_attr($abandoned_id); ?>" name="abandoned_calls" type="number" min="0" step="1" inputmode="numeric" placeholder="e.g. 120" required aria-describedby="<?php echo esc_attr($error_id); ?>">
		</div>
		<div class="bpg-abnd__field">
			<label for="<?php echo esc_attr($incoming_id); ?>">Total number of incoming calls</label>
			<input id="<?php echo esc_attr($incoming_id); ?>" name="incoming_calls" type="number" min="1" step="1" inputmode="numeric" placeholder="e.g. 1000" required aria-describedby="<?php echo esc_attr($error_id); ?>">
		</div>
		<p class="bpg-abnd__error" id="<?php echo esc_attr($error_id); ?>" role="alert" hidden></p>
		<div class="bpg-abnd__actions">
			<button type="submit" class="bpg-abnd__submit">Calculate abandonment rate</button>
			<button type="reset" class="bpg-abnd__reset">Reset</button>
		</div>
	</form>

	<div class="bpg-abnd__result" role="status" aria-live="polite" aria-atomic="true">
		<span class="bpg-abnd__result-label">Your call abandonment rate</span>
		<strong class="bpg-abnd__result-value" id="<?php echo esc_attr($result_id); ?>" data-result>—</strong>
		<span class="bpg-abnd__result-detail" data-detail>Enter your call counts to calculate the result.</span>
	</div>
</section>
<?php
		return ob_get_clean();
	}
	add_shortcode('botphonic_abandonment_calculator', 'botphonic_render_abandonment_calculator');
}