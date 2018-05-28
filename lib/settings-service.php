<?php

namespace cookiebot_addons_framework\lib;

class Settings_Service implements Settings_Service_Interface {

	/**
	 * @var \DI\Container
	 */
	public $container;

	/**
	 * Settings_Service constructor.
	 *
	 * @param $container
	 *
	 * @since 1.3.0
	 */
	public function __construct( $container ) {
		$this->container = $container;
	}

	/**
	 * Returns true if the addon is enabled in the backend
	 *
	 * @param $addon
	 *
	 * @return mixed
	 *
	 * @since 1.3.0
	 */
	public function is_addon_enabled( $addon ) {
		return ( get_option( 'cookiebot-addons-active-' . sanitize_key( $addon ), 'yes' ) == 'yes' ) ? true : false;
	}

	/**
	 * Returns true if the addon is installed
	 *
	 * @param $addon
	 *
	 * @return int|\WP_Error
	 *
	 * @since 1.3.0
	 */
	public function is_addon_installed( $addon ) {
		return validate_plugin( $addon );
	}

	/**
	 * Enable the addon - checked the addon
	 *
	 * @param $addon
	 *
	 * @since 1.3.0
	 */
	public function enable_addon( $addon ) {
		//TODO enable addon
	}

	/**
	 * Disable the addon - unchecked the addon
	 *
	 * @param $addon
	 *
	 * @since 1.3.0
	 */
	public function disable_addon( $addon ) {
		//TODO disable addon
	}

	/**
	 * Returns addons one by one through a generator
	 *
	 * @return \Generator
	 * @throws \DI\DependencyException
	 * @throws \DI\NotFoundException
	 *
	 * @since 1.3.0
	 */
	public function get_addons( ) {
		foreach ( $this->container->get( 'plugins' ) as $addon ) {
			yield $this->container->get( $addon->class );
		}
	}

	/**
	 * Returns active addons
	 *
	 * @return array
	 * @throws \DI\DependencyException
	 * @throws \DI\NotFoundException
	 *
	 * @since 1.3.0
	 */
	public function get_active_addons() {
		$active_addons = array();

		foreach ( $this->container->get( 'plugins' ) as $plugin_class => $plugin ) {
			$addon = $this->container->get( $plugin->class );

			/**
			 * Load addon code if the plugin is active and addon is activated
			 */
			if ( $addon->is_addon_enabled() && ! is_wp_error( $addon->is_addon_installed() ) ) {
				$active_addons[] = $addon;
			}
		}

		return $active_addons;
	}
}