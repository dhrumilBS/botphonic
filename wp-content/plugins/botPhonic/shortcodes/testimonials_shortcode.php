<?php
function testimonials_shortcode()
{
	wp_enqueue_style('botphonic-Swiper');
	wp_enqueue_script('botphonic-Swiper');
	ob_start();
?>
	<style>
		.testimonial-section { background: #f8fafc; padding: 80px 20px; }
		.testimonial-heading { text-align: center; margin-bottom: 60px; }
		.testimonial-heading h2 { font-size: 36px; color: #0f172a; font-weight: 800; margin-bottom: 12px; }
		.testimonial-heading p { color: #475569; max-width: 780px; margin: 0 auto; }
		.testimonial-grid { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 40px; max-width: 1200px; margin: 0 auto; }
		.testimonial-left { flex: 1 1 50%; text-align: center; }
		.testimonial-left img { max-width: 100%; width: 100%; height: auto; object-fit: contain; display: block; margin: 0 auto; }
		.testimonial-right { flex: 1 1 45%; min-width: 300px; position: relative; }
		.testimonial-card { background: #fff; padding: 32px; border-radius: 16px; border: 1px solid #e2e8f0; position: relative; }
		.testimonial-card p { color: #1e293b; margin-bottom: 20px; line-height: 1.6; }
		.testimonial-card .author { font-weight: 700; color: #0f172a; }
		.testimonial-card .role { font-size: 14px; color: #64748b; }
		.testimonial-card::after { content: "”"; position: absolute; font-size: 64px; bottom: 20px; right: 30px; color: #1e293b; font-family: serif; line-height: 1; }
		.swiper-pagination { position: relative !important; margin-top: 30px !important; display: flex !important; justify-content: center !important; align-items: center !important; gap: 10px !important; }
		.swiper-pagination-bullet { background: #cbd5e1; width: 10px; height: 10px; opacity: 1; }
		.swiper-pagination-bullet-active { background: #0f172a; }

		@media (min-width: 992px) {
			.testimonial-left img { max-height: 400px; }		
		}

		@media (max-width: 991px) {
			.testimonial-grid { flex-direction: column; text-align: center; align-items: center; }
			.testimonial-left { width: 100%; max-width: 400px; margin: 0 auto 30px; }
			.testimonial-left img { max-width: 100%; height: auto; display: block; margin: 0 auto; }
			.testimonial-right { width: 100%; }		
		}
	</style>

	<section class="testimonial-section">
		<div class="testimonial-heading">
			<h2>Look What Our Customers Say</h2>
			<p>Explore the incredible experience of Botphonic clients and comprehend our extensive potential. We assist you to attract more clients’ and improve operations speed.</p>
		</div>

		<div class="testimonial-grid">
			<div class="testimonial-left">
				<img src="https://botphonic.ai/wp-content/uploads/2025/07/Testinomial.webp" alt="Botphonic Customer Orbit">
			</div>

			<div class="testimonial-right">
				<div class="testimonial-slider swiper">
					<div class="swiper-wrapper">
						<?php
						if (have_rows('testimonials', 'option')) {
							while (have_rows('testimonials', 'option')) {
								the_row();
						?>
								<div class="swiper-slide">
									<div class="testimonial-card">
										<p><?= get_sub_field('quote'); ?></p>
										<div class="author"><?= get_sub_field('name'); ?></div>
										<div class="role"><?= get_sub_field('position'); ?></div>
									</div>
								</div>
						<?php }
						} ?>
					</div>
				</div>
				<!-- Move this outside the swiper -->
				<div class="swiper-pagination"></div>
			</div>
		</div>
	</section>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			new Swiper('.testimonial-slider', {
				slidesPerView: 1,
				spaceBetween: 30,
				loop: true,
				pagination: {
					el: '.testimonial-right .swiper-pagination', // precise targeting
					clickable: true,
				},
				autoplay: {
					delay: 6000,
					disableOnInteraction: false,
				}
			});
		});
	</script>
<?php
	return ob_get_clean();
}
add_shortcode('botphonic_testimonials', 'testimonials_shortcode');
