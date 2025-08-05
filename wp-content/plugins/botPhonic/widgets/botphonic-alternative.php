<?php

namespace Elementor;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Botphonic_Widget_Alternative extends Widget_Base
{
    public function get_name()
    {
        return 'botphonic-alternative';
    }

    public function get_title()
    {
        return __('Botphonic Alternative', 'botphonic');
    }

    public function get_categories()
    {
        return ['botphonic-widgets'];
    }

    public function get_style_depends()
    {
        return ['botphonic-alternative-css'];
    }

    public function get_script_depends()
    {
        return ['botphonic-alternative-js'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'textdomain'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'pdf',
            [
                'label' => esc_html__('Choose JSON File', 'textdomain'),
                'type' => Controls_Manager::MEDIA,
                'media_types' => ['application/json'],
            ]
        );

        $this->add_control(
            'competitor_heading',
            [
                'label' => esc_html__('Competitor Heading', 'textdomain'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Competitor', 'textdomain'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $pdf_url = $settings['pdf']['url'] ?? '';
?>

        <input id="jsonFiles" type="hidden" value="<?= esc_url($pdf_url); ?>">

        <div class="choose_data_list">
            <div class="comparison-title">Feature Comparison</div>

            <div class="all_data table-responsive">
                <table id="jsonData" class="table comparison-table">
                    <thead>
                        <tr class="feature-tr">
                            <td class="feature-td">
                                <div class="feature-title h3">Features</div>
                            </td>
                            <td class="company our">
                                <div class="h3">Botphonic</div>
                            </td>
                            <td class="company other">
                                <div class="h3"><?= esc_html($settings['competitor_heading']); ?></div>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamic feature rows -->
                    </tbody>
                </table>
            </div>
        </div>

    <?php
    }

    protected function content_template()
    { ?>
        <#
            const pdf_url=settings.pdf.url;
            const competitor_heading=settings.competitor_heading;
            #>

            <input id="jsonFiles" type="hidden" value="{{ pdf_url }}">

            <div class="choose_data_list">
                <div class="comparison-title">Feature Comparison</div>

                <div class="all_data table-responsive">
                    <table id="jsonData" class="table comparison-table">
                        <thead>
                            <tr class="feature-tr">
                                <td class="feature-td">
                                    <div class="feature-title h3">Features</div>
                                </td>
                                <td class="company our">
                                    <div class="h3">Botphonic</div>
                                </td>
                                <td class="company other">
                                    <div class="h3">{{ competitor_heading }}</div>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamic feature rows -->
                        </tbody>
                    </table>
                </div>
            </div>
    <?php }
}

Plugin::instance()->widgets_manager->register(new Botphonic_Widget_Alternative());