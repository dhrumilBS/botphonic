<?php
/**
 * BotPhonic – Status Badge (Elementor widget).
 *
 * Widget name ('available_badge'), control IDs and markup classes are
 * intentionally UNCHANGED so existing pages keep their saved data.
 *
 * @package BotPhonic
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Botphonic_Available_Badge_Widget extends Widget_Base {

	const HANDLE = 'botphonic-available-badge';
	public function get_name() {
		return 'available_badge';
	}

	public function get_title() {
		return esc_html__( 'Status Badge', 'botphonic' );
	}

	public function get_icon() {
		return 'eicon-circle-o';
	}

	public function get_categories() {
		return array( 'botphonic-widgets' );
	}

	public function get_keywords() {
		return array( 'badge', 'status', 'available', 'online', 'pill', 'botphonic' );
	}

	public function get_style_depends() {
		return array( self::HANDLE );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'Content', 'botphonic' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'label_text',
			array(
				'label'       => esc_html__( 'Status Text', 'botphonic' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Available', 'botphonic' ),
			)
		);

		$this->add_control(
			'dot_color',
			array(
				'label'     => esc_html__( 'Dot Color', 'botphonic' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#28C76F',
				'selectors' => array(
					'{{WRAPPER}} .available-badge'      => 'color: {{VALUE}};',
					'{{WRAPPER}} .available-dot'        => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .available-dot::after' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'align',
			array(
				'label'     => esc_html__( 'Alignment', 'botphonic' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'botphonic' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'botphonic' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'botphonic' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'left',
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .available-badge-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			array(
				'label' => esc_html__( 'Badge', 'botphonic' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'badge_background',
			array(
				'label'       => esc_html__( 'Background Color', 'botphonic' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Defaults to a light green tint. Set this when using a dot colour other than green.', 'botphonic' ),
				'selectors'   => array(
					'{{WRAPPER}} .available-badge' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .available-badge',
			)
		);

		$this->add_responsive_control(
			'badge_padding',
			array(
				'label'      => esc_html__( 'Padding', 'botphonic' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .available-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'badge_gap',
			array(
				'label'      => esc_html__( 'Dot Spacing', 'botphonic' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .available-badge' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'badge_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'botphonic' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .available-badge' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$label    = isset( $settings['label_text'] ) ? $settings['label_text'] : '';
		?>
		<div class="available-badge-wrapper">
			<div class="available-badge">
				<span class="available-dot" aria-hidden="true"></span>
				<?php echo esc_html( $label ); ?>
			</div>
		</div>
		<?php
	}
}

$botphonic_available_badge_widget  = new \Elementor\Botphonic_Available_Badge_Widget();
$botphonic_available_badge_manager = Plugin::instance()->widgets_manager;

if ( method_exists( $botphonic_available_badge_manager, 'register' ) ) {
	$botphonic_available_badge_manager->register( $botphonic_available_badge_widget );
} else {
	$botphonic_available_badge_manager->register_widget_type( $botphonic_available_badge_widget );
}