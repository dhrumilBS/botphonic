<?php
function botphonic_sidetabs()
{
	ob_start();
?>
<style>
	.testimonial-section { background: radial-gradient(97.32% 97.32% at 50% 2.68%, rgba(11, 30, 43, 0.00) 0%, #092D45 100%), #0B1E2B; } 

	.testimonial-section .slider-pagination { display: flex; justify-content: center; align-items: center; gap: 22px; } 
	.testimonial-section .slider-pagination .pagingInfo { width: 40px; display: flex; justify-content: center; padding: 8px 4px; font-size: 14px; font-weight: 700; line-height: 1.4; gap: 4px; } 
	.testimonial-section .slider-pagination .pagingInfo .total-slide { opacity: .5; } 
	.testimonial-section .slider-pagination .caro_btn.swiper-button-disabled { opacity: .25; cursor: not-allowed; } 
	.testimonial-section .slider-pagination .caro_btn span { padding: 12px; width: 46px; height: 46px; display: flex; justify-content: center; align-items: center; border-radius: 100px; border: 1px solid #fff; transition: 200ms all ease-in-out; } 
	.testimonial-section .slider-pagination .caro_btn span:hover { background-color: #FFF; } 
	.testimonial-section .slider-pagination .caro_btn span:hover svg,
	.testimonial-section .slider-pagination .caro_btn span:hover path { color: var(--primary); }
	/* .testimonials-wrapper:not(.slick-slider){ display: flex; width: 100%; overflow: hidden;} .testimonials-wrapper:not(.slick-slider) .testimobial-block{ max-width: 33%; } */
	.testimonials-wrapper .swiper-slide { height: auto; } 
	.testimonial-section .testimonials-wrapper .testimobial-block { border-radius: 20px; border: 3px solid #3a5465; padding: 30px; display: flex; flex-direction: column; justify-content: space-between; align-items: flex-start; height: 100%; } 
	.testimonial-section .testimonials-wrapper .testimobial-block .quote-text { font-size: 18px; margin-bottom: 12px; } 
	.testimonial-section .testimonials-wrapper .testimobial-block .doctor-img { width: 64px; } 
	.testimonial-section .testimonials-wrapper .testimobial-block .card-name { line-height: 1.4; } 
	.testimonial-section .testimonials-wrapper .testimobial-block .card-name .name { font-size: 16px; font-weight: 700; letter-spacing: -0.32px; } 
	.testimonial-section .testimonials-wrapper .testimobial-block .card-name .pos { font-size: 14px; font-style: normal; font-weight: 500; opacity: .65; } 
	.testimonial-section .testimonials-wrapper .testimobial-block .dr-img img {
		border-radius: 50px
	}

	@media screen and (max-width: 991px) {
		.testimonial-section .container { max-width: 100%; position: relative; padding-bottom: 50px; } 
		.testimonial-section .slider-pagination { position: absolute; bottom: -12px; left: 0; right: 0; }
	}

	@media screen and (max-width: 575px) {
		.testimonial-section .testimonials-wrapper .testimobial-block { margin: 0; height: auto; padding: 12px; } 
		.testimonial-section .testimonials-wrapper .testimobial-block .quote-text { font-size: 15px; }
	}
</style>
<section class="testimonial-section section-padded dark-bg">
	<div class="container">
		<div class="heading">
			<div class="row align-items-center">
				<div class="col-12 col-lg">
					<span class="pre-title">Testimonials </span>
					<h2 class="title">Look What Our Customers Say </h2>
				</div>
				<div class="col-12 col-lg">
					<p>Explore the incredible experience of Botphonic clients and comprehend our extensive potential. We assist you to attract more clients’ and improve operations speed.</p>
				</div>
				<div class="col-12 col-lg-2">
					<div class="slider-pagination">
						<div class="previous_caro caro_btn">
							<span class="icon w">
								<svg width="17" height="20" viewBox="0 0 17 20" fill="currentcolor" xmlns="http://www.w3.org/2000/svg">
									<path d="M11.7725 2.87751C11.4254 2.53041 10.8658 2.53041 10.5187 2.87751L4.63248 8.76376C4.35623 9.04001 4.35623 9.48626 4.63248 9.76251L10.5187 15.6487C10.8658 15.9958 11.4254 15.9958 11.7725 15.6487C12.1196 15.3016 12.1196 14.742 11.7725 14.395L6.64415 9.25958L11.7796 4.12417C12.1196 3.78417 12.1196 3.21751 11.7725 2.87751Z" />
								</svg>
							</span>
						</div>
						<div class="pagingInfo"></div>
						<div class="next_caro caro_btn">
							<span class="icon">
								<svg width="17" height="20" viewBox="0 0 17 20" fill="currentcolor" xmlns="http://www.w3.org/2000/svg">
									<path d="M5.2275 16.642C5.5746 16.9891 6.1342 16.9891 6.4813 16.642L12.3675 10.7558C12.6438 10.4795 12.6438 10.0333 12.3675 9.75703L6.4813 3.87079C6.1342 3.52369 5.5746 3.52369 5.2275 3.87079C4.8804 4.21789 4.8804 4.77749 5.2275 5.12449L10.3558 10.2599L5.2204 15.3954C4.8804 15.7354 4.8804 16.302 5.2275 16.642Z" />
								</svg>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php if (have_rows('testimonials', 'option')) { ?>
		<div class="testimonials">
			<div class="testimonials-wrapper swiper ">
				<div class="swiper-wrapper">
					<?php while (have_rows('testimonials', 'option')) {
		the_row(); ?>
					<div class="swiper-slide">
						<div class="testimobial-block">
							<div class="quote-text h5"><?= get_sub_field('quote'); ?></div>
							<div class="d-flex justify-content-between w-100">
								<div class="row">
									<div class="col-auto dr-img">
										<?= wp_get_attachment_image(get_sub_field('image'), ['64', '64']); ?>
									</div>
									<div class="col">
										<div class="card-name">
											<div class="name"><?= get_sub_field('name'); ?></div>
											<div class="pos"><?= get_sub_field('position'); ?></div>
										</div>
									</div>
								</div>

								<img src="<?= get_theme_file_uri('assets/img/quote.svg'); ?>" alt="quote" width="40" height="40">
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
</section>
<script>
	document.addEventListener('DOMContentLoaded', function() {
	});
</script>

<?php
	return ob_get_clean();
}
add_shortcode('botphonic_sidetabs', 'botphonic_sidetabs');