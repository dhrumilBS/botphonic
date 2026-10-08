<?php
add_shortcode('botphonic_testimonials', 'testimonials_shortcode');

function testimonials_shortcode() {
	wp_enqueue_style('swiper-css');
	wp_enqueue_script('swiper-js');

	ob_start();
?>
<style>
	.testimonial-heading { margin-bottom: 60px; text-align: center; }
	.testimonial-heading p { margin: 0 auto; max-width: 780px; }
	.testimonial-grid { align-items: center; display: flex; flex-wrap: wrap; gap: 40px; justify-content: center; margin: 0 auto; max-width: 1200px; }
	.testimonial-left { flex: 1 1 50%; text-align: center; }
	.testimonial-left img { display: block; width: 100%; height: auto; max-width: 100%; object-fit: contain; aspect-ratio: 4 / 3; }	
	.testimonial-right { flex: 1 1 45%; min-width: 300px; position: relative; }
	.testimonial-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 30px; position: relative; will-change: transform; transform: translateZ(0); }
	.testimonial-card .author { color: #0f172a; font-weight: 700; }
	.testimonial-card .role { color: #64748b; font-size: 14px; }
	.testimonial-card::after { bottom: 20px; color: #1e293b; content: "”"; font-family: var(--site-font); font-size: 64px; line-height: 1; position: absolute; right: 30px; }
	.swiper-pagination { align-items: center !important; visibility: hidden; height: 0; gap: 10px !important; justify-content: center !important; margin-top: 30px !important; position: relative !important; }
	.swiper-pagination-bullet { background: #cbd5e1; height: 10px; opacity: 1; width: 10px; }
	.swiper-pagination-bullet-active { background: #0f172a; }
	@media (min-width: 992px) { .testimonial-left img { max-height: 400px; } }
	@media (max-width: 991px) {
		.testimonial-heading { margin-bottom: 30px; padding: 10px; }
		.testimonial-grid { align-items: center; flex-direction: column; padding: 10px; text-align: center; }
		.testimonial-left { margin: 0 auto 30px; max-width: 400px; width: 100%; }
		.testimonial-left img { display: block; height: auto; margin: 0 auto; max-width: 100%; }
		.testimonial-right { width: 100%; }
		.testimonial-card { padding: 20px; }
	}
</style>

<section class="testimonial-section section-padded">
	<div class="testimonial-heading">
		<div class="text-gradient small-text text-uppercase h6">Testimonials</div>
		<h2>Look What Our Customers Say</h2>
		<p>Explore the incredible experience of Botphonic clients and comprehend our extensive potential. We assist you in attracting more clients and improving operational speed.</p>
	</div>

	<div class="testimonial-grid">
		<div class="testimonial-left">
			<img src="https://botphonic.ai/wp-content/uploads/2026/04/Testimonial.webp" alt="Botphonic Customer Testimonial" width="800" height="600">
		</div>

		<div class="testimonial-right">
			<div class="testimonial-slider swiper">
				<div class="swiper-wrapper">
					<?php if (function_exists('have_rows') && have_rows('testimonials','option')): ?>
					<?php while (have_rows('testimonials', 'option')): the_row(); ?>
					<div class="swiper-slide">
						<div class="testimonial-card">
							<p><?php echo esc_html(get_sub_field('quote')); ?></p>
							<div class="author"><?php echo esc_html(get_sub_field('name')); ?></div>
							<div class="role"><?php echo esc_html(get_sub_field('position')); ?></div>
						</div>
					</div>
					<?php endwhile; ?>
					<?php else: ?>
					<p>No testimonials available right now.</p>
					<?php endif; ?>
				</div>
				<div class="swiper-pagination"></div>
			</div>
		</div>
	</div>
</section>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		if (typeof Swiper !== 'undefined' && document.querySelector('.testimonial-slider')) {
			new Swiper('.testimonial-slider', {
				slidesPerView: 1,
				spaceBetween: 30,
				loop: true,
				autoplay: {
					delay: 6000,
					disableOnInteraction: false,
					pauseOnMouseEnter: true
				},
				speed: 600,
				pagination: {
					el: '.swiper-pagination',
					clickable: true,
				}
			});
		}
	});
</script>

<?php
	return ob_get_clean();
}
