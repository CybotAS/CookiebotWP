<?php

namespace cookiebot_addons;

use cookiebot_addons\config\Settings_Config;
use cookiebot_addons\controller\Plugin_Controller;
use cookiebot_addons\lib\buffer\Buffer_Output;
use cookiebot_addons\lib\Cookie_Consent;
use cookiebot_addons\lib\Dependency_Container;
use cookiebot_addons\lib\script_loader_tag\Script_Loader_Tag;
use cookiebot_addons\lib\Settings_Service;
use cookiebot_addons\lib\Theme_Settings_Service;
use Exception;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * __DIR__ of this plugin
 */
define( 'COOKIEBOT_ADDONS_DIR', __DIR__ . DIRECTORY_SEPARATOR );

if ( ! defined( 'COOKIEBOT_ADDONS_URL' ) ) {
	define( 'COOKIEBOT_ADDONS_URL', plugin_dir_url( __FILE__ ) );
}

define( 'COOKIEBOT_ADDONS_BASE_NAME', dirname( plugin_basename( __FILE__ ) ) );

/**
 * Same version as the CookiebotWP
 */
define( 'COOKIEBOT_ADDONS_VERSION', '3.9.0' );

/**
 * Register autoloader to load files/classes dynamically
 */
require_once COOKIEBOT_ADDONS_DIR . 'lib/autoloader.php';

/**
 * Load global functions for this plugin
 */
require_once COOKIEBOT_ADDONS_DIR . 'lib/helper.php';

class Cookiebot_Addons {

	/**
	 * Dependency Container - is used for dependency injections
	 *
	 * @var Dependency_Container
	 *
	 * @since 1.3.0
	 */
	public $container;

	/**
	 * List of all supported addons
	 *
	 * @var object
	 *
	 * @since 1.3.0
	 */
	public $plugins;

	/**
	 * @var   Cookiebot_Addons The single instance of the class
	 * @since 1.0.0
	 */
	private static $instance = null;

	/**
	 * Main Cookiebot_WP Instance
	 *
	 * Ensures only one instance of Cookiebot_Addons is loaded or can be loaded.
	 *
	 * @return Cookiebot_Addons
	 * @since   2.2.0
	 * @static
	 *
	 * @version 2.2.0
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			try {
				self::$instance = new self();
			} catch ( Exception $e ) {
				echo 'Could not initialize Cookiebot addons: ' . esc_html( $e->getMessage() );
			}
		}

		return self::$instance;
	}

	/**
	 * Cookiebot_Addons constructor.
	 *
	 * @throws Exception
	 *
	 * @since 1.3.0
	 */
	public function __construct() {
		$this->get_plugins();
		$this->build_container();
		$this->assign_addons_to_container();

		/**
		 * Load plugin controller to check if addons are active
		 * If active then load the plugin addon configuration class
		 * Else skip it
		 *
		 * @since 1.1.0
		 */
		add_action(
			'after_setup_theme',
			array(
				new Plugin_Controller( $this->container->get( 'Settings_Service_Interface' ) ),
				'load_active_addons',
			)
		);
		/**
		 * Load settings config
		 *
		 * @since 1.1.0
		 */
		$settings = new Settings_Config( $this->container->get( 'Settings_Service_Interface' ) );
		$settings->load();
	}

	/**
	 * if the cookiebot is activated
	 * run this script to start up
	 *
	 * @since 2.2.0
	 */
	public function cookiebot_activated() {
		$settings_service = $this->container->get( 'Settings_Service_Interface' );
		$settings_service->cookiebot_activated();
	}

	/**
	 * if the cookiebot is deactivated
	 * run this script to clean up addons.
	 *
	 * @since 2.2.0
	 */
	public function cookiebot_deactivated() {
		$settings_service = $this->container->get( 'Settings_Service_Interface' );
		$settings_service->cookiebot_deactivated();
	}

	/**
	 * Loads plugins from json file
	 *
	 * All the addon plugins are defined there.
	 *
	 * The file is located at the root map of this plugin
	 *
	 * @throws Exception
	 * @since 1.3.0
	 */
	protected function get_plugins() {
		$file = cookiebot_get_local_file_json_contents( COOKIEBOT_ADDONS_DIR . 'addons.json' );

		$this->plugins = apply_filters( 'cookiebot_addons_list', $file );
	}

	/**
	 * @throws Exception
	 */
	protected function build_container() {
		$dependencies = array(
			'Script_Loader_Tag_Interface' => new Script_Loader_Tag(),
			'Cookie_Consent_Interface'    => new Cookie_Consent(),
			'Buffer_Output_Interface'     => new Buffer_Output(),
			'plugins'                     => $this->plugins,
		);

		$this->container = new Dependency_Container( $dependencies );

		$this->container->set(
			'Settings_Service_Interface',
			new Settings_Service( $this->container )
		);

		$this->container->set(
			'Theme_Settings_Service_Interface',
			new Theme_Settings_Service( $this->container )
		);
	}

	/**
	 * Assign addon class to the container to use it later
	 *
	 * @throws Exception
	 *
	 * @since 1.3.0
	 */
	protected function assign_addons_to_container() {
		/**
		 * Check plugins one by one and load addon configuration
		 */
		foreach ( $this->plugins as $plugin_class => $plugin ) {
			/**
			 * Load addon class to the container
			 */

			if ( class_exists( $plugin->class ) ) {
				$this->container->set(
					$plugin->class,
					new $plugin->class(
						isset( $plugin->is_theme ) && $plugin->is_theme
							? $this->container->get( 'Theme_Settings_Service_Interface' )
							: $this->container->get( 'Settings_Service_Interface' ),
						$this->container->get( 'Script_Loader_Tag_Interface' ),
						$this->container->get( 'Cookie_Consent_Interface' ),
						$this->container->get( 'Buffer_Output_Interface' )
					)
				);
			} else {
				throw new Exception( 'Class ' . $plugin->class . ' not found' );
			}
		}
	}
}

/**
 * Initiate the cookiebot addons framework plugin
 */
Cookiebot_Addons::instance();
