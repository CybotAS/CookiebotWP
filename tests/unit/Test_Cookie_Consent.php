<?php

namespace cybot\cookiebot\tests\unit;

use cybot\cookiebot\lib\Cookie_Consent;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Cookie_Consent extends WP_UnitTestCase {
	const COOKIE = '{"stamp":"0boMmPgsG8gUTRvMkOLtyZ1uLvOFJobBbNb23IZO/TpY3eETvRxFfg==","necessary":"true","preferences":"true","statistics":"false","marketing":"false","ver":"1","utc":"1557479161596"}';

	/**
	 * Test Cookie Consent with valid encoded json format
	 *
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 */
	public function test_scan_cookie_with_encoded_json_format() {
		$cookie_consent = new Cookie_Consent( self::COOKIE );

		$this->assertEquals( array( 'necessary', 'preferences' ), $cookie_consent->get_cookie_states() );
	}

	/**
	 * Test Cookie Consent with unchecked status
	 * Only necessary type should be allowed
	 *
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 */
	public function test_scan_cookie_with_zero_value() {
		$cookie_consent = new Cookie_Consent( 0 );

		$this->assertEquals( array( 'necessary' ), $cookie_consent->get_cookie_states() );
	}

	/**
	 * Test Cookie Consent with every type checked
	 *
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 */
	public function test_scan_cookie_everything_checked() {
		$cookie_consent = new Cookie_Consent( - 1 );

		$this->assertEquals(
			array( 'necessary', 'preferences', 'statistics', 'marketing' ),
			$cookie_consent->get_cookie_states()
		);
	}

	/**
	 * Test Cookie Consent with invalid encoded json format
	 * Should return only necessary type
	 *
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 */
	public function test_scan_cookie_with_wrong_encoded_json_format() {
		$cookie_consent = new Cookie_Consent( '{"test":"test"}' );

		$this->assertEquals( array( 'necessary' ), $cookie_consent->get_cookie_states() );
	}
}
