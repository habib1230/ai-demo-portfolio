<?php
/**
 * Plugin Name: Elementor Vertical Scroll
 * Description: Custom Elementor widget for the Vertical Scroll section.
 * Version: 1.0.0
 * Author: Nano Banana
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

function register_elementor_vertical_scroll_widget($widgets_manager)
{
    require_once(__DIR__ . '/widgets/vertical-scroll-widget.php');
    $widgets_manager->register(new \Elementor_Vertical_Scroll_Widget());
}
add_action('elementor/widgets/register', 'register_elementor_vertical_scroll_widget');

function elementor_vertical_scroll_scripts()
{
    wp_register_script('gsap-js', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', [], '3.12.5', true);
    wp_register_script('gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', ['gsap-js'], '3.12.5', true);
    wp_register_script('vertical-scroll-widget-js', plugin_dir_url(__FILE__) . 'assets/js/widget-script.js', ['jquery', 'gsap-scrolltrigger'], '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'elementor_vertical_scroll_scripts');
