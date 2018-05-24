<?php
/**
 * Plugin Name: Cookiebot Addons Framework
 * Description: Adding support for Cookiebot
 * Author: Johan Holst Nielsen & Aytac Kokus
 * Version: 1.1.0
 */

namespace cookiebot_addons_framework;

use cookiebot_addons_framework\controller\Plugin_Controller;
use DI\Container;
use DI\ContainerBuilder;

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

/**
 * Load composer
 */
include_once CAF_DIR . 'vendor/autoload.php';

class Cookiebot_Addons_Framework {

	public $container;

	public function __construct() {
		/**
		 * Load plugin controller to check if addons are active
		 * If active then load the plugin addon configuration class
		 * Else skip it
		 *
		 * @since 1.1.0
		 */
		add_action( 'plugins_loaded', array( new Plugin_Controller( $this->get_plugins() ), 'check_addons' ) );
	}

	/**
	 * Loads plugins from json file
	 *
	 * All the addon plugins are defined there.
	 *
	 * The file is located at the root map of this plugin
	 *
	 * @return array    List of supported plugins
	 *
	 * @since 1.1.0
	 */
	private function get_plugins() {
		$file    = file_get_contents( CAF_DIR . 'addons.json' );
		$plugins = json_decode( $file );

		return $plugins;
	}
}

/**
 * Initiate the cookiebot addons framework plugin
 */
new Cookiebot_Addons_Framework();