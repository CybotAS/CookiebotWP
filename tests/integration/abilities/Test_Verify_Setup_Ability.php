<?php
/**
 * Tests for Verify_Setup_Ability.
 *
 * @package cookiebot
 * @subpackage tests
 */

namespace cybot\cookiebot\tests\integration\abilities;

use cybot\cookiebot\abilities\Verify_Setup_Ability;
use WP_UnitTestCase;

/**
 * Test class for Verify_Setup_Ability.
 *
 * @since 4.8.0
 */
class Test_Verify_Setup_Ability extends WP_UnitTestCase {

	/**
	 * Set up test fixtures.
	 *
	 * @return void
	 */
	public function set_up() {
		parent::set_up();
		update_option( 'cookiebot-cbid', 'aabbccdd-1111-2222-3333-aabbccddeeff' );
		update_option( 'cookiebot-gcm', '1' );
		update_option( 'cookiebot-cookie-blocking-mode', 'auto' );
	}

	/**
	 * Test get_name returns correct ability name.
	 *
	 * @return void
	 */
	public function test_get_name() {
		$this->assertSame( 'cookiebot/verify-setup', ( new Verify_Setup_Ability() )->get_name() );
	}

	/**
	 * Test no issues when fully configured.
	 *
	 * @return void
	 */
	public function test_no_issues_when_fully_configured() {
		$args   = ( new Verify_Setup_Ability() )->get_args();
		$result = call_user_func( $args['execute_callback'] );
		$this->assertEmpty( $result['issues'] );
		$this->assertTrue( $result['cbid_configured'] );
		$this->assertTrue( $result['gcm_enabled'] );
		$this->assertTrue( $result['blocking_mode_automatic'] );
	}

	/**
	 * Test issue when CBID is missing.
	 *
	 * @return void
	 */
	public function test_issue_when_cbid_missing() {
		update_option( 'cookiebot-cbid', '' );
		delete_site_option( 'cookiebot-cbid' );
		$args   = ( new Verify_Setup_Ability() )->get_args();
		$result = call_user_func( $args['execute_callback'] );
		$this->assertFalse( $result['cbid_configured'] );
		$this->assertNotEmpty( $result['issues'] );
		$this->assertStringContainsString( 'Domain Group ID', implode( ' ', $result['issues'] ) );
	}

	/**
	 * Test issue when blocking mode is manual.
	 *
	 * @return void
	 */
	public function test_issue_when_blocking_mode_manual() {
		update_option( 'cookiebot-cookie-blocking-mode', 'manual' );
		$args   = ( new Verify_Setup_Ability() )->get_args();
		$result = call_user_func( $args['execute_callback'] );
		$this->assertFalse( $result['blocking_mode_automatic'] );
		$this->assertNotEmpty( $result['issues'] );
	}

	/**
	 * Test output has all required keys.
	 *
	 * @return void
	 */
	public function test_output_has_all_required_keys() {
		$args   = ( new Verify_Setup_Ability() )->get_args();
		$result = call_user_func( $args['execute_callback'] );
		foreach ( array( 'cbid_configured', 'configured_domain', 'gcm_enabled', 'blocking_mode_automatic', 'issues' ) as $key ) {
			$this->assertArrayHasKey( $key, $result );
		}
	}

	/**
	 * Test configured_domain is a string.
	 *
	 * @return void
	 */
	public function test_configured_domain_is_string() {
		$args   = ( new Verify_Setup_Ability() )->get_args();
		$result = call_user_func( $args['execute_callback'] );
		$this->assertIsString( $result['configured_domain'] );
		$this->assertNotEmpty( $result['configured_domain'] );
	}

	/**
	 * Test issue when GCM is disabled.
	 *
	 * @return void
	 */
	public function test_issue_when_gcm_disabled() {
		update_option( 'cookiebot-gcm', '0' );
		$args   = ( new Verify_Setup_Ability() )->get_args();
		$result = call_user_func( $args['execute_callback'] );
		$this->assertFalse( $result['gcm_enabled'] );
		$this->assertNotEmpty( $result['issues'] );
		$this->assertStringContainsString( 'Google Consent Mode', implode( ' ', $result['issues'] ) );
	}

	/**
	 * Test show_in_rest is true.
	 *
	 * @return void
	 */
	public function test_show_in_rest_true() {
		$args = ( new Verify_Setup_Ability() )->get_args();
		$this->assertTrue( $args['meta']['show_in_rest'] );
	}
}
