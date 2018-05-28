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

	public function get_addons() {
		return $this->container->get( 'plugins' );
	}

	public function get_addon_by_generator( ) {
		foreach ( $this->get_addons() as $addon ) {
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

	/**
	 * Returns all supported addons
	 *
	 * @return array
	 * @throws \DI\DependencyException
	 * @throws \DI\NotFoundException
	 */
	public function get_addon_list() {
		$addons = [];

		foreach ( $this->container->get( 'plugins' ) as $plugin_class => $plugin ) {
			$addons[ ( is_plugin_active( $plugin->file ) ? 'available' : 'unavailable' ) ][ $plugin_class ] = [
				'name'      => $plugin->name,
				'file'      => $plugin->file,
				'class'     => $plugin->class,
				'available' => ( is_plugin_active( $plugin->file ) ? true : false ),
			];
		}

		return $addons;
	}

	/**
	 * Get list of active addons
	 *
	 * TODO test this method
	 *
	 * @since 1.2.0
	 */
	public function get_checked_addons() {
		$activePlugins = get_option( 'cookiebot-addons-activated', '' );
		if ( ! empty( $activePlugins ) ) {
			return explode( ';', $activePlugins );
		}

		return false;
	}
}