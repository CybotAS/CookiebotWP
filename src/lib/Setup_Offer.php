<?php

namespace cybot\cookiebot\lib;

/**
 * Decides whether the first-year setup offer is active and which Cookiebot
 * signup URL to show. The offer ends at 2027-01-31 00:00:00 UTC.
 */
class Setup_Offer {

	const ENDS_AT    = 1801353600;
	const COUPON     = 'WPPLUGIN20';
	const SIGNUP_URL = 'https://admin.cookiebot.com/en/signup/';
	const SIGNUP_UTM = 'utm_source=wordpress&utm_medium=referral&utm_campaign=banner&utm_content=create_account';

	/**
	 * Whether the offer is still active.
	 *
	 * @param int|null $now Unix timestamp; defaults to the current time.
	 * @return bool
	 */
	public static function is_active( $now = null ) {
		$now = null === $now ? time() : $now;

		return $now < self::ENDS_AT;
	}

	/**
	 * Signup URL, including the coupon only while the offer is active.
	 *
	 * @param int|null $now Unix timestamp; defaults to the current time.
	 * @return string
	 */
	public static function get_signup_url( $now = null ) {
		if ( self::is_active( $now ) ) {
			return self::SIGNUP_URL . '?coupon=' . self::COUPON . '&' . self::SIGNUP_UTM;
		}

		return self::SIGNUP_URL . '?' . self::SIGNUP_UTM;
	}
}
