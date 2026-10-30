<?php
/**
 * Tests for Dashboard_Setup_Ajax.
 *
 * @package cookiebot
 * @subpackage tests
 */

namespace cybot\cookiebot\tests\integration\lib;

use cybot\cookiebot\lib\Dashboard_Setup_Ajax;
use WP_Ajax_UnitTestCase;
use WP_Error;
use WPAjaxDieContinueException;
use WPAjaxDieStopException;

/**
 * Test class for Dashboard_Setup_Ajax.
 *
 * @group ajax
 */
class Test_Dashboard_Setup_Ajax extends WP_Ajax_UnitTestCase {

	const DOMAIN_GROUP_ID = 'aabbccdd-1111-2222-3333-aabbccddeeff';

	/**
	 * Number of HTTP requests made through the stub.
	 *
	 * @var int
	 */
	private $http_calls = 0;

	/**
	 * Set up test fixtures.
	 *
	 * @return void
	 */
	public function set_up() {
		parent::set_up();
		$this->http_calls = 0;
		( new Dashboard_Setup_Ajax() )->register_hooks();
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		delete_option( 'cookiebot-cbid' );
		delete_option( Dashboard_Setup_Ajax::OPTION_SIGNUP_CLICKED );
		delete_transient( Dashboard_Setup_Ajax::TRANSIENT_JUST_CONNECTED );
		$_POST = array();
	}

	/**
	 * Tear down test fixtures.
	 *
	 * @return void
	 */
	public function tear_down() {
		$_POST = array();
		parent::tear_down();
	}

	/**
	 * Stub the HTTP layer with a body and status code.
	 *
	 * @param string $body Response body.
	 * @return void
	 */
	private function stub_body( $body ) {
		$this->stub(
			array(
				'headers'  => array(),
				'body'     => $body,
				'response' => array(
					'code'    => 200,
					'message' => 'OK',
				),
				'cookies'  => array(),
				'filename' => null,
			)
		);
	}

	/**
	 * Stub the HTTP layer with an arbitrary result, counting requests.
	 *
	 * @param array|WP_Error $result Value to return from the request.
	 * @return void
	 */
	private function stub( $result ) {
		add_filter(
			'pre_http_request',
			function ( $pre, $args, $url ) use ( $result ) {
				if ( false === strpos( $url, 'consent.cookiebot.com' ) ) {
					return $pre;
				}
				++$this->http_calls;
				return $result;
			},
			10,
			3
		);
	}

	/**
	 * Run an AJAX action and decode the JSON response.
	 *
	 * @param string $action AJAX action.
	 * @param array  $post   POST fields; a valid nonce is added unless given.
	 * @return array
	 */
	private function run_ajax( $action, $post = array() ) {
		$_POST = array_merge(
			array( 'nonce' => wp_create_nonce( Dashboard_Setup_Ajax::NONCE_ACTION ) ),
			$post
		);

		$this->_last_response = '';
		try {
			$this->_handleAjax( $action );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		} catch ( WPAjaxDieStopException $e ) {
			unset( $e );
		}

		return json_decode( $this->_last_response, true );
	}

	/**
	 * Bad nonce is rejected.
	 *
	 * @return void
	 */
	public function test_connect_rejects_bad_nonce() {
		$response = $this->run_ajax(
			'cookiebot_connect_cbid',
			array(
				'nonce' => 'x',
				'cbid'  => self::DOMAIN_GROUP_ID,
			)
		);

		$this->assertFalse( $response['success'] );
		$this->assertSame( '', get_option( 'cookiebot-cbid', '' ) );
	}

	/**
	 * Non-admin is rejected.
	 *
	 * @return void
	 */
	public function test_connect_rejects_non_admin() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'editor' ) ) );

		$response = $this->run_ajax( 'cookiebot_connect_cbid', array( 'cbid' => str_repeat( 'a', 14 ) ) );

		$this->assertFalse( $response['success'] );
		$this->assertSame( '', get_option( 'cookiebot-cbid', '' ) );
	}

	/**
	 * Wrong-length ID is invalid.
	 *
	 * @return void
	 */
	public function test_connect_invalid_length() {
		$response = $this->run_ajax( 'cookiebot_connect_cbid', array( 'cbid' => 'abc' ) );

		$this->assertFalse( $response['success'] );
		$this->assertSame( 'invalid', $response['data']['code'] );
		$this->assertSame( '', get_option( 'cookiebot-cbid', '' ) );
	}

	/**
	 * Empty endpoint response means invalid ID.
	 *
	 * @return void
	 */
	public function test_connect_empty_response_is_invalid() {
		$this->stub_body( '' );

		$response = $this->run_ajax( 'cookiebot_connect_cbid', array( 'cbid' => self::DOMAIN_GROUP_ID ) );

		$this->assertFalse( $response['success'] );
		$this->assertSame( 'invalid', $response['data']['code'] );
		$this->assertSame( '', get_option( 'cookiebot-cbid', '' ) );
	}

	/**
	 * Whitespace is trimmed and a matching ID is saved.
	 *
	 * @return void
	 */
	public function test_connect_trims_and_saves_match() {
		$this->stub_body( 'window.CookieConsent={};' );

		$response = $this->run_ajax( 'cookiebot_connect_cbid', array( 'cbid' => '  ' . self::DOMAIN_GROUP_ID . ' ' ) );

		$this->assertTrue( $response['success'] );
		$this->assertSame( admin_url( 'admin.php?page=cookiebot' ), $response['data']['redirect'] );
		$this->assertSame( self::DOMAIN_GROUP_ID, get_option( 'cookiebot-cbid' ) );
		$this->assertSame( '1', get_transient( Dashboard_Setup_Ajax::TRANSIENT_JUST_CONNECTED ) );
	}

	/**
	 * Domain not set up returns the site domain and does not save.
	 *
	 * @return void
	 */
	public function test_connect_not_set_up_returns_domain() {
		update_option( 'home', 'https://clientsite.com' );
		$this->stub_body( 'Domain is not authorized to show the cookie banner' );

		$response = $this->run_ajax( 'cookiebot_connect_cbid', array( 'cbid' => self::DOMAIN_GROUP_ID ) );

		$this->assertFalse( $response['success'] );
		$this->assertSame( 'not_set_up', $response['data']['code'] );
		$this->assertSame( 'clientsite.com', $response['data']['domain'] );
		$this->assertSame( '', get_option( 'cookiebot-cbid', '' ) );
	}

	/**
	 * Forced connect skips the check and the just-connected transient.
	 *
	 * @return void
	 */
	public function test_connect_anyway_saves_without_check() {
		add_filter(
			'pre_http_request',
			function ( $pre, $args, $url ) {
				if ( false !== strpos( $url, 'consent.cookiebot.com' ) ) {
					$this->fail( 'HTTP check must not run when forced.' );
				}
				return $pre;
			},
			10,
			3
		);

		$response = $this->run_ajax(
			'cookiebot_connect_cbid',
			array(
				'cbid'  => self::DOMAIN_GROUP_ID,
				'force' => '1',
			)
		);

		$this->assertTrue( $response['success'] );
		$this->assertSame( self::DOMAIN_GROUP_ID, get_option( 'cookiebot-cbid' ) );
		$this->assertFalse( get_transient( Dashboard_Setup_Ajax::TRANSIENT_JUST_CONNECTED ) );
	}

	/**
	 * Unknown check result still saves.
	 *
	 * @return void
	 */
	public function test_connect_unknown_saves() {
		$this->stub( new WP_Error( 'http_request_failed', 'boom' ) );

		$response = $this->run_ajax( 'cookiebot_connect_cbid', array( 'cbid' => self::DOMAIN_GROUP_ID ) );

		$this->assertTrue( $response['success'] );
		$this->assertSame( self::DOMAIN_GROUP_ID, get_option( 'cookiebot-cbid' ) );
	}

	/**
	 * Settings IDs save without any HTTP call.
	 *
	 * @return void
	 */
	public function test_connect_settings_id_saves_without_check() {
		$this->stub( new WP_Error( 'http_request_failed', 'boom' ) );

		$response = $this->run_ajax( 'cookiebot_connect_cbid', array( 'cbid' => 'abcdefghijklmn' ) );

		$this->assertTrue( $response['success'] );
		$this->assertSame( 'abcdefghijklmn', get_option( 'cookiebot-cbid' ) );
		$this->assertSame( 0, $this->http_calls );
	}

	/**
	 * An uppercase Domain Group ID is checked and saved in lowercase.
	 *
	 * @return void
	 */
	public function test_connect_saves_domain_group_id_lowercase() {
		$this->stub_body( 'window.CookieConsent={};' );

		$response = $this->run_ajax( 'cookiebot_connect_cbid', array( 'cbid' => strtoupper( self::DOMAIN_GROUP_ID ) ) );

		$this->assertTrue( $response['success'] );
		$this->assertSame( strtolower( self::DOMAIN_GROUP_ID ), get_option( 'cookiebot-cbid' ) );
	}

	/**
	 * A 9-character Settings ID with punctuation is rejected as invalid.
	 *
	 * @return void
	 */
	public function test_connect_settings_id_with_punctuation_is_invalid() {
		$response = $this->run_ajax( 'cookiebot_connect_cbid', array( 'cbid' => 'abc-defgh' ) );

		$this->assertFalse( $response['success'] );
		$this->assertSame( 'invalid', $response['data']['code'] );
		$this->assertSame( '', (string) get_option( 'cookiebot-cbid', '' ) );
	}

	/**
	 * Connecting the same ID twice succeeds (update_option returns false).
	 *
	 * @return void
	 */
	public function test_connect_same_id_twice_succeeds() {
		$this->stub_body( 'window.CookieConsent={};' );

		$first  = $this->run_ajax( 'cookiebot_connect_cbid', array( 'cbid' => self::DOMAIN_GROUP_ID ) );
		$second = $this->run_ajax( 'cookiebot_connect_cbid', array( 'cbid' => self::DOMAIN_GROUP_ID ) );

		$this->assertTrue( $first['success'] );
		$this->assertTrue( $second['success'] );
	}

	/**
	 * Disconnect clears the site option.
	 *
	 * @return void
	 */
	public function test_disconnect_clears_option() {
		update_option( 'cookiebot-cbid', self::DOMAIN_GROUP_ID );

		$response = $this->run_ajax( 'cookiebot_disconnect_cbid' );

		$this->assertTrue( $response['success'] );
		$this->assertSame( admin_url( 'admin.php?page=cookiebot' ), $response['data']['redirect'] );
		$this->assertSame( '', get_option( 'cookiebot-cbid' ) );
	}

	/**
	 * Disconnect leaves the network option alone.
	 *
	 * @return void
	 */
	public function test_disconnect_does_not_touch_network_option() {
		if ( ! is_multisite() ) {
			$this->markTestSkipped( 'Multisite only.' );
		}

		update_site_option( 'cookiebot-cbid', self::DOMAIN_GROUP_ID );
		update_option( 'cookiebot-cbid', str_repeat( 'a', 14 ) );

		$this->run_ajax( 'cookiebot_disconnect_cbid' );

		$this->assertSame( self::DOMAIN_GROUP_ID, get_site_option( 'cookiebot-cbid' ) );
	}

	/**
	 * Signup click is recorded.
	 *
	 * @return void
	 */
	public function test_signup_clicked_sets_option() {
		$response = $this->run_ajax( 'cookiebot_signup_clicked' );

		$this->assertTrue( $response['success'] );
		$this->assertSame( '1', get_option( Dashboard_Setup_Ajax::OPTION_SIGNUP_CLICKED ) );
	}
}
