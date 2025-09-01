<?php
if (! defined('ABSPATH')) exit; // Exit if accessed directly

class Botphonic_Industry_Slider extends \Elementor\Widget_Base
{

	public function get_name()
	{
		return 'botphonic-industry-slider';
	}

	public function get_title()
	{
		return __('Botphonic Industry Slider', 'botphonic');
	}

	public function get_icon()
	{
		return 'eicon-slider-push';
	}

	public function get_categories()
	{
		return ['botphonic-widgets'];
	}

	public function get_script_depends()
	{
		return ['botphonic-Swiper'];
	}

	public function get_style_depends()
	{
		return ['botphonic-Swiper'];
	}

	protected function register_controls()
	{
		$this->start_controls_section(
			'content_section',
			[
				'label' => __('Industry Slider', 'botphonic'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'industry_title',
			[
				'label' => __('Title', 'botphonic'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __('Industry Title', 'botphonic'),
			]
		);

		$repeater->add_control(
			'industry_description',
			[
				'label' => __('Description', 'botphonic'),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __('Short description about this industry.', 'botphonic'),
			]
		);

		$repeater->add_control(
			'industry_icon',
			[
				'label' => esc_html__('Icon', 'elementor'),
				'type' => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
			]
		);

		$repeater->add_control(
			'industry_bg_color',
			[
				'label' => __('Background Color', 'botphonic'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
			]
		);

		$this->add_control(
			'industries',
			[
				'label' => __('Industries', 'botphonic'),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [],
				'title_field' => '{{{ industry_title }}}',
			]
		);

		$this->end_controls_section();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();


?>
<div class="container-fluid gx-0">
	<div class="row align-items-center">
		<div class="col-12 col-lg-3 offset-lg-1 text-center text-lg-start">
			<h3>Our AI Receptionist Designed For Every Industry</h3>
			<p>Rendering personalized services 24/7 and answering complicated questions with ease. Deployed in minutes and streamlines to manage. </p>
		</div>
		<div class="col-12 col-lg-8">
			<div class="botphonic-industry-slider">
				<div class="swiper industrySwiper">
					<div class="swiper-wrapper">
						<?php if ($settings['industries']) :

		foreach ($settings['industries'] as $item) : 
		$has_icon = ! empty($item['industry_icon']['value']);
		$migrated = isset($item['__fa4_migrated']['selected_icon']);
		$is_new = ! isset($item['icon']) && \Elementor\Icons_Manager::is_migration_allowed();

						?>
						<div class="swiper-slide">
							<div class="industry-card" style="--background: <?= esc_attr($item['industry_bg_color']); ?>;">
								<?php
		if ($is_new || $migrated) {
			\Elementor\Icons_Manager::render_icon($item['industry_icon'], ['aria-hidden' => 'true']);
		}
								?>
								<h3><?php echo esc_html($item['industry_title']); ?></h3>
								<p><?php echo esc_html($item['industry_description']); ?></p>
							</div>
						</div>
						<?php endforeach;
		endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	document.addEventListener("DOMContentLoaded", function() {
		new Swiper(".industrySwiper", {
			slidesPerView: 3,
			spaceBetween: 30,
			loop: true,
			centeredSlides: true,
			autoplay: {
				delay: 2500,
				disableOnInteraction: false,
			},
			breakpoints: {
				0: {
					slidesPerView: 1
				},
				480: {
					slidesPerView: 2
				},
				992: {
					slidesPerView: 3
				},
			}
		});
	});
</script>

<style>
	.botphonic-industry-slider { position: relative; padding: 60px 0; background: #fff; overflow: hidden; }
	.botphonic-industry-slider .swiper-wrapper{ align-items: center; }
	.botphonic-industry-slider .swiper::-webkit-scrollbar,
	.botphonic-industry-slider .swiper-wrapper::-webkit-scrollbar,
	.botphonic-industry-slider .swiper-slide::-webkit-scrollbar { display: none !important; }
	
	.botphonic-industry-slider .industry-card{}
	.botphonic-industry-slider .industry-card { background: #fff; border-radius: 20px; padding: 30px 22px; text-align: center; box-shadow: 0 6px 22px rgba(0, 0, 0, 0.1); transition: all 0.35s ease; height: 100%; }
	.botphonic-industry-slider .industry-card svg { width: 60px; height: 60px; object-fit: contain; margin-bottom: 8px; opacity: 0.5; transition: opacity 0.3s ease; color: var(--accent); }
	.botphonic-industry-slider .industry-card h3 { font-size: 20px; font-weight: 700; margin-bottom: 12px; color: #072032; }
	.botphonic-industry-slider .industry-card p { font-size: 15px; color: #6c7a89; line-height: 1.6; }
	
	@media (min-width: 992px) {
		.botphonic-industry-slider { background: #202020; border-radius: 9999px 0 0 9999px; border: 1px solid #202020; border-right:0; }
		.botphonic-industry-slider .swiper { overflow: visible; }
		.botphonic-industry-slider .swiper-slide { transition: transform 0.35s ease; opacity: 0.75 }
		.botphonic-industry-slider .swiper-slide-active { transform: scale(1.05);  opacity: 1; }
		.botphonic-industry-slider .swiper-slide-active .industry-card svg { opacity: 0.85; }
	}

	@media (max-width: 1024px) {
		.botphonic-industry-slider .industry-card { padding: 22px 16px; }
		.botphonic-industry-slider .industry-card h3 { font-size: 18px; }
		.botphonic-industry-slider .industry-card p { font-size: 14px; } 
	}
	@media (max-width: 767px) {
		.botphonic-industry-slider { background: transparent; border-radius: 0; margin-right: 0; padding: 50px 0; }
		.botphonic-industry-slider .swiper { overflow: hidden; padding-bottom: 40px; }
	}
	@media (max-width: 480px) {
		.botphonic-industry-slider .industry-card svg { width: 55px; height: 55px; }
		.botphonic-industry-slider .industry-card h3 { font-size: 16px; } 
		.botphonic-industry-slider .industry-card p { font-size: 13px; }
	}
</style>
<?php
	}
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Botphonic_Industry_Slider());