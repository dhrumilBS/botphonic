<?php
if (!defined('ABSPATH')) exit;

function botphonic_commission_assets()
{

	if (!is_singular()) return;

	global $post;

	if (
		!isset($post->post_content) ||
		!has_shortcode($post->post_content, 'commission_calculator')
	) {
		return;
	}

	wp_register_style('botphonic-commission-style', false);
	wp_enqueue_style('botphonic-commission-style');

	wp_register_script('botphonic-commission-js', false, [], null, true);
	wp_enqueue_script('botphonic-commission-js');

	wp_add_inline_style('botphonic-commission-style', '
        .commission-wrapper {  max-width:1024px;  margin:0 auto;  padding:16px;  background:#fff;  border-radius:12px;  box-shadow:0 3px 15px rgba(0,0,0,.06); }
        .commission-header {  text-align:center;  background:#f0f4ff;  padding:.8rem 1.2rem;  border-radius:8px; }
        .commission-display { display:flex; justify-content:space-evenly; flex-wrap:wrap; margin:2rem 0; gap:1.5rem; text-align:center; }
        .commission-value { font-size:2.4rem; font-weight:600; color:#4f46e5; }
        .commission-label { font-size:.95rem; color:#666; }
        .commission-slider { margin:1rem 0; }
        .commission-slider input[type=range]{ width:100%; height:6px; background:#e7ebff; border-radius:5px; outline:none; -webkit-appearance:none; }
        .commission-slider input[type=range]::-webkit-slider-thumb{ -webkit-appearance:none; width:22px; height:22px; border-radius:50%; background:#4f46e5; border:2px solid #fff; cursor:pointer; }
        @media(max-width:768px){ 
		.commission-display{flex-direction:column;} 
		.commission-value{font-size:2rem;} 
		} 
	');

	wp_add_inline_script('botphonic-commission-js', '
    document.addEventListener("DOMContentLoaded", function(){
        document.querySelectorAll(".commission-wrapper").forEach(function(wrapper){
            const slider = wrapper.querySelector(".commission-range");
            const commissionLabel = wrapper.querySelector(".commission-output");
            const accountLabel = wrapper.querySelector(".commission-accounts");
            const percent = parseFloat(wrapper.dataset.percent);
            const price   = parseFloat(wrapper.dataset.price);

            if(!slider) return;
            slider.addEventListener("input", function(){
                const accounts = Number(slider.value);
                const totalCommission = accounts * price * percent;
                commissionLabel.textContent = "$" + totalCommission.toFixed(2);
                accountLabel.textContent = accounts;
            });
        });
    });
    ');
}
add_action('wp_enqueue_scripts', 'botphonic_commission_assets');

function commission_calculator_shortcode($atts)
{
	$atts = shortcode_atts([
		'percent' => 25,
		'price'   => 100,
	], $atts, 'commission_calculator');
	$percent = floatval($atts['percent']) / 100;
	$price   = floatval($atts['price']);

	ob_start(); ?>

	<section class="commission-wrapper"
		data-percent="<?php echo esc_attr($percent); ?>"
		data-price="<?php echo esc_attr($price); ?>">

		<div class="commission-header">Affiliate Commission <strong><?php echo esc_html($atts['percent']); ?>%</strong></div>
		<div class="commission-display">
			<div>
				<div class="commission-value commission-output">$0.00</div>
				<div class="commission-label">Your Monthly Commission</div>
			</div>

			<div>
				<div class="commission-value commission-accounts">0</div>
				<div class="commission-label">Botphonic AI Assistant Accounts</div>
			</div>
		</div>
		<div class="commission-slider">
			<input type="range" class="commission-range" min="0" max="100" step="1" value="0" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
		</div>
	</section>
<?php
	return ob_get_clean();
}
add_shortcode('commission_calculator', 'commission_calculator_shortcode');
