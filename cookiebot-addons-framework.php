<?php
/**
 * Plugin Name: Cookiebot Addons Framework
 * Description: Adding support for Cookiebot
 * Author: Johan Holst Nielsen & Aytac Kokus
 * Version: 1.2.0
 */

namespace cookiebot_addons_framework;

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
		add_action( 'plugins_loaded', array( new Plugin_Controller( $this->container ), 'check_addons' ) );
	}

	/**
	 * Build IoC container
	 *
	 * @since 1.3.0
	 */
	protected function build_container() {
		$builder = new ContainerBuilder();

		$builder->addDefinitions( [
			'Cookiebot_Script_Loader_Tag_Interface' => DI\object( 'cookiebot_addons_framework\lib\script_loader_tag\Cookiebot_Script_Loader_Tag' ),
			'Cookiebot_Cookie_Consent_Interface'    => DI\object( 'cookiebot_addons_framework\lib\Cookiebot_Cookie_Consent' ),
			'Cookiebot_Buffer_Output_Interface'     => DI\object( 'cookiebot_addons_framework\lib\buffer\Cookiebot_Buffer_Output' ),
			'plugins'                               => DI\value( $this->plugins )
		] );

		$this->container = $builder->build();
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
		$file          = file_get_contents( CAF_DIR . 'addons.json' );
		$this->plugins = json_decode( $file );
	}

	/**
	 * Assign addon class to the container to use it later (lazy loading)
	 *
	 * @throws DI\DependencyException
	 * @throws DI\NotFoundException
	 *
	 * @since 1.3.0
	 */
	protected function assign_addons_to_container() {
		/**
		 * Check plugins one by one and load configuration if it is active
		 */
		foreach ( $this->plugins as $plugin_class => $plugin ) {
			/**
			 * Load addon class to the container
			 */
			$this->container->set( $plugin->class, \DI\object( $plugin->class )
				->constructor(
					$this->container->get( 'Cookiebot_Script_Loader_Tag_Interface' ),
					$this->container->get( 'Cookiebot_Cookie_Consent_Interface' ),
					$this->container->get( 'Cookiebot_Buffer_Output_Interface' )
				)
			);
		}
	}
}

/**
 * Initiate the cookiebot addons framework plugin
 */
new Cookiebot_Addons_Framework();