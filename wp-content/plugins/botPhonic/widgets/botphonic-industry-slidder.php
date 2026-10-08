<?php
/**
 * BotPhonic – Industry Slider (Elementor widget).
 *
 * Widget name, control IDs and markup classes are intentionally UNCHANGED so
 * existing pages keep their saved data and layout.
 *
 * @package BotPhonic
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Botphonic_Industry_Slider extends \Elementor\Widget_Base {

	public function get_name() {
		return 'botphonic-industry-slider';
	}

	public function get_title() {
		return __( 'Botphonic Industry Slider', 'botphonic' );
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	public function get_categories() {
		return array( 'botphonic-widgets' );
	}

	public function get_keywords() {
		return array( 'slider', 'industry', 'carousel', 'audio', 'voice', 'swiper', 'botphonic' );
	}

	public function get_script_depends() {
		return array( 'swiper', 'botphonic-wavesurfer-js', 'botphonic-industry-slider-js' );
	}

	public function get_style_depends() {
		return array( 'swiper', 'botphonic-industry-slider-css' );
	}

	private function get_heading_tags() {
		return array(
			'h1'   => 'H1',
			'h2'   => 'H2',
			'h3'   => 'H3',
			'h4'   => 'H4',
			'h5'   => 'H5',
			'h6'   => 'H6',
			'div'  => 'div',
			'span' => 'span',
			'p'    => 'p',
		);
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Industry Slider', 'botphonic' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'section_title',
			array(
				'label'       => __( 'Section Title', 'botphonic' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Our AI Receptionist Designed For Every Industry', 'botphonic' ),
				'label_block' => true,
			)
		);

		// Default h3 keeps existing pages byte-identical.
		$this->add_control(
			'section_title_tag',
			array(
				'label'   => __( 'Title HTML Tag', 'botphonic' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => $this->get_heading_tags(),
				'default' => 'h3',
			)
		);

		$this->add_control(
			'section_description',
			array(
				'label'   => __( 'Section Description', 'botphonic' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( 'Rendering personalized services 24/7 and answering complicated questions with ease. Deployed in minutes and streamlines to manage.', 'botphonic' ),
			)
		);

		$this->add_control(
			'show_button',
			array(
				'label'        => __( 'Show Button', 'botphonic' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'botphonic' ),
				'label_off'    => __( 'No', 'botphonic' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'       => __( 'Button Text', 'botphonic' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Explore Marketplace', 'botphonic' ),
				'label_block' => true,
				'condition'   => array(
					'show_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'button_link',
			array(
				'label'       => __( 'Button URL', 'botphonic' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'botphonic' ),
				'default'     => array(
					'url'         => 'https://botphonic.ai/marketplace/',
					'is_external' => true,
				),
				'condition'   => array(
					'show_button' => 'yes',
				),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'industry_title',
			array(
				'label'       => __( 'Title', 'botphonic' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'Industry Title', 'botphonic' ),
			)
		);

		$repeater->add_control(
			'industry_description',
			array(
				'label'   => __( 'Description', 'botphonic' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( 'Short description about this industry.', 'botphonic' ),
			)
		);

		$repeater->add_control(
			'industry_icon',
			array(
				'label'            => __( 'Icon', 'botphonic' ),
				'type'             => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				),
			)
		);

		$repeater->add_control(
			'agent_audio',
			array(
				'label'       => __( 'Audio File', 'botphonic' ),
				'type'        => \Elementor\Controls_Manager::MEDIA,
				'media_types' => array( 'audio' ),
				'default'     => array(),
			)
		);

		$repeater->add_control(
			'industry_bg_color',
			array(
				'label'   => __( 'Background Color', 'botphonic' ),
				'type'    => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
			)
		);

		$this->add_control(
			'industries',
			array(
				'label'       => __( 'Industries', 'botphonic' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(),
				'title_field' => '{{{ industry_title }}}',
			)
		);

		$this->add_control(
			'card_title_tag',
			array(
				'label'     => __( 'Card Title HTML Tag', 'botphonic' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => $this->get_heading_tags(),
				'default'   => 'h4',
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Cards', 'botphonic' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'       => __( 'Icon Color', 'botphonic' ),
				'type'        => \Elementor\Controls_Manager::COLOR,
				'description' => __( 'Sets the --accent variable the card icon uses.', 'botphonic' ),
				'selectors'   => array(
					'{{WRAPPER}} .industry-card' => '--accent: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$widget_id     = $this->get_id();
		$title         = isset( $settings['section_title'] ) ? $settings['section_title'] : '';
		$description   = isset( $settings['section_description'] ) ? $settings['section_description'] : '';
		$title_tag     = ! empty( $settings['section_title_tag'] ) ? \Elementor\Utils::validate_html_tag( $settings['section_title_tag'] ) : 'h3';
		$card_tag      = ! empty( $settings['card_title_tag'] ) ? \Elementor\Utils::validate_html_tag( $settings['card_title_tag'] ) : 'h4';
		$industries    = ! empty( $settings['industries'] ) && is_array( $settings['industries'] ) ? $settings['industries'] : array();
		$label_play    = __( 'Play voice preview', 'botphonic' );
		$label_pause   = __( 'Pause voice preview', 'botphonic' );
		?>
		<div class="row align-items-center industry-slider-row" id="industrySwiperContainer">
			<div class="col-12 col-lg-3 offset-lg-1 text-center text-lg-start px-3 p-lg-0">
				<?php if ( '' !== $title ) : ?>
					<<?php echo esc_html( $title_tag ); ?>><?php echo esc_html( $title ); ?></<?php echo esc_html( $title_tag ); ?>>
				<?php endif; ?>

				<?php if ( '' !== $description ) : ?>
					<p><?php echo esc_html( $description ); ?></p>
				<?php endif; ?>

				<?php
				if ( ! empty( $settings['show_button'] ) && 'yes' === $settings['show_button'] ) :
					$button_url = ! empty( $settings['button_link']['url'] ) ? $settings['button_link']['url'] : '';

					if ( '' !== $button_url ) :
						$this->add_render_attribute( 'button', 'class', array( 'btn', 'theme-btn' ) );
						// Handles href, target and rel (including nofollow) consistently.
						$this->add_link_attributes( 'button', $settings['button_link'] );
						?>
						<a <?php $this->print_render_attribute_string( 'button' ); ?>>
							<?php echo esc_html( isset( $settings['button_text'] ) ? $settings['button_text'] : '' ); ?>
						</a>
						<?php
					endif;
				endif;
				?>
			</div>

			<div class="col-12 col-lg-8">
				<div class="botphonic-industry-slider">
					<div class="swiper industrySwiper px-3 pb-4 p-lg-0">
						<div class="swiper-wrapper">
							<?php
							foreach ( $industries as $index => $item ) :

								$item_id   = ! empty( $item['_id'] ) ? $item['_id'] : (string) $index;
								$agent_uid = $widget_id . '-' . $item_id;
								$audio_url = ! empty( $item['agent_audio']['url'] ) ? $item['agent_audio']['url'] : '';
								$bg_color  = ! empty( $item['industry_bg_color'] ) ? $item['industry_bg_color'] : '';
								$card_name = isset( $item['industry_title'] ) ? $item['industry_title'] : '';
								$card_desc = isset( $item['industry_description'] ) ? $item['industry_description'] : '';
								?>
								<div class="swiper-slide">
									<div class="industry-card"
										<?php if ( '' !== $bg_color ) : ?>
											style="--background: <?php echo esc_attr( $bg_color ); ?>;"
										<?php endif; ?>
										data-audio="<?php echo esc_url( $audio_url ); ?>"
										data-id="agent-<?php echo esc_attr( $agent_uid ); ?>"
										data-label-play="<?php echo esc_attr( $label_play ); ?>"
										data-label-pause="<?php echo esc_attr( $label_pause ); ?>">

										<?php if ( ! empty( $item['industry_icon']['value'] ) ) : ?>
											<div class="industry-icon">
												<?php \Elementor\Icons_Manager::render_icon( $item['industry_icon'], array( 'aria-hidden' => 'true' ) ); ?>
											</div>
										<?php endif; ?>

										<?php if ( '' !== $card_name ) : ?>
											<<?php echo esc_html( $card_tag ); ?>><?php echo esc_html( $card_name ); ?></<?php echo esc_html( $card_tag ); ?>>
										<?php endif; ?>

										<?php if ( '' !== $card_desc ) : ?>
											<p><?php echo esc_html( $card_desc ); ?></p>
										<?php endif; ?>

										<?php if ( '' !== $audio_url ) : ?>
											<div class="agent-audio">
												<button type="button"
													class="play-button"
													aria-label="<?php echo esc_attr( $label_play ); ?>"
													aria-pressed="false">
													<svg data-icon="play" xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 12 12" height="16" fill="none"><path d="M11.25 5.99999C11.2503 6.12731 11.2177 6.25255 11.1552 6.36352C11.0928 6.4745 11.0027 6.56742 10.8937 6.63327L4.14 10.7648C4.02613 10.8346 3.89572 10.8726 3.76222 10.8751C3.62873 10.8776 3.49699 10.8444 3.38063 10.7789C3.26536 10.7144 3.16935 10.6205 3.10245 10.5066C3.03556 10.3928 3.00019 10.2631 3 10.1311V1.8689C3.00019 1.73684 3.03556 1.60722 3.10245 1.49337C3.16935 1.37951 3.26536 1.28553 3.38063 1.22108C3.49699 1.15562 3.62873 1.12241 3.76222 1.12488C3.89572 1.12736 4.02613 1.16542 4.14 1.23515L10.8937 5.36671C11.0027 5.43255 11.0928 5.52548 11.1552 5.63645C11.2177 5.74743 11.2503 5.87266 11.25 5.99999Z" fill="currentColor"></path></svg>
												</button>
												<div class="waveform" id="waveform_agent-<?php echo esc_attr( $agent_uid ); ?>"></div>
											</div>
										<?php endif; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}

$botphonic_industry_slider_widget  = new Botphonic_Industry_Slider();
$botphonic_industry_slider_manager = \Elementor\Plugin::instance()->widgets_manager;

if ( method_exists( $botphonic_industry_slider_manager, 'register' ) ) {
	$botphonic_industry_slider_manager->register( $botphonic_industry_slider_widget );
} else {
	$botphonic_industry_slider_manager->register_widget_type( $botphonic_industry_slider_widget );
}