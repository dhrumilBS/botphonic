<?php

/**
 * Plugin Name: Botphonic
 * Description: Custom Elementor widgets.
 * Version: 1.0
 * Author: Your Name
 */

if (! defined('ABSPATH')) exit;

require_once('shortcodes/testimonials_shortcode.php');
require_once('shortcodes/sidetabs_shortcode.php');
require_once('shortcodes/usecases_shortcode.php');
require_once('shortcodes/ai-call-assistant-demo-shoertcode.php');	
require_once('shortcodes/iconbox_shortcode.php');	
require_once('shortcodes/integration_slider.php');	

function botphonic_enqueue_assets()
{
    wp_enqueue_style('botphonic-css', plugin_dir_url(__FILE__) . 'assets/css/botphonic.css',[] , rand());
    wp_enqueue_script('botphonic-js', plugin_dir_url(__FILE__) . 'assets/js/botphonic.js', ['jquery'], null, true);


	// Swiper from CDN
	wp_register_style('botphonic-Swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',[] , rand());
	wp_register_script('botphonic-Swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [], null, true);

	// Usecase
    wp_register_style('botphonic-usecase-css', plugin_dir_url(__FILE__) . 'assets/css/botphonic-usecase.css',[] , rand());

	// tabs
    wp_register_style('botphonic-tabs-css', plugin_dir_url(__FILE__) . 'assets/css/botphonic-tabs.css',[] , rand());
    wp_register_script('botphonic-tabs-js', plugin_dir_url(__FILE__) . 'assets/js/botphonic-tabs.js', ['jquery'], null, true);
	
	// audio
    wp_register_style('botphonic-audio-css', plugin_dir_url(__FILE__) . 'assets/css/botphonic-audio.css',[] , rand());
    wp_register_script('botphonic-audio-js', plugin_dir_url(__FILE__) . 'assets/js/botphonic-audio.js', ['jquery'], null, true);

    // FAQs
    wp_register_style('botphonic-faqs-css', plugin_dir_url(__FILE__) . 'assets/css/botphonic-faqs.css',[] , rand());
    wp_register_script('botphonic-faqs-js', plugin_dir_url(__FILE__) . 'assets/js/botphonic-faqs.js', ['jquery'], null, true);
    
     // alternative
    wp_register_style('botphonic-alternative-css', plugin_dir_url(__FILE__) . 'assets/css/botphonic-alternative.css',[] , rand());
    wp_register_script('botphonic-alternative-js', plugin_dir_url(__FILE__) . 'assets/js/botphonic-alternative.js', ['jquery'], null, true);
    
    // integration
    wp_register_style('botphonic-integration-css', plugin_dir_url(__FILE__) . 'assets/css/integration.css',[] , rand());
    wp_register_script('botphonic-integration-js', plugin_dir_url(__FILE__) . 'assets/js/integration.js', ['jquery'], null, true); 
    
    // Commission Calculator 
    wp_register_style('botphonic-commission', plugin_dir_url(__FILE__) . 'assets/css/botphonic-commission.css', [], rand());
    wp_register_script('botphonic-agent-grid-js', plugin_dir_url(__FILE__) . 'assets/js/botphonic-agent-grid.js', ['jquery'], null, true);

    // Commission Calculator 
    wp_register_style('botphonic-iconbox', plugin_dir_url(__FILE__) . 'assets/css/botphonic-iconbox.css', [], rand());
    wp_register_script('botphonic-iconbox', plugin_dir_url(__FILE__) . 'assets/js/botphonic-iconbox.js', ['jquery'], null, true);
}
add_action('wp_enqueue_scripts', 'botphonic_enqueue_assets');
function botphonic_add_custom_widget_category($elements_manager)
{
    $elements_manager->add_category(
        'botphonic-widgets',
        [
            'title' => __('BotPhonic Widgets', 'elementor-kit'),
            'icon'  => 'fa fa-plug',
        ]
    );
}
add_action('elementor/elements/categories_registered', 'botphonic_add_custom_widget_category');

function botphonic_register_widget($widgets_manager)
{
    require_once(__DIR__ . '/widgets/botphonic-tabs.php');
    require_once(__DIR__ . '/widgets/botphonic-faqs.php');
    require_once(__DIR__ . '/widgets/botphonic-integration.php');
    require_once(__DIR__ . '/widgets/botphonic-alternative.php');
    require_once(__DIR__ . '/widgets/botphonic-audio-button.php');
    require_once(__DIR__ . '/widgets/botphonic-audio-sample-box.php');
    require_once(__DIR__ . '/widgets/botphonic-commission-calculator.php');
    require_once(__DIR__ . '/widgets/botphonic-available-badge.php');
}
add_action('elementor/widgets/register', 'botphonic_register_widget');