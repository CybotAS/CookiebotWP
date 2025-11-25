<?php

// Mock WP functions if they don't exist
if (!function_exists('add_action')) {
    function add_action($hook, $callback, $priority = 10, $accepted_args = 1) {}
}
if (!function_exists('add_filter')) {
    function add_filter($hook, $callback, $priority = 10, $accepted_args = 1) {}
}
if (!function_exists('apply_filters')) {
    function apply_filters($hook, $value) { return $value; }
}
if (!function_exists('esc_url')) {
    function esc_url($url) { return $url; }
}
if (!function_exists('esc_attr')) {
    function esc_attr($text) { return htmlspecialchars($text, ENT_QUOTES); }
}
if (!function_exists('esc_html')) {
    function esc_html($text) { return htmlspecialchars($text, ENT_QUOTES); }
}
if (!function_exists('esc_html__')) {
    function esc_html__($text, $domain) { return $text; }
}
if (!function_exists('get_option')) {
    function get_option($option, $default = false) { return $default; }
}
if (!function_exists('update_option')) {
    function update_option($option, $value) {}
}
if (!function_exists('get_bloginfo')) {
    function get_bloginfo($show = 'name') { return '5.8'; }
}
if (!function_exists('wp_scripts')) {
    function wp_scripts() { return (object)['registered' => []]; }
}
if (!function_exists('is_multisite')) {
    function is_multisite() { return false; }
}
if (!function_exists('get_site_url')) {
    function get_site_url() { return 'http://example.com'; }
}
if (!function_exists('get_site_option')) {
    function get_site_option($option, $default = false) { return $default; }
}
if (!function_exists('get_locale')) {
    function get_locale() { return 'en_US'; }
}
if (!function_exists('plugin_dir_url')) {
    function plugin_dir_url($file) { return 'http://example.com/wp-content/plugins/cookiebot/'; }
}
if (!function_exists('plugin_dir_path')) {
    function plugin_dir_path($file) { return '/path/to/plugin/'; }
}

// Constants
if (!defined('ABSPATH')) define('ABSPATH', '/tmp/');
if (!defined('CYBOT_COOKIEBOT_PLUGIN_DIR')) define('CYBOT_COOKIEBOT_PLUGIN_DIR', __DIR__ . '/../');
if (!defined('CYBOT_COOKIEBOT_PLUGIN_ASSETS_DIR')) define('CYBOT_COOKIEBOT_PLUGIN_ASSETS_DIR', 'assets/');
