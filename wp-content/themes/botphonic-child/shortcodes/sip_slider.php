<?php
/* Shortcode: [sip_slider] */

if (!defined('ABSPATH')) exit;

function botphonic_sip_slider_assets()
{
	if (!is_singular()) return;
	global $post;
	if (
		!isset($post->post_content) ||
		!has_shortcode($post->post_content, 'sip_slider')
	) {
		return;
	}
}
add_action('wp_enqueue_scripts', 'botphonic_sip_slider_assets');
function botphonic_sip_slider_shortcode()
{
	ob_start(); ?>
<style>
	.sip-section { border-radius:20px; padding:20px 0; overflow:hidden }
	.marquee { position:relative; width:100%; overflow:hidden; margin:16px 0 }
	.marquee:before,.marquee:after { content:""; position:absolute; top:0; width:80px; height:100%; z-index:2; pointer-events:none }
	.marquee:before { left:0; background:linear-gradient(to right,#fff 20%,transparent) }
	.marquee:after { right:0; background:linear-gradient(to left,#fff 20%,transparent) }
	.marquee-track { display:flex; margin-bottom:20px; width:max-content; will-change:transform; }
	.sip-section .marquee.reverse { margin-top:32px }
	.marquee-track img { margin:0 30px; height:50px!important; width:auto; flex-shrink:0; }
	@media(max-width:600px) { .marquee-track img { margin:0 18px; } }
	@keyframes scroll-left {
		from { transform:translateX(0) }
		to { transform:translateX(calc(-1 * var(--marquee-width))) }
	}
	@keyframes scroll-right {
		from { transform:translateX(calc(-1 * var(--marquee-width))) }
		to { transform:translateX(0) }
	}
</style>
<div class="sip-section">
	<div class="marquee">
		<div class="marquee-track">
			<img src="https://botphonic.ai/wp-content/uploads/2026/01/Twilio.svg" alt="Twilio SIP Trunking" width="150" height="50" loading="lazy" decoding="async">
			<img src="https://botphonic.ai/wp-content/uploads/2026/01/Telnyx.svg" alt="Telnyx SIP Trunking" width="150" height="50" loading="lazy" decoding="async">
			<img src="https://botphonic.ai/wp-content/uploads/2026/01/Plivo.svg" alt="Plivo SIP Trunking" width="150" height="50" loading="lazy" decoding="async">
		</div>
	</div>
	<div class="marquee reverse">
		<div class="marquee-track">
			<img src="https://botphonic.ai/wp-content/uploads/2026/01/Plivo.svg" alt="Plivo SIP Trunking" width="150" height="50" loading="lazy" decoding="async">
			<img src="https://botphonic.ai/wp-content/uploads/2026/01/Telnyx.svg" alt="Telnyx SIP Trunking" width="150" height="50" loading="lazy" decoding="async">
			<img src="https://botphonic.ai/wp-content/uploads/2026/01/Twilio.svg" alt="Twilio SIP Trunking" width="150" height="50" loading="lazy" decoding="async">
		</div>
	</div>
</div>
<script>
	document.addEventListener("DOMContentLoaded", function(){
		document.querySelectorAll(".marquee").forEach(function(row){
			if(row.dataset.initialized) return;
			row.dataset.initialized = "true";
			const track = row.querySelector(".marquee-track");
			if(!track) return;
			const images = track.querySelectorAll("img");
			function initMarquee() {
				if(track.dataset.cloned) return;
				track.dataset.cloned = "true";
				const originalWidth = track.scrollWidth;
				track.innerHTML += track.innerHTML;
				track.style.setProperty("--marquee-width", originalWidth + "px");
				track.style.animation = row.classList.contains("reverse")
					? "scroll-right 34s linear infinite"
				: "scroll-left 28s linear infinite";
			}
			let loaded = 0;
			if(images.length === 0) {
				initMarquee();
				return;
			}
			images.forEach(function(img){
				if(img.complete) {
					loaded++;
					if(loaded === images.length) initMarquee();
				} else {
					img.addEventListener("load", function(){
						loaded++;
						if(loaded === images.length) initMarquee();
					});
				}
			});
			row.addEventListener("pointerdown", function(){
				track.style.animationPlayState = "paused";
			});
			["pointerup","pointerleave","pointercancel"].forEach(function(event){
				row.addEventListener(event, function(){
					track.style.animationPlayState = "running";
				});
			});
		});
	});
</script>
<?php
	return ob_get_clean();
}
add_shortcode('sip_slider', 'botphonic_sip_slider_shortcode');
