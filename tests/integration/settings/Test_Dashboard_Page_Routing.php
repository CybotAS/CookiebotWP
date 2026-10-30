<?php
/**
 * Tests for Dashboard_Page routing args.
 *
 * @package cookiebot
 * @subpackage tests
 */

namespace cybot\cookiebot\tests\integration\settings;

use cybot\cookiebot\lib\Cbid_Checker;
use cybot\cookiebot\lib\Dashboard_Setup_Ajax;
use cybot\cookiebot\lib\Setup_Offer;
use cybot\cookiebot\settings\pages\Dashboard_Page;
use WP_Error;
use WP_UnitTestCase;

/**
 * Test class for Dashboard_Page args builders.
 */
class Test_Dashboard_Page_Routing extends WP_UnitTestCase {

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
		update_option( 'home', 'https://clientsite.com' );
		delete_option( 'cookiebot-cbid' );
		delete_option( Dashboard_Setup_Ajax::OPTION_SIGNUP_CLICKED );
		delete_transient( Dashboard_Setup_Ajax::TRANSIENT_JUST_CONNECTED );
	}

	/**
	 * Stub the HTTP layer for consent.cookiebot.com only, counting requests.
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
	 * Stub a 200 response with a body.
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

	public function test_welcome_args_when_no_cbid() {
		$args = ( new Dashboard_Page() )->get_welcome_args();

		$this->assertSame( 'clientsite.com', $args['domain'] );
		$this->assertFalse( $args['signup_clicked'] );
		$this->assertSame( Setup_Offer::is_active(), $args['offer_active'] );
		$this->assertSame( Setup_Offer::get_signup_url(), $args['signup_url'] );
		$this->assertSame( admin_url( 'admin.php?page=cookiebot_support' ), $args['support_url'] );
	}

	public function test_welcome_args_signup_clicked() {
		update_option( Dashboard_Setup_Ajax::OPTION_SIGNUP_CLICKED, '1' );

		$args = ( new Dashboard_Page() )->get_welcome_args();

		$this->assertTrue( $args['signup_clicked'] );
	}

	public function test_connected_args_domain_group_runs_check() {
		update_option( 'cookiebot-cbid', self::DOMAIN_GROUP_ID );
		$this->stub_body( 'Domain is not authorized to show the cookie banner' );

		$args = ( new Dashboard_Page() )->get_connected_args();

		$this->assertSame( 'not_set_up', $args['check_result'] );
		$this->assertSame( 'domain_group', $args['id_type'] );
		$this->assertFalse( $args['just_connected'] );
		$this->assertSame( self::DOMAIN_GROUP_ID, $args['cbid'] );
		$this->assertSame( 'clientsite.com', $args['domain'] );
		$this->assertFalse( $args['is_network_id'] );
		$this->assertSame( home_url( '/' ), $args['preview_url'] );
		$this->assertSame( 1, $this->http_calls );
	}

	public function test_connected_args_just_connected_skips_check() {
		update_option( 'cookiebot-cbid', self::DOMAIN_GROUP_ID );
		set_transient( Dashboard_Setup_Ajax::TRANSIENT_JUST_CONNECTED, 1, 60 );
		$this->stub( new WP_Error( 'should_not_be_called' ) );

		$args = ( new Dashboard_Page() )->get_connected_args();

		$this->assertSame( 'match', $args['check_result'] );
		$this->assertTrue( $args['just_connected'] );
		$this->assertFalse( get_transient( Dashboard_Setup_Ajax::TRANSIENT_JUST_CONNECTED ) );
		$this->assertSame( 0, $this->http_calls );
	}

	public function test_connected_dashboard_without_warning_when_check_unknown() {
		update_option( 'cookiebot-cbid', self::DOMAIN_GROUP_ID );
		$this->stub( new WP_Error( 'http_request_failed', 'Timeout' ) );

		$args = ( new Dashboard_Page() )->get_connected_args();

		$this->assertSame( Cbid_Checker::RESULT_UNKNOWN, $args['check_result'] );
		$this->assertFalse( $args['just_connected'] );
	}

	public function test_connected_args_settings_id() {
		update_option( 'cookiebot-cbid', 'abcdefghi' );
		$this->stub( new WP_Error( 'should_not_be_called' ) );

		$args = ( new Dashboard_Page() )->get_connected_args();

		$this->assertSame( 'settings', $args['id_type'] );
		$this->assertSame( 'match', $args['check_result'] );
		$this->assertFalse( $args['just_connected'] );
		$this->assertSame( 0, $this->http_calls );
	}
}
