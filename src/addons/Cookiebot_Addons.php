<?php

namespace cybot\cookiebot\addons;

use cybot\cookiebot\addons\config\Settings_Config;
use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Addon;
use cybot\cookiebot\addons\controller\Plugin_Controller;
use cybot\cookiebot\exceptions\addons\InvalidAddonClassException;
use cybot\cookiebot\lib\buffer\Buffer_Output;
use cybot\cookiebot\lib\Cookie_Consent;
use cybot\cookiebot\lib\Dependency_Container;
use cybot\cookiebot\lib\script_loader_tag\Script_Loader_Tag;
use cybot\cookiebot\lib\script_loader_tag\Script_Loader_Tag_Interface;
use cybot\cookiebot\lib\Settings_Service;
use cybot\cookiebot\lib\Settings_Service_Interface;
use Exception;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Cookiebot_Addons {

	/**
	 * @var Dependency_Container
	 */
	public $container;

	/**
	 * @var array
	 */
	private $addons_list = array();

	/**
	 * @var Cookiebot_Addons
	 */
	private static $instance;

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
		if ( ! is_a( self::$instance, self::class ) ) {
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
		$this->load_init_files();
		$this->load_addons();
		$this->build_container();
		$this->assign_addons_to_container();
		$this->assign_ignore_scripts_from_settings();

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
	 * Load init files to use 'validate_plugin' and 'is_plugin_active'
	 *
	 * @since 1.3.0
	 */
	private function load_init_files() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
			require_once ABSPATH . '/wp-admin/includes/translation-install.php';
			require_once ABSPATH . '/wp-includes/l10n.php';
		}
	}

	/**
	 * if the cookiebot is activated
	 * run this script to start up
	 *
	 * @throws Exception
	 * @since 2.2.0
	 */
	public function cookiebot_activated() {
		/** @var Settings_Service_Interface $settings_service */
		$settings_service = $this->container->get( 'Settings_Service_Interface' );
		$settings_service->cookiebot_activated();
	}

	/**
	 * if the cookiebot is deactivated
	 * run this script to clean up addons.
	 *
	 * @throws Exception
	 * @since 2.2.0
	 */
	public function cookiebot_deactivated() {
		/** @var Settings_Service_Interface $settings_service */
		$settings_service = $this->container->get( 'Settings_Service_Interface' );
		$settings_service->cookiebot_deactivated();
	}

	protected function load_addons() {
		require_once __DIR__ . '/addons.php';
		$this->addons_list = apply_filters(
			'cybot_cookiebot_addons_list',
			array_merge( PLUGIN_ADDONS, THEME_ADDONS, OTHER_ADDONS )
		);
	}

	/**
	 * @throws Exception
	 */
	protected function build_container() {
		$dependencies = array(
			'Script_Loader_Tag_Interface' => new Script_Loader_Tag(),
			'Cookie_Consent_Interface'    => new Cookie_Consent(),
			'Buffer_Output_Interface'     => new Buffer_Output(),
			'addons_list'                 => $this->addons_list,
		);

		$this->container = new Dependency_Container( $dependencies );

		$this->container->set(
			'Settings_Service_Interface',
			new Settings_Service( $this->container )
		);
	}

	/**
	 * @throws Exception
	 */
	protected function assign_addons_to_container() {
		/**
		 * Check plugins one by one and load addon configuration
		 */
		foreach ( $this->addons_list as $addon_class ) {
			/**
			 * Load addon class to the container
			 */
			if ( class_exists( $addon_class ) ) {
				$addon = call_user_func(
					array( $addon_class, 'get_instance' ),
					$this->container->get( 'Settings_Service_Interface' ),
					$this->container->get( 'Script_Loader_Tag_Interface' ),
					$this->container->get( 'Cookie_Consent_Interface' ),
					$this->container->get( 'Buffer_Output_Interface' )
				);
				if ( ! is_a( $addon, Base_Cookiebot_Addon::class ) ) {
					throw new InvalidAddonClassException(
						sprintf( 'Class %s could not be instantiated', $addon_class )
					);
				}
				$this->container->set( $addon_class, $addon );
			} else {
				throw new InvalidAddonClassException(
					sprintf( 'Class %s not found', $addon_class )
				);
			}
		}
	}

	protected function assign_ignore_scripts_from_settings() {
		$ignore_scripts = get_option( 'cookiebot-ignore-scripts' );

		if ( empty( $ignore_scripts ) ) {
			return;
		}

		$ignore_scripts = explode( PHP_EOL, $ignore_scripts );

		/**
		 * @var Script_Loader_Tag_Interface
		 */
		$script_loader_tag = $this->container->get( 'Script_Loader_Tag_Interface' );

		foreach ( $ignore_scripts as $ignore_script ) {
			$script_loader_tag->ignore_script( trim( $ignore_script ) );
		}
	}
}
