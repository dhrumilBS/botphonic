<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Bf_How_It_Works extends Widget_Base {

	const HANDLE = 'botphonic-how-it-works';

	public function get_name() {
		return 'bf-how-it-works';
	}

	public function get_title() {
		return __( 'BotPhonic How It Works', 'botphonic' );
	}

	public function get_icon() {
		return 'eicon-flow';
	}

	public function get_categories() {
		return array( 'botphonic-widgets' );
	}

	public function get_keywords() {
		return array( 'steps', 'process', 'timeline', 'how it works', 'botphonic' );
	}

	public function get_style_depends() {
		return array( self::HANDLE );
	}

	private function get_heading_tags() {
		return array(
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
				'label' => __( 'Steps', 'botphonic' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'step_number',
			array(
				'label'       => __( 'Step Number', 'botphonic' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'STEP 01', 'botphonic' ),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'       => __( 'Title', 'botphonic' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'User signs up', 'botphonic' ),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'description',
			array(
				'label'       => __( 'Description', 'botphonic' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Describe this step...', 'botphonic' ),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'icon',
			array(
				'label'       => __( 'Icon', 'botphonic' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'icon_color',
			array(
				'label'     => __( 'Icon Color', 'botphonic' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .step-icon' => 'color: {{VALUE}};',
				),
			)
		);

		$repeater->add_control(
			'icon_bg_color',
			array(
				'label'     => __( 'Background Color', 'botphonic' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .step-icon' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'steps',
			array(
				'label'       => __( 'Steps List', 'botphonic' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(),
				'title_field' => '{{{ title }}}',
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'     => __( 'Title HTML Tag', 'botphonic' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_heading_tags(),
				'default'   => 'h3',
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			array(
				'label' => __( 'Steps', 'botphonic' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'line_color',
			array(
				'label'     => __( 'Connector Line Color', 'botphonic' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .my-how-it-works' => '--step-line-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'step_gap',
			array(
				'label'      => __( 'Space Between Steps', 'botphonic' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .my-how-it-works' => '--step-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'number_heading',
			array(
				'label'     => __( 'Step Number', 'botphonic' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'number_color',
			array(
				'label'     => __( 'Color', 'botphonic' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .step-number' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'number_typography',
				'selector' => '{{WRAPPER}} .step-number',
			)
		);

		$this->add_control(
			'title_heading',
			array(
				'label'     => __( 'Title', 'botphonic' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'botphonic' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .step-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .step-title',
			)
		);

		$this->add_control(
			'description_heading',
			array(
				'label'     => __( 'Description', 'botphonic' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => __( 'Color', 'botphonic' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .step-description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .step-description',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['steps'] ) || ! is_array( $settings['steps'] ) ) {
			return;
		}

		$title_tag = ! empty( $settings['title_tag'] ) ? Utils::validate_html_tag( $settings['title_tag'] ) : 'h3';
		?>
		<div class="my-how-it-works">
			<?php
			foreach ( $settings['steps'] as $index => $step ) :

				$item_id     = ! empty( $step['_id'] ) ? $step['_id'] : (string) $index;
				$number      = isset( $step['step_number'] ) ? $step['step_number'] : '';
				$title       = isset( $step['title'] ) ? $step['title'] : '';
				$description = isset( $step['description'] ) ? $step['description'] : '';
				$has_icon    = ! empty( $step['icon']['value'] );

				$this->add_render_attribute(
					'step_item_' . $index,
					'class',
					array( 'step-item', 'elementor-repeater-item-' . $item_id )
				);
				?>
				<div <?php $this->print_render_attribute_string( 'step_item_' . $index ); ?>>
					<div class="step-icon">
						<?php
						if ( $has_icon ) {
							Icons_Manager::render_icon( $step['icon'], array( 'aria-hidden' => 'true' ) );
						}
						?>
					</div>
					<div class="step-content">
						<?php if ( '' !== $number ) : ?>
							<span class="step-number"><?php echo esc_html( $number ); ?></span>
						<?php endif; ?>

						<?php if ( '' !== $title ) : ?>
							<<?php echo esc_html( $title_tag ); ?> class="step-title"><?php echo esc_html( $title ); ?></<?php echo esc_html( $title_tag ); ?>>
						<?php endif; ?>

						<?php if ( '' !== $description ) : ?>
							<p class="step-description"><?php echo esc_html( $description ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}

$botphonic_how_it_works_widget  = new \Elementor\Bf_How_It_Works();
$botphonic_how_it_works_manager = Plugin::instance()->widgets_manager;

if ( method_exists( $botphonic_how_it_works_manager, 'register' ) ) {
	$botphonic_how_it_works_manager->register( $botphonic_how_it_works_widget );
} else {
	$botphonic_how_it_works_manager->register_widget_type( $botphonic_how_it_works_widget );
}