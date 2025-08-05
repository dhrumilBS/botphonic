<?php

namespace Elementor;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Botphonic_Available_Badge_Widget extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'available_badge';
    }

    public function get_title()
    {
        return esc_html__('Status Badge', 'botphonic');
    }

    public function get_icon()
    {
        return 'eicon-circle-o';
    }

    public function get_categories()
    {
        return ['botphonic-widgets'];
    }

    public function _register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'botphonic'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'label_text',
            [
                'label' => esc_html__('Status Text', 'botphonic'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Available',
            ]
        );

        $this->add_control(
            'dot_color',
            [
                'label' => esc_html__('Dot Color', 'botphonic'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#28C76F',
            ]
        );
        $this->add_control(
            'align',
            [
                'label' => esc_html__('Alignment', 'botphonic'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'botphonic'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'botphonic'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'botphonic'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .available-badge-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $label = $settings['label_text'];
        $color = $settings['dot_color'];
?>

        <style>
            .available-badge {
                display: inline-flex;
                align-items: center;
                gap: 12px;
                font-size: 14px;
                color: <?php echo esc_attr($color); ?>;
                background: rgba(40, 199, 111, 0.1);
                padding: 6px 12px;
                border-radius: 50px;
                animation: fadeIn 0.5s ease-in-out;
            }

            .available-dot {
                position: relative;
                width: 12px;
                height: 12px;
                background-color: <?php echo esc_attr($color); ?>;
                border-radius: 50%;
            }

            .available-dot::after {
                content: "";
                position: absolute;
                top: -4px;
                left: -4px;
                width: 20px;
                height: 20px;
                border-radius: 50%;
                background: <?php echo esc_attr($this->hex_to_rgba($color, 0.4)); ?>;
                animation: pulse 1.5s infinite;
            }

            @keyframes pulse {
                0% {
                    transform: scale(0.8);
                    opacity: 1;
                }

                100% {
                    transform: scale(1.6);
                    opacity: 0;
                }
            }
        </style>
        <div class="available-badge-wrapper">
            <div class="available-badge">
                <span class="available-dot"></span>
                <?php echo esc_html($label); ?>
            </div>
        </div>
<?php
    }

    private function hex_to_rgba($hex, $opacity = 1.0)
    {
        $hex = str_replace("#", "", $hex);
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        return "rgba($r,$g,$b,$opacity)";
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Botphonic_Available_Badge_Widget());