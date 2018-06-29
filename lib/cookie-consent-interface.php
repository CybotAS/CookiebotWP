<?php

namespace cookiebot_addons\lib;

Interface Cookie_Consent_Interface {

	/**
	 * Scans cookiebot consent cookie and fills in $states with accepted consents.
	 *
	 * @since 1.2.0
	 */
	public function scan_cookie();

	/**
	 * Adds state to $states variable
	 *
	 * @param $state    string  new state
	 *
	 * @since 1.2.0
	 */
	public function add_state( $state );

	/**
	 * Returns cookiebot cookie consent state
	 *
	 * @return array    array   List of accepted cookiebot consents
	 *
	 * @since 1.2.0
	 */
	public function get_cookie_states();

	/**
	 * Checks if the cookie state is accepted
	 *
	 * @param $state    string  Cookie state to check if it is accepted
	 *
	 * @return bool
	 *
	 * @since 1.2.0
	 */
	public function is_cookie_state_accepted( $state );

	/**
	 * Checks if the cookie states are accepted.
	 *
	 * @param $states    array  Cookie states to check if it is accepted.
	 *
	 * @return bool
	 *
	 * @since 1.3.0
	 */
	public function are_cookie_states_accepted( $states );
}
