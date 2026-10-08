<?php
/**
 * Shortcode: [botphonic_integration_slider]
 */
add_shortcode('botphonic_integration_slider', 'botphonic_integration_slider');
function botphonic_integration_slider($atts)
{
	static $css_printed = false;
	$atts = shortcode_atts(
		array(
			'img'   => 'https://botphonic.ai/wp-content/plugins/botPhonic/assets/img/integration_logo.webp',
			'speed' => '70s',
			'blur'  => '6px',
			'contrast' => '105%',
		),
		$atts,
		'botphonic_integration_slider'
	);
	$img      = esc_url($atts['img']);
	$speed    = esc_attr($atts['speed']);
	$blur     = esc_attr($atts['blur']);
	$contrast = esc_attr($atts['contrast']);
	ob_start();
?>
<?php if (!$css_printed) : $css_printed = true; ?>
<style>
	.botphonic-carousel { position: relative; height: 90px; width: 100%; overflow: hidden; margin: 20px 0; isolation: isolate; }
	.botphonic-carousel .botphonic-logos { position: absolute; inset: 0; background-repeat: repeat-x; background-position: 0 50%; background-size: auto 80%; will-change: background-position; animation: botphonicMove linear infinite; transform: translateZ(0); }
	.botphonic-carousel .botphonic-mask { position: absolute; inset: 0; pointer-events: none; backdrop-filter: blur(var(--blur)) contrast(var(--contrast)); -webkit-backdrop-filter: blur(var(--blur)) contrast(var(--contrast)); -webkit-mask: linear-gradient(90deg, #000 50px, #0000 175px calc(100% - 175px), #fff calc(100% - 50px)); }
	@keyframes botphonicMove {
		from { background-position: 0 50%; }
		to { background-position: -3000px 50%; }
	}
</style>
<?php endif; ?>
<div class="botphonic-carousel" style="--blur: <?php echo $blur; ?>; --contrast: <?php echo $contrast; ?>;">
	<div class="botphonic-logos" style="background-image: url('<?php echo $img; ?>'); animation-duration: <?php echo $speed; ?>;"></div>
	<div class="botphonic-mask"></div>
</div>
<?php
	return ob_get_clean();
}