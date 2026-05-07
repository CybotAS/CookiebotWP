<?php
/**
 * Tests for Get_Status_Ability.
 *
 * @package cookiebot
 * @subpackage tests
 */

namespace cybot\cookiebot\tests\integration\abilities;

use cybot\cookiebot\abilities\Get_Status_Ability;
use WP_UnitTestCase;

/**
 * Test class for Get_Status_Ability.
 *
 * @since 4.8.0
 */
class Test_Get_Status_Ability extends WP_UnitTestCase {

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
		update_option( 'cookiebot-banner-enabled', '1' );
	}

	/**
	 * Test get_name returns correct ability name.
	 *
	 * @return void
	 */
	public function test_get_name() {
		$this->assertSame( 'cookiebot/get-status', ( new Get_Status_Ability() )->get_name() );
	}

	/**
	 * Test output has all required keys.
	 *
	 * @return void
	 */
	public function test_output_has_all_required_keys() {
		$args   = ( new Get_Status_Ability() )->get_args();
		$result = call_user_func( $args['execute_callback'] );
		foreach ( array( 'cbid_set', 'gcm_enabled', 'blocking_mode', 'banner_enabled', 'plugin_version' ) as $key ) {
			$this->assertArrayHasKey( $key, $result );
		}
	}

	/**
	 * Test cbid_set is true when configured.
	 *
	 * @return void
	 */
	public function test_cbid_set_true_when_configured() {
		$args   = ( new Get_Status_Ability() )->get_args();
		$result = call_user_func( $args['execute_callback'] );
		$this->assertTrue( $result['cbid_set'] );
	}

	/**
	 * Test cbid_set is false when empty.
	 *
	 * @return void
	 */
	public function test_cbid_set_false_when_empty() {
		update_option( 'cookiebot-cbid', '' );
		$args   = ( new Get_Status_Ability() )->get_args();
		$result = call_user_func( $args['execute_callback'] );
		$this->assertFalse( $result['cbid_set'] );
	}

	/**
	 * Test gcm_enabled reflects option.
	 *
	 * @return void
	 */
	public function test_gcm_enabled_reflects_option() {
		$args   = ( new Get_Status_Ability() )->get_args();
		$result = call_user_func( $args['execute_callback'] );
		$this->assertTrue( $result['gcm_enabled'] );

		update_option( 'cookiebot-gcm', '0' );
		$result = call_user_func( $args['execute_callback'] );
		$this->assertFalse( $result['gcm_enabled'] );
	}

	/**
	 * Test blocking_mode reflects option.
	 *
	 * @return void
	 */
	public function test_blocking_mode_reflects_option() {
		$args   = ( new Get_Status_Ability() )->get_args();
		$result = call_user_func( $args['execute_callback'] );
		$this->assertSame( 'auto', $result['blocking_mode'] );
	}

	/**
	 * Test banner_enabled reflects option.
	 *
	 * @return void
	 */
	public function test_banner_enabled_reflects_option() {
		$args   = ( new Get_Status_Ability() )->get_args();
		$result = call_user_func( $args['execute_callback'] );
		$this->assertTrue( $result['banner_enabled'] );

		update_option( 'cookiebot-banner-enabled', '0' );
		$result = call_user_func( $args['execute_callback'] );
		$this->assertFalse( $result['banner_enabled'] );
	}

	/**
	 * Test show_in_rest is true.
	 *
	 * @return void
	 */
	public function test_show_in_rest_true() {
		$args = ( new Get_Status_Ability() )->get_args();
		$this->assertTrue( $args['meta']['show_in_rest'] );
	}
}
