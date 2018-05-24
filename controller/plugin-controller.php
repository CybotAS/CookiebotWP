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
	 * Is used to manipulate enqueue script attributes
	 *
	 * @var Cookiebot_Script_Loader_Tag
	 *
	 * @since 1.2.0
	 */
	public $script_loader_tag;

	/**
	 * Is used to manipulate the data in the buffer
	 *
	 * @var Cookiebot_Buffer_Output
	 *
	 * @since 1.2.0
	 */
	public $buffer_output;

	/**
	 * Is used to get cookie consent
	 *
	 * @var Cookiebot_Cookie_Consent
	 *
	 * @since 1.2.0
	 */
	public $cookie_consent;

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
		foreach ( $this->container->get( 'plugins' ) as $plugin_class => $plugin ) {
			/**
			 * Load addon code if the plugin is active
			 */
			if ( is_plugin_active( $plugin->file ) ) {
				$this->load_addon_configuration( $plugin->class );
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
	 * Dynamically Loads addon plugin configuration class
	 *
	 * For example:
	 * /controller/addons/google-analyticator/google-analyticator.php
	 *
	 * @param $class    string  Plugin class name
	 *
	 * @throws \DI\DependencyException
	 * @throws \DI\NotFoundException
	 *
	 * @since 1.2.0
	 */
	private function load_addon_configuration( $class ) {
		/**
		 * Load addon class
		 */
		if ( class_exists( $class ) ) {
			new $class( $this->container->get( 'script_loader_tag' ), $this->container->get( 'cookie_consent' ), $this->container->get( 'buffer_output' ) );
		}
	}

	/**
	 * Runs every added action hooks to manipulate script tag
	 *
	 * @throws \DI\DependencyException
	 * @throws \DI\NotFoundException
	 *
	 * @since 1.2.0
	 */
	public function run_buffer_output_manipulations() {
		$buffer_output = $this->container->get( 'buffer_output' );

		if ( $buffer_output->has_action() ) {
			$buffer_output->run_actions();
		}
	}
}