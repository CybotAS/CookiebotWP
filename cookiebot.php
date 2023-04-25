<?php

/*
Plugin Name: Cookie banner plugin for WordPress – Cookiebot CMP by Usercentrics
Plugin URI: https://www.cookiebot.com/
Description: The Cookiebot CMP WordPress cookie banner and cookie policy help you comply with the major data protection laws (GDPR, ePrivacy, CCPA, LGPD, etc.) in a simple and fully automated way. Secure your website and get peace of mind.
Author: Usercentrics A/S
Version: 4.2.9
Author URI: https://www.cookiebot.com/
Text Domain: cookiebot
Domain Path: /langs
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'CYBOT_COOKIEBOT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CYBOT_COOKIEBOT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/lib/helper.php';
require_once __DIR__ . '/src/lib/global-deprecations.php';

try {
	\cybot\cookiebot\lib\cookiebot();
} catch ( RuntimeException $exception ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	deactivate_plugins( plugin_basename( __FILE__ ) );
	wp_die( esc_html( $exception->getMessage() ) );
}
