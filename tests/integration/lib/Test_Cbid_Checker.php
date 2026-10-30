<?php
/**
 * Tests for Cbid_Checker.
 *
 * @package cookiebot
 * @subpackage tests
 */

namespace cybot\cookiebot\tests\integration\lib;

use cybot\cookiebot\lib\Cbid_Checker;
use WP_Error;
use WP_UnitTestCase;

/**
 * Test class for Cbid_Checker.
 */
class Test_Cbid_Checker extends WP_UnitTestCase {

	const DOMAIN_GROUP_ID = 'aabbccdd-1111-2222-3333-aabbccddeeff';

	/**
	 * Requested URLs captured by the HTTP stub.
	 *
	 * @var array
	 */
	private $captured_urls = array();

	/**
	 * Request args captured by the HTTP stub.
	 *
	 * @var array
	 */
	private $captured_args = array();

	/**
	 * Set up test fixtures.
	 *
	 * @return void
	 */
	public function set_up() {
		parent::set_up();
		$this->captured_urls = array();
		$this->captured_args = array();
	}

	/**
	 * Stub the HTTP layer with a fixed body and status code.
	 *
	 * @param string $body Response body.
	 * @param int    $code HTTP status code.
	 * @return void
	 */
	private function stub_response( $body, $code = 200 ) {
		$this->stub(
			array(
				'headers'  => array(),
				'body'     => $body,
				'response' => array(
					'code'    => $code,
					'message' => 'OK',
				),
				'cookies'  => array(),
				'filename' => null,
			)
		);
	}

	/**
	 * Stub the HTTP layer with an arbitrary return value, capturing requests.
	 *
	 * @param array|WP_Error $result Value to return from the request.
	 * @return void
	 */
	private function stub( $result ) {
		add_filter(
			'pre_http_request',
			function ( $pre, $args, $url ) use ( $result ) {
				$this->captured_urls[] = $url;
				$this->captured_args[] = $args;
				return $result;
			},
			10,
			3
		);
	}

	/**
	 * Test normalize trims whitespace.
	 *
	 * @return void
	 */
	public function test_normalize_trims_whitespace() {
		$this->assertSame( 'abc', Cbid_Checker::normalize( "  abc \n" ) );
		$this->assertSame( '', Cbid_Checker::normalize( null ) );
	}

	/**
	 * Domain Group IDs are lowercased: the front end only recognises lowercase UUIDs as Cookiebot IDs.
	 * Settings IDs are case-sensitive and keep their case.
	 *
	 * @return void
	 */
	public function test_normalize_lowercases_domain_group_id_only() {
		$this->assertSame( 'aabbccdd-1111-2222-3333-aabbccddeeff', Cbid_Checker::normalize( ' AABBCCDD-1111-2222-3333-AABBCCDDEEFF ' ) );
		$this->assertSame( 'Ab12Cd34Ef56Gh', Cbid_Checker::normalize( 'Ab12Cd34Ef56Gh' ) );
	}

	/**
	 * Settings IDs must be alphanumeric; a right-length value with punctuation is invalid.
	 *
	 * @return void
	 */
	public function test_settings_id_with_punctuation_is_invalid() {
		$this->assertSame( Cbid_Checker::TYPE_INVALID, Cbid_Checker::get_type( 'abc-defgh' ) );
		$this->assertSame( Cbid_Checker::TYPE_INVALID, Cbid_Checker::get_type( 'abcdefg hijklm' ) );
		$this->assertSame( Cbid_Checker::TYPE_SETTINGS, Cbid_Checker::get_type( 'Ab12Cd34Ef56Gh' ) );
	}

	/**
	 * Test get_type by length.
	 *
	 * @return void
	 */
	public function test_get_type_by_length() {
		$this->assertSame( Cbid_Checker::TYPE_DOMAIN_GROUP, Cbid_Checker::get_type( self::DOMAIN_GROUP_ID ) );
		$this->assertSame( Cbid_Checker::TYPE_SETTINGS, Cbid_Checker::get_type( str_repeat( 'a', 9 ) ) );
		$this->assertSame( Cbid_Checker::TYPE_SETTINGS, Cbid_Checker::get_type( str_repeat( 'a', 14 ) ) );
		$this->assertSame( Cbid_Checker::TYPE_INVALID, Cbid_Checker::get_type( '' ) );
		$this->assertSame( Cbid_Checker::TYPE_INVALID, Cbid_Checker::get_type( str_repeat( 'a', 8 ) ) );
		$this->assertSame( Cbid_Checker::TYPE_INVALID, Cbid_Checker::get_type( str_repeat( 'a', 10 ) ) );
		$this->assertSame( Cbid_Checker::TYPE_INVALID, Cbid_Checker::get_type( str_repeat( 'a', 37 ) ) );
		$this->assertSame( Cbid_Checker::TYPE_DOMAIN_GROUP, Cbid_Checker::get_type( '  ' . self::DOMAIN_GROUP_ID . ' ' ) );
	}

	/**
	 * Test get_site_host strips port and path and lowercases.
	 *
	 * @return void
	 */
	public function test_get_site_host_strips_port_and_path() {
		update_option( 'home', 'http://LocalHost:8080/wp' );
		$this->assertSame( 'localhost', Cbid_Checker::get_site_host() );
	}

	/**
	 * Test check builds the URL with referer host.
	 *
	 * @return void
	 */
	public function test_check_builds_url_with_referer_host() {
		$this->stub_response( 'var CookieConsent={};' );
		Cbid_Checker::check( self::DOMAIN_GROUP_ID, 'clientsite.com' );
		$this->assertCount( 1, $this->captured_urls );
		$this->assertSame(
			'https://consent.cookiebot.com/aabbccdd-1111-2222-3333-aabbccddeeff/cc.js?referer=clientsite.com',
			$this->captured_urls[0]
		);
		$this->assertSame( 1024, $this->captured_args[0]['limit_response_size'] );
	}

	/**
	 * Test banner script response is a match.
	 *
	 * @return void
	 */
	public function test_banner_script_is_match() {
		$this->stub_response( 'var CookieConsent={};' );
		$this->assertSame( Cbid_Checker::RESULT_MATCH, Cbid_Checker::check( self::DOMAIN_GROUP_ID, 'clientsite.com' ) );
	}

	/**
	 * Test unauthorized domain is not set up.
	 *
	 * @return void
	 */
	public function test_unknown_well_formed_id_is_not_set_up() {
		$this->stub_response( "if(console){var cookiedomainwarning='Error: The domain CLIENTSITE.COM is not authorized to show the cookie banner for domain group ID aabbccdd-1111-2222-3333-aabbccddeeff.'" );
		$this->assertSame( Cbid_Checker::RESULT_NOT_SET_UP, Cbid_Checker::check( self::DOMAIN_GROUP_ID, 'clientsite.com' ) );
	}

	/**
	 * Test empty body is invalid.
	 *
	 * @return void
	 */
	public function test_empty_body_is_invalid() {
		$this->stub_response( '' );
		$this->assertSame( Cbid_Checker::RESULT_INVALID, Cbid_Checker::check( self::DOMAIN_GROUP_ID, 'clientsite.com' ) );
	}

	/**
	 * Test out of region body is unknown.
	 *
	 * @return void
	 */
	public function test_out_of_region_is_unknown() {
		$this->stub_response( 'CookieConsent.setOutOfRegion();' );
		$this->assertSame( Cbid_Checker::RESULT_UNKNOWN, Cbid_Checker::check( self::DOMAIN_GROUP_ID, 'clientsite.com' ) );
	}

	/**
	 * Test HTTP errors are unknown.
	 *
	 * @return void
	 */
	public function test_http_error_is_unknown() {
		$this->stub( new WP_Error( 'http_request_failed', 'timeout' ) );
		$this->assertSame( Cbid_Checker::RESULT_UNKNOWN, Cbid_Checker::check( self::DOMAIN_GROUP_ID, 'clientsite.com' ) );

		remove_all_filters( 'pre_http_request' );
		$this->stub_response( 'var CookieConsent={};', 500 );
		$this->assertSame( Cbid_Checker::RESULT_UNKNOWN, Cbid_Checker::check( self::DOMAIN_GROUP_ID, 'clientsite.com' ) );
	}

	/**
	 * Test Settings IDs skip HTTP.
	 *
	 * @return void
	 */
	public function test_settings_id_skips_http() {
		$this->stub_response( '' );
		$this->assertSame( Cbid_Checker::RESULT_MATCH, Cbid_Checker::check( 'abcdefghi' ) );
		$this->assertCount( 0, $this->captured_urls );
	}

	/**
	 * Test invalid length skips HTTP.
	 *
	 * @return void
	 */
	public function test_invalid_length_skips_http() {
		$this->stub_response( '' );
		$this->assertSame( Cbid_Checker::RESULT_INVALID, Cbid_Checker::check( 'short' ) );
		$this->assertCount( 0, $this->captured_urls );
	}
}
