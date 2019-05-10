<?php

namespace cookiebot_addons\lib;

class Cookie_Consent implements Cookie_Consent_Interface {

	/**
	 * Array of cookiebot consent states
	 *
	 * It can have 4 items:
	 * - necessary
	 * - preferences
	 * - statistics
	 * - marketing
	 *
	 * @var array  consent state
	 *
	 * @since 1.2.0
	 */
	private $states = array();

	/**
	 * @var array|null
	 */
	private $cookie;

	/**
	 * Scan cookiebot cookie
	 *
	 * @param   $default_cookie
	 *
	 * @since 1.2.0
	 * @version 2.4.1
	 */
	public function __construct($default_cookie = null) {
		$this->cookie = ( isset( $_COOKIE['CookieConsent'] ) ) ? $_COOKIE['CookieConsent'] : $default_cookie;

		$this->scan_cookie();
	}

	/**
	 * Scans cookiebot consent cookie and fills in $states with accepted consents.
	 *
	 * @since 1.2.0
	 * @version 2.4.1
	 */
	public function scan_cookie() {
		//default - set strictly necessary cookies
		$this->add_state( 'necessary' );

		if ( ! empty( $this->cookie ) ) {
			switch ( $this->cookie ) {
				case "0":
					//The user has not accepted cookies - set strictly necessary cookies only
					break;

				case "-1":
					//The user is not within a region that requires consent - all cookies are accepted
					$this->add_state( 'preferences' );
					$this->add_state( 'statistics' );
					$this->add_state( 'marketing' );
					break;

				default: //The user has accepted one or more type of cookies

					//Read current user consent in encoded JavaScript format
					$valid_php_json = preg_replace( '/\s*:\s*([a-zA-Z0-9_]+?)([}\[,])/', ':"$1"$2',
						preg_replace( '/([{\[,])\s*([a-zA-Z0-9_]+?):/', '$1"$2":',
							str_replace( "'", '"', stripslashes( $this->cookie ) ) ) );
					$CookieConsent  = json_decode( $valid_php_json );

					if ( isset( $CookieConsent->preferences ) && filter_var( $CookieConsent->preferences, FILTER_VALIDATE_BOOLEAN ) ) {
						//Current user accepts preference cookies
						$this->add_state( 'preferences' );
					} else {
						//Current user does NOT accept preference cookies
					}

					if ( isset( $CookieConsent->statistics ) && filter_var( $CookieConsent->statistics, FILTER_VALIDATE_BOOLEAN ) ) {
						//Current user accepts statistics cookies
						$this->add_state( 'statistics' );
					} else {
						//Current user does NOT accept statistics cookies
					}

					if ( isset( $CookieConsent->marketing ) && filter_var( $CookieConsent->marketing, FILTER_VALIDATE_BOOLEAN ) ) {
						//Current user accepts marketing cookies
						$this->add_state( 'marketing' );
					} else {
						//Current user does NOT accept marketing cookies
					}
			}
		} else {
			//The user has not accepted cookies - set strictly necessary cookies only
		}
	}

	/**
	 * Adds state to $states variable
	 *
	 * @param $state    string  new state
	 *
	 * @since 1.2.0
	 */
	public function add_state( $state ) {
		if ( ! in_array( $state, $this->states ) ) {
			$this->states[] = $state;
		}
	}

	/**
	 * Returns cookiebot cookie consent state
	 *
	 * @return array    array   List of accepted cookiebot consents
	 *
	 * @since 1.2.0
	 */
	public function get_cookie_states() {
		if ( count( $this->states ) == 0 ) {
			$this->scan_cookie();
		}

		return $this->states;
	}

	/**
	 * Checks if the cookie states are accepted.
	 *
	 * @param $states    array  Cookie states to check if it is accepted.
	 *
	 * @return bool
	 *
	 * @since 1.3.0
	 */
	public function are_cookie_states_accepted( $states ) {
		if ( is_array( $states ) ) {
			foreach ( $states as $state ) {
				if ( ! in_array( $state, $this->states ) ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Checks if the cookie state is accepted
	 *
	 * @param $state    string  Cookie state to check if it is accepted
	 *
	 * @return bool
	 */
	public function is_cookie_state_accepted( $state ) {
		return in_array( $state, $this->states );
	}
}
