<?php

namespace cookiebot_addons;

use cookiebot_addons\config\Settings_Config;
use cookiebot_addons\controller\Plugin_Controller;
use cookiebot_addons\lib\Settings_Service_Interface;
use Cybot\Dependencies\DI\ContainerBuilder;
use Cybot\Dependencies\DI;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * __DIR__ of this plugin
 */
define( 'COOKIEBOT_ADDONS_DIR', __DIR__ . DIRECTORY_SEPARATOR );

define( 'COOKIEBOT_ADDONS_BASE_NAME', dirname( plugin_basename( __FILE__ ) ) );

/**
 * Same version as the CookiebotWP
 */
define( 'COOKIEBOT_ADDONS_VERSION', '3.9.0' );

/**
 * Register autoloader to load files/classes dynamically
 */
include_once COOKIEBOT_ADDONS_DIR . 'lib/autoloader.php';

/**
 * Load global functions for this plugin
 */
include_once COOKIEBOT_ADDONS_DIR . 'lib/helper.php';

/**
 * Load composer
 *
 * "php-di/php-di": "5.0"
 */
include_once COOKIEBOT_ADDONS_DIR . 'vendor/autoload.php';

class Cookiebot_Addons {

	/**
	 * IoC Container - is used for dependency injections
	 *
	 * @var DI\Container
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
	protected static $_instance = null;

	/**
	 * Main Cookiebot_WP Instance
	 *
	 * Ensures only one instance of Cookiebot_Addons is loaded or can be loaded.
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 * @static
	 *
	 * @return Cookiebot_Addons
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			try {
				self::$_instance = new self();
			} catch ( DI\DependencyException $e ) {
				echo 'Dependencies are not loaded.';
			} catch ( DI\NotFoundException $e ) {
				echo 'Dependencies are not found.';
			}
		}

		return self::$_instance;
	}

	/**
	 * Cookiebot_Addons constructor.
	 *
	 * @throws DI\DependencyException
	 * @throws DI\NotFoundException
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
		add_action( 'after_setup_theme', array(
			new Plugin_Controller( $this->container->get( 'Settings_Service_Interface' ) ),
			'load_active_addons',
		) );
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
	 * @since 1.3.0
	 */
	protected function get_plugins() {
		$file          = file_get_contents( COOKIEBOT_ADDONS_DIR . 'addons.json' );
		$this->plugins = apply_filters( 'cookiebot_addons_list', json_decode( $file ) );
	}

	/**
	 * Build IoC container
	 *
	 * @since 1.3.0
	 */
	protected function build_container() {
		$builder = new ContainerBuilder();

		$builder->addDefinitions(
			array(
				'Script_Loader_Tag_Interface' => DI\object( 'cookiebot_addons\lib\script_loader_tag\Script_Loader_Tag' ),
				'Cookie_Consent_Interface'    => DI\object( 'cookiebot_addons\lib\Cookie_Consent' ),
				'Buffer_Output_Interface'     => DI\object( 'cookiebot_addons\lib\buffer\Buffer_Output' ),
				'plugins'                     => DI\value( $this->plugins ),
			)
		);

		$this->container = $builder->build();

		$this->container->set( 'Settings_Service_Interface', DI\object( 'cookiebot_addons\lib\Settings_Service' )
			->constructor( $this->container ) );

		$this->container->set( 'Theme_Settings_Service_Interface', DI\object( 'cookiebot_addons\lib\Theme_Settings_Service' )
			->constructor( $this->container ) );
	}

	/**
	 * Assign addon class to the container to use it later
	 *
	 * @throws DI\DependencyException
	 * @throws DI\NotFoundException
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
			$this->container->set( $plugin->class, DI\object( $plugin->class )
				->constructor(
					isset( $plugin->is_theme ) && $plugin->is_theme
						? $this->container->get( 'Theme_Settings_Service_Interface' )
						: $this->container->get( 'Settings_Service_Interface' ),
					$this->container->get( 'Script_Loader_Tag_Interface' ),
					$this->container->get( 'Cookie_Consent_Interface' ),
					$this->container->get( 'Buffer_Output_Interface' ) )
			);
		}
	}
}

/**
 * Initiate the cookiebot addons framework plugin
 */
Cookiebot_Addons::instance();
