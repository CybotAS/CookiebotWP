<?php

/*
Plugin Name: Cookie Banner & Privacy Compliance for GDPR/CCPA/Google Consent Mode – Usercentrics Cookiebot
Plugin URI: https://www.cookiebot.com/
Description: Install your cookie banner in minutes. Automatically scan and block cookies to comply with the GDPR, CCPA, Google Consent Mode v2. Free plan option.
Author: Usercentrics A/S
Version: 4.5.6
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
