<?php

namespace cybot\cookiebot\lib;

use Exception;
use Generator;

/**
 * Interface Settings_Service_Interface
 *
 * @package cybot\cookiebot\lib
 */
interface Settings_Service_Interface {

	/**
	 * Settings_Service constructor.
	 *
	 * @param $container Dependency_Container
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
	 * @return Generator
	 * @throws Exception
	 *
	 * @since 1.3.0
	 */
	public function get_addons();

	/**
	 * Returns active addons
	 *
	 * @return array
	 * @throws Exception
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
	 * @param string              $src
	 *
	 * @return mixed
	 */
	public function get_placeholder( $option_key, $default_placeholder, $cookies, $src = '' );

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
