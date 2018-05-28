<?php
/**
 * Plugin Name: Cookiebot Addons Framework
 * Description: Adding support for Cookiebot
 * Author: Johan Holst Nielsen & Aytac Kokus
 * Version: 1.2.0
 */

namespace cookiebot_addons_framework;

use cookiebot_addons_framework\controller\Plugin_Controller;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * __DIR__ of this plugin
 */
define( 'CAF_DIR', __DIR__ . DIRECTORY_SEPARATOR );

/**
 * Register autoloader to load files/classes dynamically
 */
include_once CAF_DIR . 'lib/cookiebot-addons-framework-autoloader.php';

/**
 * Load global functions for this plugin
 */
include_once CAF_DIR . 'lib/cookiebot-addons-functions.php';

class Cookiebot_Addons_Framework {

	public function __construct() {
		/**
		 * Load plugin controller to check if addons are active
		 * If active then load the plugin addon configuration class
		 * Else skip it
		 *
		 * @since 1.1.0
		 */
		$pc = new Plugin_Controller();
		add_action( 'plugins_loaded', array($pc, 'load_active_addons' ) );
		
		if(is_admin()) {
			//add_action('admin_init', array($pc,'register_settings'));
			add_action( 'admin_menu', array($pc,'add_menu'));
			add_action( 'admin_enqueue_scripts', array($pc,'add_wp_admin_style') );
		}
	}
	
}

/**
 * Initiate the cookiebot addons framework plugin
 */
new Cookiebot_Addons_Framework();
