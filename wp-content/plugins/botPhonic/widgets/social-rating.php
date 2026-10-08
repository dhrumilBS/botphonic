<?php
/**
 * BotPhonic – Social Rating (Elementor widget).
 *
 * Widget name ('rating'), control IDs and markup classes are intentionally
 * UNCHANGED so existing pages keep their saved data.
 *
 * See the note on get_name() below regarding the collision with Elementor's
 * own core Rating widget.
 *
 * @package BotPhonic
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BotPhonic_social_rating extends Widget_Base {

	const HANDLE = 'botphonic-social-rating';
	public function get_name() {
		return 'rating';
	}

	public function get_title() {
		return esc_html__( 'Social Rating', 'botphonic' );
	}

	public function get_icon() {
		return 'eicon-rating';
	}

	public function get_categories() {
		return array( 'botphonic-widgets' );
	}

	public function get_keywords() {
		return array( 'star', 'rating', 'review', 'score', 'scale', 'botphonic' );
	}

	public function get_style_depends() {
		return array( self::HANDLE );
	}

	private function add_style_tab() {
		$this->start_controls_section(
			'section_icon_style',
			array(
				'label' => esc_html__( 'Icon', 'botphonic' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Size', 'botphonic' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'em'  => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
					'rem' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'size_units' => array( 'px', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--e-rating-icon-font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'icon_gap',
			array(
				'label'      => esc_html__( 'Icon Spacing', 'botphonic' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'em'  => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
					'rem' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'size_units' => array( 'px', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--e-rating-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Color', 'botphonic' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--e-rating-icon-marked-color: {{VALUE}}',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'icon_unmarked_color',
			array(
				'label'     => esc_html__( 'Unmarked Color', 'botphonic' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--e-rating-icon-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			array(
				'label' => esc_html__( 'Image', 'botphonic' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'width',
			array(
				'label'          => esc_html__( 'Width', 'botphonic' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => '%',
				),
				'tablet_default' => array(
					'unit' => '%',
				),
				'mobile_default' => array(
					'unit' => '%',
				),
				'size_units'     => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'          => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
					'vw' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'space',
			array(
				'label'          => esc_html__( 'Max Width', 'botphonic' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => '%',
				),
				'tablet_default' => array(
					'unit' => '%',
				),
				'mobile_default' => array(
					'unit' => '%',
				),
				'size_units'     => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'          => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
					'vw' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} img' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'      => esc_html__( 'Height', 'botphonic' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vh', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 500,
					),
					'vh' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} img' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'object-fit',
			array(
				'label'     => esc_html__( 'Object Fit', 'botphonic' ),
				'type'      => Controls_Manager::SELECT,
				'condition' => array(
					'height[size]!' => '',
				),
				'options'   => array(
					''        => esc_html__( 'Default', 'botphonic' ),
					'fill'    => esc_html__( 'Fill', 'botphonic' ),
					'cover'   => esc_html__( 'Cover', 'botphonic' ),
					'contain' => esc_html__( 'Contain', 'botphonic' ),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} img' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'object-position',
			array(
				'label'     => esc_html__( 'Object Position', 'botphonic' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'center center' => esc_html__( 'Center Center', 'botphonic' ),
					'center left'   => esc_html__( 'Center Left', 'botphonic' ),
					'center right'  => esc_html__( 'Center Right', 'botphonic' ),
					'top center'    => esc_html__( 'Top Center', 'botphonic' ),
					'top left'      => esc_html__( 'Top Left', 'botphonic' ),
					'top right'     => esc_html__( 'Top Right', 'botphonic' ),
					'bottom center' => esc_html__( 'Bottom Center', 'botphonic' ),
					'bottom left'   => esc_html__( 'Bottom Left', 'botphonic' ),
					'bottom right'  => esc_html__( 'Bottom Right', 'botphonic' ),
				),
				'default'   => 'center center',
				'selectors' => array(
					'{{WRAPPER}} img' => 'object-position: {{VALUE}};',
				),
				'condition' => array(
					'object-fit' => 'cover',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_controls() {
		$start_logical = is_rtl() ? 'end' : 'start';
		$end_logical   = is_rtl() ? 'start' : 'end';

		$this->start_controls_section(
			'section_rating',
			array(
				'label' => esc_html__( 'Rating', 'botphonic' ),
			)
		);

		$this->add_control(
			'rating_scale',
			array(
				'label'   => esc_html__( 'Rating Scale', 'botphonic' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => array(
					'px' => array(
						'min' => 1,
						'max' => 10,
					),
				),
				'step'    => 1,
				'default' => array(
					'size' => '5',
				),
			)
		);

		$this->add_control(
			'rating_value',
			array(
				'label'   => esc_html__( 'Rating', 'botphonic' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'step'    => 0.5,
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'rating_icon',
			array(
				'label'                  => esc_html__( 'Icon', 'botphonic' ),
				'type'                   => Controls_Manager::ICONS,
				'fa4compatibility'       => 'icon',
				'skin'                   => 'inline',
				'label_block'            => false,
				'skin_settings'          => array(
					'inline' => array(
						'icon' => array(
							'icon' => 'eicon-star',
						),
					),
				),
				'default'                => array(
					'value'   => 'eicon-star',
					'library' => 'eicons',
				),
				'separator'              => 'before',
				'exclude_inline_options' => array( 'none' ),
			)
		);

		$this->add_responsive_control(
			'icon_alignment',
			array(
				'label'                => esc_html__( 'Alignment', 'botphonic' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'start'  => array(
						'title' => esc_html__( 'Start', 'botphonic' ),
						'icon'  => "eicon-align-$start_logical-h",
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'botphonic' ),
						'icon'  => 'eicon-align-center-h',
					),
					'end'    => array(
						'title' => esc_html__( 'End', 'botphonic' ),
						'icon'  => "eicon-align-$end_logical-h",
					),
				),
				'selectors_dictionary' => array(
					'start'  => '--e-rating-justify-content: flex-start;',
					'center' => '--e-rating-justify-content: center;',
					'end'    => '--e-rating-justify-content: flex-end;',
				),
				'selectors'            => array(
					'{{WRAPPER}}' => '{{VALUE}}',
				),
				'separator'            => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image',
			array(
				'label' => esc_html__( 'Image', 'botphonic' ),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'   => esc_html__( 'Choose Image', 'botphonic' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array(
					'active' => true,
				),
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				// Produces `image_size` and `image_custom_dimension`.
				'name'      => 'image',
				'default'   => 'large',
				'separator' => 'none',
			)
		);

		$this->add_responsive_control(
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
				'selectors' => array(
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_to',
			array(
				'label'   => esc_html__( 'Link', 'botphonic' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'   => esc_html__( 'None', 'botphonic' ),
					'file'   => esc_html__( 'Media File', 'botphonic' ),
					'custom' => esc_html__( 'Custom URL', 'botphonic' ),
				),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'      => esc_html__( 'Link', 'botphonic' ),
				'type'       => Controls_Manager::URL,
				'dynamic'    => array(
					'active' => true,
				),
				'condition'  => array(
					'link_to' => 'custom',
				),
				'show_label' => false,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_heading',
			array(
				'label' => esc_html__( 'Heading', 'botphonic' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .elementor-heading-title',
			)
		);

		$this->add_control(
			'heading_color',
			array(
				'label'     => esc_html__( 'Color', 'botphonic' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-heading-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'heading_align',
			array(
				'label'     => esc_html__( 'Alignment', 'botphonic' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'botphonic' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'botphonic' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'botphonic' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'botphonic' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-heading-title' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->add_style_tab();

		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'botphonic' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'botphonic' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'default'    => array(
					'size' => 12,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .e-rating' => 'margin-top: {{SIZE}}{{UNIT}};margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function get_rating_scale(): int {
		$scale = $this->get_settings_for_display( 'rating_scale' );
		$scale = isset( $scale['size'] ) ? intval( $scale['size'] ) : 5;

		return max( 1, $scale );
	}

	protected function get_rating_value(): float {
		$scale        = $this->get_rating_scale();
		$rating_value = $this->get_settings_for_display( 'rating_value' );

		if ( '' === $rating_value || null === $rating_value ) {
			$rating_value = $scale;
		}

		// Clamped so a value above the scale can't render as e.g. "8/5".
		$rating_value = max( 0, min( floatval( $rating_value ), $scale ) );

		return round( $rating_value, 2 );
	}

	protected function get_icon_marked_width( $icon_index ): string {
		$rating_value = $this->get_rating_value();

		$width = '0%';

		if ( $rating_value >= $icon_index ) {
			$width = '100%';
		} elseif ( intval( ceil( $rating_value ) ) === $icon_index ) {
			$width = ( $rating_value - ( $icon_index - 1 ) ) * 100 . '%';
		}

		return $width;
	}

	protected function get_icon_markup(): string {
		$icon         = $this->get_settings_for_display( 'rating_icon' );
		$rating_scale = $this->get_rating_scale();

		ob_start();

		for ( $index = 1; $index <= $rating_scale; $index++ ) {
			$this->add_render_attribute(
				'icon_marked_' . $index,
				array(
					'class' => 'e-icon-wrapper e-icon-marked',
				)
			);

			$icon_marked_width = $this->get_icon_marked_width( $index );

			if ( '100%' !== $icon_marked_width ) {
				$this->add_render_attribute(
					'icon_marked_' . $index,
					array(
						'style' => '--e-rating-icon-marked-width: ' . $icon_marked_width . ';',
					)
				);
			}
			?>
			<div class="e-icon">
				<div <?php $this->print_render_attribute_string( 'icon_marked_' . $index ); ?>>
					<?php echo Icons_Manager::try_get_icon_html( $icon, array( 'aria-hidden' => 'true' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<div class="e-icon-wrapper e-icon-unmarked">
					<?php echo Icons_Manager::try_get_icon_html( $icon, array( 'aria-hidden' => 'true' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
			<?php
		}

		return ob_get_clean();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			'widget',
			array(
				'class' => 'e-rating',
			)
		);

		$this->add_render_attribute(
			'widget_wrapper',
			array(
				'class'      => 'e-rating-wrapper',
				'role'       => 'img',
				'aria-label' => sprintf(
					/* translators: 1: rating value, 2: rating scale */
					esc_html__( 'Rated %1$s out of %2$s', 'botphonic' ),
					$this->get_rating_value(),
					$this->get_rating_scale()
				),
			)
		);

		$this->add_render_attribute( 'title', 'class', 'elementor-heading-title' );

		$title      = $this->get_rating_value() . '/' . $this->get_rating_scale();
		$has_image  = ! empty( $settings['image']['url'] );
		$image_link = $has_image ? $this->get_link_url( $settings ) : false;

		// Only link the heading when a custom URL is actually selected.
		if ( 'custom' === $settings['link_to'] && ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'url', $settings['link'] );
			$title = sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $title );
		}

		if ( $image_link ) {
			$this->add_link_attributes( 'link', $image_link );

			if ( Plugin::instance()->editor->is_edit_mode() ) {
				$this->add_render_attribute( 'link', 'class', 'elementor-clickable' );
			}
		}

		$this->add_render_attribute( 'wrapper', 'class', 'elementor-image' );
		?>
		<div class="social-rating">
			<div <?php $this->print_render_attribute_string( 'title' ); ?>>
				<?php echo $title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

			<div <?php $this->print_render_attribute_string( 'widget' ); ?>>
				<div <?php $this->print_render_attribute_string( 'widget_wrapper' ); ?>>
					<?php echo $this->get_icon_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>

			<?php if ( $has_image ) : ?>
				<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
					<?php if ( $image_link ) : ?>
						<a <?php $this->print_render_attribute_string( 'link' ); ?>>
					<?php endif; ?>

					<?php Group_Control_Image_Size::print_attachment_image_html( $settings ); ?>

					<?php if ( $image_link ) : ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	protected function get_link_url( $settings ) {
		if ( empty( $settings['link_to'] ) || 'none' === $settings['link_to'] ) {
			return false;
		}

		if ( 'custom' === $settings['link_to'] ) {
			if ( empty( $settings['link']['url'] ) ) {
				return false;
			}

			return $settings['link'];
		}

		if ( empty( $settings['image']['url'] ) ) {
			return false;
		}

		return array(
			'url' => $settings['image']['url'],
		);
	}
}

/*
 * Widget registration – `register()` on Elementor 3.5+, with a fallback.
 */
$botphonic_social_rating_widget  = new \Elementor\BotPhonic_social_rating();
$botphonic_social_rating_manager = Plugin::instance()->widgets_manager;

if ( method_exists( $botphonic_social_rating_manager, 'register' ) ) {
	$botphonic_social_rating_manager->register( $botphonic_social_rating_widget );
} else {
	$botphonic_social_rating_manager->register_widget_type( $botphonic_social_rating_widget );
}