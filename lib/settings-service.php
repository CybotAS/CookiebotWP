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
		$option = get_option( 'cookiebot_available_addons' );

		if ( isset( $option[ $addon ] ) && ! isset( $option[ $addon ]['enabled'] ) ) {
			return false;
		}

		return true;
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
		return ( is_wp_error( validate_plugin( $addon ) ) ) ? false : true;
	}

	/**
	 * Returns true if the addon plugin is activated
	 *
	 * @param $addon
	 *
	 * @return bool
	 *
	 * @since 1.3.0
	 */
	public function is_addon_activated( $addon ) {
		return ( is_plugin_active( $addon ) ) ? true : false;
	}

	public function get_cookie_types( $addon ) {
		$option = get_option( 'cookiebot_available_addons' );

		if ( isset( $option[ $addon ]['cookie_type'] ) && is_array( $option[ $addon ]['cookie_type'] ) ) {
			return $option[ $addon ]['cookie_type'];
		}

		return array();
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
	public function get_addons() {
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

		foreach ( $this->get_addons() as $addon ) {
			/**
			 * Load addon code if the plugin is active and addon is activated
			 */
			if ( $addon->is_addon_enabled() && ! is_wp_error( $addon->is_addon_installed() ) && $addon->is_addon_activated() ) {
				$active_addons[] = $addon;
			}
		}

		return $active_addons;
	}
}