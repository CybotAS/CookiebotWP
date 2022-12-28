<?php

/*
Plugin Name: Cookiebot CMP by Usercentrics | The reliable, flexible and easy to use consent solution
Plugin URI: https://cookiebot.com/
Description: Cookiebot consent management platform (CMP) provides a plug-and-play cookie consent solution that enables compliance with the GDPR, LGPD, CCPA and other international regulations.
Author: Usercentrics A/S
Version: 4.2.4
Author URI: http://cookiebot.com
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
