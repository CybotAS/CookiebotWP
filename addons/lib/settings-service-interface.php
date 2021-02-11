<?php

namespace cookiebot_addons\lib;

use cookiebot_addons\controller\addons\Cookiebot_Addons_Interface;
use Cybot\Dependencies\DI;

Interface Settings_Service_Interface {

	/**
	 * Settings_Service constructor.
	 *
	 * @param $container DI\Container
	 *
	 * @since 1.3.0
	 */
	public function __construct( $container );

	/**
	 * Returns true if the addon is enabled in the backend
	 *
	 * @param $addon string option name
	 *
	 * @return mixed
	 *
	 * @since 1.3.0
	 */
	public function is_addon_enabled( $addon );

	/**
	 * Returns true if the addon is installed
	 *
	 * @param $addon    string  plugin file, for example: test/test.php
	 *
	 * @return int|\WP_Error
	 *
	 * @since 1.3.0
	 */
	public function is_addon_installed( $addon );

	/**
	 * Returns true if the addon plugin is activated
	 *
	 * @param $addon    string  plugin file, for example: test/test.php
	 *
	 * @return bool
	 *
	 * @since 1.3.0
	 */
	public function is_addon_activated( $addon );

	/**
	 * Returns the addon version
	 *
	 * @param $addon
	 *
	 * @return bool
	 *
	 * @since 2.2.1
	 */
	public function get_addon_version( $addon );

	/**
	 * Returns all cookie type for given addon
	 *
	 * @param $addon    string  option name
	 * @param $default  array   default cookie types
	 *
	 * @return array
	 *
	 * @since 1.3.0
	 */
	public function get_cookie_types( $addon, $default = array() );

	/**
	 * Returns regex for given addon
	 *
	 * @param $addon    string  option name
	 * @param $default  string   default regex
	 *
	 * @return string
	 *
	 * @since 2.4.5
	 */
	public function get_addon_regex( $addon, $default = '' );

	/**
	 * Returns addons one by one through a generator
	 *
	 * @return \Generator
	 * @throws DI\DependencyException
	 * @throws DI\NotFoundException
	 *
	 * @since 1.3.0
	 */
	public function get_addons();

	/**
	 * Returns active addons
	 *
	 * @return array
	 * @throws DI\DependencyException
	 * @throws DI\NotFoundException
	 *
	 * @since 1.3.0
	 */
	public function get_active_addons();

	/**
	 * returns the placeholder if it does exist
	 *
	 * @param $option_key
	 * @param $default_placeholder
	 * @param $cookies
	 *
	 * @return bool|mixed
	 *
	 * @since 1.8.0
	 */
	public function get_placeholder( $option_key, $default_placeholder, $cookies );


	/**
	 * Check if the previous version is active
	 *
	 * @param $addons array         List of addons
	 * @param $addon_class string   The name of the class
	 *
	 * @return bool
	 *
	 * @since 2.1.3
	 */
	public function is_previous_version_active( $addons, $addon_class );

	/**
	 * Checks if the addon is the latest plugin version.
	 * Latest plugin version doesn't have extended class.
	 *
	 * @param $addon
	 *
	 * @return bool
	 *
	 * @since 2.1.3
	 */
	public function is_latest_plugin_version( $addon );

	/**
	 * The cookiebot plugin is deactivated
	 * so run this function to cleanup the addons.
	 *
	 * @since 2.2.0
	 */
	public function cookiebot_deactivated();

	/**
	 * The cookiebot plugin is activated and the addon settings is activated
	 *
	 * @since 3.6.3
	 */
	public function cookiebot_activated();
}
