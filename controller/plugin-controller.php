<?php

namespace cookiebot_addons_framework\controller;

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
	 * Load addons if the plugin is activated
	 *
	 * @since 1.1.0
	 */
	public function check_addons() {
		$this->load_plugins();

		if ( ! function_exists( 'is_plugin_active' ) ){
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}

		foreach ( $this->plugins as $plugin_class => $plugin ) {
			/**
			 * Load addon code if the plugin is active
			 */
			if ( is_plugin_active( $plugin->file ) ) {
				$this->load_addon( $plugin->class );
			}
		}
	}

	/**
	 * Loads plugins from json file
	 *
	 * All the addon plugins are defined there.
	 *
	 * The file is located at the root map of this plugin
	 *
	 * @since 1.1.0
	 */
	private function load_plugins() {
		$file          = file_get_contents( CAF_DIR . 'addons.json' );
		$this->plugins = json_decode( $file );
	}

	/**
	 * Dynamically Loads addon plugin configuration class
	 *
	 * For example:
	 * /controller/addons/google-analyticator/google-analyticator.php
	 *
	 * @param $class    string  Plugin class name
	 *
	 * @since 1.1.0
	 */
	private function load_addon( $class ) {
		$full_class_name = 'cookiebot_addons_framework\\controller\\addons\\' . $class;

		/**
		 * Load addon class
		 */

		if ( class_exists( $full_class_name ) ) {
			new $full_class_name;
		}
	}
}
