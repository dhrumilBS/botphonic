<?php

namespace Elementor;

if (!defined('ABSPATH')) exit;

class BotPhonic_Tabs_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'botphonic-tabs';
    }

    public function get_title()
    {
        return __('BotPhonic Tabs', 'botphonic');
    }

    public function get_icon()
    {
        return 'eicon-tabs';
    }

    public function get_categories()
    {
        return ['botphonic-widgets'];
    }

    public function get_style_depends()
    {
        return ['botphonic-tabs-css'];
    }

    public function get_script_depends()
    {
        return ['jquery','botphonic-tabs-js'];
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'tabs_section',
            [
                'label' => __('BotPhonic Tabs Items', 'botphonic'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'tab_title',
            [
                'label' => __('Tab Title', 'botphonic'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Tab Title', 'botphonic'),
            ]
        );
        $repeater->add_control(
            'tab_title_icon',
            [
                'label' => __('Tab Icon', 'botphonic'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
            ]
        );
        //  ------ 
        $repeater->add_control(
            'divider-1',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label' => __('Description', 'botphonic'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Set up your business phone system in just 3 minutes. Clear and easy communication starts now.', 'botphonic'),
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label' => __('Image', 'botphonic'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        // $repeater->add_control(
        //     'features',
        //     [
        //         'label' => __('Features (one per line)', 'botphonic'),
        //         'type' => Controls_Manager::TEXTAREA,
        //         'default' => "Power Dialer\nCall Analytics\nAutomatic Machine Detection\nAI Smart DID Routing\nAI Global Connect",
        //     ]
        // );

        $this->add_control(
            'tabs',
            [
                'label' => __('Tabs', 'botphonic'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['tab_title' => 'Tab 1', 'tab_content' => 'Content for Tab 1'],
                    ['tab_title' => 'Tab 2', 'tab_content' => 'Content for Tab 2'],
                ],
                'title_field' => '{{{ tab_title }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display(); ?>
        <div class="botphonic-tab-wr botphonic-tabs">
            <div class="botphonic-tab-left">
                <button id="prevArrow" class="arrow">
                    <svg fill="#ffffff" height="22px" viewBox="0 0 24 24" width="22px" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0h24v24H0V0z" fill="none"></path>
                        <path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.41z"></path>
                    </svg></button>
                <ul>
                    <?php foreach ($settings['tabs'] as $index => $tab) { ?>
                        <li class="botphonic-tabs-nav <?= ($index == 0) ? 'active-product' : ''; ?>" data-id="<?= esc_attr($index); ?>">
                            <span class="tablefticon business-icon">
                                <?php if (!empty($tab['tab_title_icon']['value'])) {
                                    \Elementor\Icons_Manager::render_icon($tab['tab_title_icon'], ['aria-hidden' => 'true']);
                                } ?>
                            </span>
                            <span> <?= esc_attr($tab['tab_title']); ?> </span>
                        </li>
                    <?php   } ?>
                </ul>
                <button id="nextArrow" class="arrow"><svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 0 24 24" width="22px" fill="#ffffff">
                        <path d="M0 0h24v24H0V0z" fill="none"></path>
                        <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"></path>
                    </svg></button>
            </div>
            <div class="botphonic-tab-right">
                <?php foreach ($settings['tabs'] as $index => $tab) { ?>
                    <div class="botphonic-tab-content active" id="<?= esc_attr($index); ?>">
                        <h3> <?= esc_html($tab['tab_title']); ?></h3>
                        <p> <?= esc_html($tab['description']); ?> </p>
                        <div class="botphonic-tab-img">
                            <?php
                            if (!empty($tab['image']['id']))
                                echo wp_get_attachment_image($tab['image']['id'], 'full');
                            else
                                echo '<img src="' . esc_url($tab['image']['url']) . '" />'; ?>
                        </div>
                       
                    </div>
                <?php } ?>
            </div>
        </div> <?php
                        // $features = explode("\n", $tab['features']);
                        // if (!empty($features)) {
                        //     echo '<div class="features-inner-block"> <div class="feature-title-product">Features:</div><ul>';
                        //     foreach ($features as $feature) {
                        //         echo '<li>' . esc_html(trim($feature)) . '</li>';
                        //     }
                        //     echo '</ul></div>';
                        // }
                         ?>
    <?php
    }

    protected function content_template()
    {
    ?>
        <div class="botphonic-tab-wr botphonic-tabs">
            <div class="botphonic-tab-left">
                <button id="prevArrow" class="arrow">
                    <svg fill="#ffffff" height="22px" viewBox="0 0 24 24" width="22px" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0h24v24H0V0z" fill="none"></path>
                        <path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.41z"></path>
                    </svg>
                </button>
                <ul>
                    <# _.each(settings.tabs, function(tab, index) { #>
                        <li class="botphonic-tabs-nav <# if(index === 0){ #>active-product<# } #>" data-id="{{ index }}">
                            <span class="tablefticon business-icon">
                                <# if (tab.tab_title_icon && tab.tab_title_icon.value) { #>
                                    <i class="{{ tab.tab_title_icon.value }}"></i>
                                    <# } #>
                            </span>
                            {{{ tab.tab_title }}}
                        </li>
                        <# }); #>
                </ul>
                <button id="nextArrow" class="arrow">
                    <svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 0 24 24" width="22px" fill="#ffffff">
                        <path d="M0 0h24v24H0V0z" fill="none"></path>
                        <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"></path>
                    </svg>
                </button>
            </div>

            <div class="botphonic-tab-right">
                <# _.each(settings.tabs, function(tab, index) { #>
                    <div class="botphonic-tab-content <# if(index === 0){ #>active<# } #>" id="{{ index }}">
                        <h3>{{{ tab.tab_title }}}</h3>
                        <p> {{{ tab.description }}} </p>
                        <# if (tab.image && tab.image.url) { #>
                            <img src="{{ tab.image.url }}" alt="{{ tab.headline }}" />
                            <# } #>
                    </div>
                    <# }); #>
            </div>
        </div>
<?php
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new BotPhonic_Tabs_Widget());
