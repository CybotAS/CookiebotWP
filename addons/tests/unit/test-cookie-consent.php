<?php

namespace cookiebot_addons\tests\unit;

use cookiebot_addons\lib\Cookie_Consent;
use DI\ContainerBuilder;
use DI;

class Test_Cookie_Consent extends \WP_UnitTestCase {

	/**
	 * @var string Encoded json format
	 */
	private $cookie;

	public function setUp() {
		$this->cookie = '{"stamp":"0boMmPgsG8gUTRvMkOLtyZ1uLvOFJobBbNb23IZO/TpY3eETvRxFfg==","necessary":"true","preferences":"true","statistics":"false","marketing":"false","ver":"1","utc":"1557479161596"}';
	}

	/**
	 * Test Cookie Consent with valid encoded json format
	 *
	 * @since 2.4.1
	 */
	public function test_scan_cookie_with_encoded_json_format() {
		$cookie_consent = new Cookie_Consent( $this->cookie );

		$this->assertEquals( $cookie_consent->get_cookie_states(), array( 'necessary', 'preferences' ) );
	}

	/**
	 * Test Cookie Consent with unchecked status
	 * Only necessary type should be allowed
	 *
	 * @since 2.4.1
	 */
	public function test_scan_cookie_with_zero_value() {
		$cookie_consent = new Cookie_Consent( 0 );

		$this->assertEquals( $cookie_consent->get_cookie_states(), array( 'necessary' ) );
	}

	/**
	 * Test Cookie Consent with every type checked
	 *
	 * @since 2.4.1
	 */
	public function test_scan_cookie_everything_checked() {
		$cookie_consent = new Cookie_Consent( - 1 );

		$this->assertEquals( $cookie_consent->get_cookie_states(),
			array( 'necessary', 'preferences', 'statistics', 'marketing' ) );
	}

	/**
	 * Test Cookie Consent with invalid encoded json format
	 * Should return only necessary type
	 *
	 * @since 2.4.1
	 */
	public function test_scan_cookie_with_wrong_encoded_json_format() {
		$cookie_consent = new Cookie_Consent( '{"test":"test"}' );

		$this->assertEquals( $cookie_consent->get_cookie_states(), array( 'necessary' ) );
	}
}