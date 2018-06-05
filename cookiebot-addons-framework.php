<?php
/**
 * Plugin Name: Cookiebot Addons
 * Description: Adding support for Cookiebot
 * Author: Johan Holst Nielsen & Aytac Kokus & Sebastian Kurznyowski
 * Version: 1.5.0
 */

namespace cookiebot_addons_framework;

use cookiebot_addons_framework\config\Settings_Config;
use cookiebot_addons_framework\controller\Plugin_Controller;
use DI\ContainerBuilder;
use DI;

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
include_once CAF_DIR . 'lib/autoloader.php';

/**
 * Load global functions for this plugin
 */
include_once CAF_DIR . 'lib/helper.php';

/**
 * Load composer
 *
 * "php-di/php-di": "5.0"
 */
include_once CAF_DIR . 'lib/ioc/autoload.php';

class Cookiebot_Addons_Framework {

	/**
	 * IoC Container - is used for dependency injections
	 *
	 * @var \DI\Container
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
	 * Cookiebot_Addons_Framework constructor.
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
		add_action( 'plugins_loaded', array(
			new Plugin_Controller( $this->container->get( 'Settings_Service_Interface' ) ),
			'load_active_addons'
		) );

		/**
		 * Load settings config
		 */
		$settings = new Settings_Config( $this->container->get( 'Settings_Service_Interface' ) );
		$settings->load();
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
		$file = file_get_contents( CAF_DIR . 'addons.json' );
		$this->plugins = json_decode( $file );
	}

	/**
	 * Build IoC container
	 *
	 * @since 1.3.0
	 */
	protected function build_container() {
		$builder = new ContainerBuilder();

		$builder->addDefinitions( [
			'Script_Loader_Tag_Interface' => DI\object( 'cookiebot_addons_framework\lib\script_loader_tag\Script_Loader_Tag' ),
			'Cookie_Consent_Interface'    => DI\object( 'cookiebot_addons_framework\lib\Cookie_Consent' ),
			'Buffer_Output_Interface'     => DI\object( 'cookiebot_addons_framework\lib\buffer\Buffer_Output' ),
			'plugins'                     => DI\value( $this->plugins )
		] );

		$this->container = $builder->build();

		$this->container->set( 'Settings_Service_Interface', DI\object( 'cookiebot_addons_framework\lib\Settings_Service' )
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
			$this->container->set( $plugin->class, \DI\object( $plugin->class )
				->constructor(
					$this->container->get( 'Settings_Service_Interface' ),
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
new Cookiebot_Addons_Framework();
