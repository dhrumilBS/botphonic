<?php
if (!defined('ABSPATH')) exit;

// Guard: only define the widget class if Elementor's base class is actually loaded.
// Prevents a fatal error (which would break the whole editor) if this file is ever
// included before Elementor has initialized.
if (!class_exists('\Elementor\Widget_Base')) {
    return;
}

if (!class_exists('Botphonic_Accordion_Widget')) {

    class Botphonic_Accordion_Widget extends \Elementor\Widget_Base
    {

        public function get_name()
        {
            return 'botphonic_accordion';
        }

        public function get_title()
        {
            return __('Botphonic Accordion', 'botphonic');
        }

        public function get_icon()
        {
            return 'eicon-accordion';
        }

        public function get_script_depends()
        {
            return ['botphonic-accordion'];
        }

        public function get_style_depends()
        {
            return ['botphonic-accordion'];
        }

        public function get_categories()
        {
            return ['botphonic-widgets'];
        }

        protected function register_controls()
        {

            $this->start_controls_section(
                'layout_section',
                [
                    'label' => __('Layout Settings', 'botphonic'),
                ]
            );

            $this->add_control(
                'columns',
                [
                    'label' => __('Columns', 'botphonic'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '2',
                    'options' => [
                        '1' => '1 Column',
                        '2' => '2 Columns',
                        '3' => '3 Columns',
                        '4' => '4 Columns',
                    ],
                ]
            );

            $this->end_controls_section();


            /* COLUMN REPEATER */
            $this->start_controls_section(
                'content_section',
                [
                    'label' => __('Columns Content', 'botphonic'),
                ]
            );

            $item_repeater = new \Elementor\Repeater();

            $item_repeater->add_control(
                'question',
                [
                    'label' => __('Question', 'botphonic'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => 'Accordion Question',
                ]
            );

            $item_repeater->add_control(
                'answer',
                [
                    'label' => __('Answer', 'botphonic'),
                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                    'default' => 'Accordion answer text goes here.',
                ]
            );

            $column_repeater = new \Elementor\Repeater();

            $column_repeater->add_control(
                'column_title',
                [
                    'label' => __('Column Title', 'botphonic'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => 'Column Title',
                ]
            );

            $column_repeater->add_control(
                'items',
                [
                    'label' => __('Accordion Items', 'botphonic'),
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'fields' => $item_repeater->get_controls(),
                    'title_field' => '{{{ question }}}',
                ]
            );

            $this->add_control(
                'columns_data',
                [
                    'label' => __('Columns', 'botphonic'),
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'fields' => $column_repeater->get_controls(),
                    'title_field' => '{{{ column_title }}}',
                ]
            );

            $this->end_controls_section();
        }

        protected function render()
        {

            $settings = $this->get_settings_for_display();
            $columns = $settings['columns'];
            $widget_id = 'botphonic-' . $this->get_id();

            echo '<div id="' . esc_attr($widget_id) . '" class="botphonic-accordion columns-' . esc_attr($columns) . '">';

            if (!empty($settings['columns_data'])) {

                foreach ($settings['columns_data'] as $column) {

                    echo '<div class="bp-column">';

                    if (!empty($column['column_title'])) {
                        echo '<div class="bp-column-title">';
                        echo '<h3>' . esc_html($column['column_title']) . '</h3>';
                        echo '</div>';
                    }

                    if (!empty($column['items'])) {

                        foreach ($column['items'] as $item) {

                            echo '<div class="bp-item">';
                            echo '<div class="bp-question">';
                            echo '<h5>' . esc_html($item['question']) . '</h5>';
                            echo '<span class="bp-icon">+</span>';
                            echo '</div>';
                            echo '<div class="bp-answer">';
                            echo '<p>' . esc_html($item['answer']) . '</p>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }

                    echo '</div>';
                }
            }

            echo '</div>';
        }
    }
}

if (class_exists('\Elementor\Plugin') && class_exists('Botphonic_Accordion_Widget')) {
    \Elementor\Plugin::instance()->widgets_manager->register(new Botphonic_Accordion_Widget());
}