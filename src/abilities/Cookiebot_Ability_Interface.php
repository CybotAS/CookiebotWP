<?php

namespace cybot\cookiebot\abilities;

/**
 * Interface Cookiebot_Ability_Interface
 *
 * @package cybot\cookiebot\abilities
 */
interface Cookiebot_Ability_Interface {

	/**
	 * Returns the namespaced ability name, e.g. 'cookiebot/get-status'.
	 *
	 * @return string
	 *
	 * @since 4.8.0
	 */
	public function get_name();

	/**
	 * Returns the full args array for wp_register_ability().
	 *
	 * @return array
	 *
	 * @since 4.8.0
	 */
	public function get_args();
}
