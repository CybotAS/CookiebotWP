<?php
/**
 * Tests for which account script Account_Service loads on the dashboard.
 *
 * @package cookiebot
 * @subpackage tests
 */

namespace cybot\cookiebot\tests\integration\lib;

use cybot\cookiebot\lib\Account_Service;
use cybot\cookiebot\lib\Cookiebot_Frame;
use cybot\cookiebot\settings\pages\Dashboard_Page;
use WP_UnitTestCase;

/**
 * Test class for Account_Service::cookiebot_admin_script().
 */
class Test_Account_Service_Scripts extends WP_UnitTestCase {

	const HOOK            = 'toplevel_page_cookiebot';
	const DOMAIN_GROUP_ID = 'aabbccdd-1111-2222-3333-aabbccddeeff';
	const SETTINGS_ID     = 'Ab12Cd34Ef56G_';

	/**
	 * Set up test fixtures.
	 *
	 * @return void
	 */
	public function set_up() {
		parent::set_up();
		wp_dequeue_script( 'cookiebot-account-js' );
		wp_deregister_script( 'cookiebot-account-js' );
		wp_dequeue_script( 'cookiebot-account-static-js' );
		wp_deregister_script( 'cookiebot-account-static-js' );
		delete_option( 'cookiebot-cbid' );
		delete_option( 'cookiebot-user-data' );
		delete_option( 'cookiebot-auth-token' );
		delete_option( 'cookiebot-uc-onboarded-via-signup' );
	}

	/**
	 * Run the enqueue callback for the dashboard page.
	 *
	 * @return void
	 */
	private function enqueue() {
		( new Account_Service() )->cookiebot_admin_script( self::HOOK );
	}

	public function test_cookiebot_id_does_not_load_account_script() {
		update_option( 'cookiebot-cbid', self::DOMAIN_GROUP_ID );

		$this->enqueue();

		$this->assertFalse( wp_script_is( 'cookiebot-account-js', 'enqueued' ) );
		$this->assertFalse( wp_script_is( 'cookiebot-account-static-js', 'enqueued' ) );
	}

	public function test_cookiebot_id_with_user_data_does_not_load_account_script() {
		update_option( 'cookiebot-cbid', self::DOMAIN_GROUP_ID );
		update_option( 'cookiebot-user-data', array( 'email' => 'user@example.com' ) );

		$this->enqueue();

		$this->assertFalse( wp_script_is( 'cookiebot-account-js', 'enqueued' ) );
		$this->assertFalse( wp_script_is( 'cookiebot-account-static-js', 'enqueued' ) );
	}

	public function test_session_expired_still_loads_static_account_script() {
		update_option( 'cookiebot-cbid', self::SETTINGS_ID );
		update_option( 'cookiebot-user-data', array( 'email' => 'user@example.com' ) );
		update_option( 'cookiebot-uc-onboarded-via-signup', true );

		$this->enqueue();

		$this->assertTrue( wp_script_is( 'cookiebot-account-static-js', 'enqueued' ) );
		$this->assertFalse( wp_script_is( 'cookiebot-account-js', 'enqueued' ) );
	}

	/**
	 * Run the enqueue callback, then render the dashboard, as a real request does.
	 *
	 * @return void
	 */
	private function enqueue_and_render_dashboard() {
		$this->enqueue();
		ob_start();
		( new Dashboard_Page() )->display();
		ob_end_clean();
	}

	/**
	 * Number of account scripts enqueued.
	 *
	 * @return int
	 */
	private function enqueued_account_scripts() {
		return (int) wp_script_is( 'cookiebot-account-js', 'enqueued' )
			+ (int) wp_script_is( 'cookiebot-account-static-js', 'enqueued' );
	}

	public function test_cookiebot_id_session_expired_loads_only_static_account_script() {
		update_option( 'cookiebot-cbid', self::DOMAIN_GROUP_ID );
		update_option( 'cookiebot-user-data', array( 'email' => 'user@example.com' ) );
		update_option( 'cookiebot-uc-onboarded-via-signup', true );

		$this->enqueue_and_render_dashboard();

		$this->assertTrue( wp_script_is( 'cookiebot-account-static-js', 'enqueued' ) );
		$this->assertSame( 1, $this->enqueued_account_scripts() );
	}

	public function test_cookiebot_id_trial_expired_loads_only_account_script() {
		update_option( 'cookiebot-cbid', self::DOMAIN_GROUP_ID );
		update_option( 'cookiebot-auth-token', 'token' );
		update_option(
			'cookiebot-user-data',
			array(
				'email'         => 'user@example.com',
				'subscriptions' => array(
					'active' => array( 'subscription_status' => 'cancelled' ),
				),
			)
		);

		$this->enqueue_and_render_dashboard();

		$this->assertTrue( wp_script_is( 'cookiebot-account-js', 'enqueued' ) );
		$this->assertSame( 1, $this->enqueued_account_scripts() );
	}

	public function test_shows_connected_view_uses_the_given_id() {
		// The stored ID is ignored: only the arguments decide.
		update_option( 'cookiebot-cbid', self::SETTINGS_ID );
		$user_data = array( 'email' => 'user@example.com' );

		$this->assertTrue( Cookiebot_Frame::shows_connected_view( self::DOMAIN_GROUP_ID, $user_data ) );
		$this->assertFalse( Cookiebot_Frame::shows_connected_view( self::SETTINGS_ID, $user_data ) );
		$this->assertTrue( Cookiebot_Frame::shows_connected_view( self::SETTINGS_ID, '' ) );
		$this->assertFalse( Cookiebot_Frame::shows_connected_view( '', '' ) );
	}

	public function test_settings_id_without_user_data_does_not_load_account_script() {
		update_option( 'cookiebot-cbid', self::SETTINGS_ID );

		$this->enqueue();

		$this->assertFalse( wp_script_is( 'cookiebot-account-js', 'enqueued' ) );
	}

	public function test_settings_id_with_user_data_loads_account_script() {
		update_option( 'cookiebot-cbid', self::SETTINGS_ID );
		update_option( 'cookiebot-user-data', array( 'email' => 'user@example.com' ) );
		update_option( 'cookiebot-auth-token', 'token' );

		$this->enqueue();

		$this->assertTrue( wp_script_is( 'cookiebot-account-js', 'enqueued' ) );
	}

	public function test_no_id_still_loads_account_script() {
		$this->enqueue();

		$this->assertTrue( wp_script_is( 'cookiebot-account-js', 'enqueued' ) );
	}

	public function test_other_admin_pages_load_nothing() {
		( new Account_Service() )->cookiebot_admin_script( 'index.php' );

		$this->assertFalse( wp_script_is( 'cookiebot-account-js', 'enqueued' ) );
	}
}
