<?php

namespace cookiebot_addons_framework\controller;

use cookiebot_addons_framework\lib\Cookiebot_Buffer_Output;
use cookiebot_addons_framework\lib\Cookiebot_Cookie_Consent;
use cookiebot_addons_framework\lib\Cookiebot_Script_Loader_Tag;

class Plugin_Controller {

	/**
	 * Array of addon plugins
	 *
	 * @var array
	 *
	 * @since 1.1.0
	 */
	private $plugins;

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
	 * @param $plugins  array   List of supported plugins
	 *
	 * @since 1.2.0
	 */
	public function __construct( $plugins ) {
		$this->plugins = $plugins;
	}

	/**
	 * Load addon configuration if the plugin is activated
	 *
	 * @since 1.2.0
	 */
	public function check_addons() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}

		/**
		 * Initialize default features: script_loader_tag, cookie_consent, buffer output
		 */
		$this->init_cookiebot_functions();

		/**
		 * Check plugins one by one and load configuration if it is active
		 */
		foreach ( $this->plugins as $plugin_class => $plugin ) {
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
	 * Load functions to use in Dependency Injection
	 *
	 * @since 1.2.0
	 */
	private function init_cookiebot_functions() {
		/**
		 * Initialize script loader tag class
		 */
		$this->script_loader_tag = new Cookiebot_Script_Loader_Tag();

		/**
		 * Initialize cookie consent class
		 */
		$this->cookie_consent = new Cookiebot_Cookie_Consent();

		/**
		 * Initialize buffer output
		 */
		$this->buffer_output = new Cookiebot_Buffer_Output();
	}

	/**
	 * Dynamically Loads addon plugin configuration class
	 *
	 * For example:
	 * /controller/addons/google-analyticator/google-analyticator.php
	 *
	 * @param $class    string  Plugin class name
	 *
	 * @since 1.2.0
	 */
	private function load_addon_configuration( $class ) {
		$full_class_name = 'cookiebot_addons_framework\\controller\\addons\\' . $class;

		/**
		 * Load addon class
		 */

		if ( class_exists( $full_class_name ) ) {
			new $full_class_name( $this->script_loader_tag, $this->cookie_consent, $this->buffer_output );
		}
	}

	/**
	 * Runs every added action hooks to manipulate script tag
	 *
	 * @since 1.2.0
	 */
	public function run_buffer_output_manipulations() {
		if ( $this->buffer_output->has_action() ) {
			$this->buffer_output->run_actions();
		}
	}
}