<?php

namespace cookiebot_addons_framework\controller;

use cookiebot_addons_framework\lib\Cookiebot_Buffer_Output;
use cookiebot_addons_framework\lib\Cookiebot_Cookie_Consent;
use cookiebot_addons_framework\lib\Cookiebot_Script_Loader_Tag;

class Plugin_Controller {

	/**
	 * IoC container - Dependency Injection
	 *
	 * @var \DI\Container
	 *
	 * @since 1.1.0
	 */
	private $container;

	/**
	 * Plugin_Controller constructor.
	 *
	 * @param $container  object IoC Container
	 *
	 * @since 1.2.0
	 */
	public function __construct( $container ) {
		$this->container = $container;
	}

	/**
	 * Load addon configuration if the plugin is activated
	 *
	 * @throws \DI\DependencyException
	 * @throws \DI\NotFoundException
	 *
	 * @since 1.2.0
	 */
	public function check_addons() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}

		/**
		 * Check plugins one by one and load configuration if it is active
		 */
		foreach ( $this->container->get( 'plugins' ) as $plugin ) {
			$addon = $this->container->get( $plugin->class );

			/**
			 * Load addon code if the plugin is active
			 */
			if ( $addon->is_addon_enabled() && $addon->is_plugin_installed() ) {
				$addon->load_configuration();
			} else {
				// unset not used addon???
				$this->container->set( $plugin->class, '' );
				unset( $addon );
			}
		}

		/**
		 * After WordPress is fully loaded
		 *
		 * Run buffer output actions - this runs after scanning of every addons
		 */
		add_action( 'parse_request', array( $this, 'run_buffer_output_manipulations' ) );
	}

	/**
	 * Runs every added action hooks to manipulate script tag
	 *
	 * @throws \DI\DependencyException
	 * @throws \DI\NotFoundException
	 *
	 * @since 1.3.0
	 */
	public function run_buffer_output_manipulations() {
		$buffer_output = $this->container->get( 'Cookiebot_Buffer_Output_Interface' );

		if ( $buffer_output->has_action() ) {
			$buffer_output->run_actions();
		}
	}
}