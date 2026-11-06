<?php
/**
 * Tests for Setup_Offer.
 *
 * @package cookiebot
 * @subpackage tests
 */

namespace cybot\cookiebot\tests\integration\lib;

use cybot\cookiebot\lib\Setup_Offer;
use WP_UnitTestCase;

/**
 * Test class for Setup_Offer.
 */
class Test_Setup_Offer extends WP_UnitTestCase {

	const UTM = 'utm_source=wordpress&utm_medium=referral&utm_campaign=banner&utm_content=create_account';

	/**
	 * Test the offer is active one second before the end.
	 *
	 * @return void
	 */
	public function test_active_one_second_before_end() {
		$this->assertTrue( Setup_Offer::is_active( 1801353599 ) );
	}

	/**
	 * Test the offer is inactive at the end.
	 *
	 * @return void
	 */
	public function test_inactive_at_end() {
		$this->assertFalse( Setup_Offer::is_active( 1801353600 ) );
	}

	/**
	 * Test the signup URL carries the coupon while the offer is active.
	 *
	 * @return void
	 */
	public function test_signup_url_with_coupon() {
		$this->assertSame(
			'https://admin.cookiebot.com/en/signup/?coupon=WPPLUGIN20&' . self::UTM,
			Setup_Offer::get_signup_url( 1801353599 )
		);
	}

	/**
	 * Test the signup URL has no coupon once the offer has ended.
	 *
	 * @return void
	 */
	public function test_signup_url_without_coupon() {
		$this->assertSame(
			'https://admin.cookiebot.com/en/signup/?' . self::UTM,
			Setup_Offer::get_signup_url( 1801353600 )
		);
	}
}
