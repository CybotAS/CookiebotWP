<?php
/**
 * Test_Toggle_Gcm_Ability class.
 *
 * @package cookiebot
 * @subpackage tests
 */

namespace cybot\cookiebot\tests\integration\abilities;

use cybot\cookiebot\abilities\Ability_Audit_Logger;
use cybot\cookiebot\abilities\Toggle_Gcm_Ability;
use WP_UnitTestCase;

/**
 * Tests for Toggle_Gcm_Ability.
 *
 * @since 4.8.0
 */
class Test_Toggle_Gcm_Ability extends WP_UnitTestCase {

	/**
	 * @var Ability_Audit_Logger
	 */
	private $logger;

	public function set_up() {
		parent::set_up();
		$this->logger = new Ability_Audit_Logger();
		delete_option( 'cookiebot-ai-action-log' );
	}

	public function test_get_name() {
		$this->assertSame( 'cookiebot/toggle-gcm', ( new Toggle_Gcm_Ability( $this->logger ) )->get_name() );
	}

	public function test_enable_sets_option_to_1() {
		update_option( 'cookiebot-gcm', '0' );
		$args   = ( new Toggle_Gcm_Ability( $this->logger ) )->get_args();
		$result = call_user_func( $args['execute_callback'], array( 'enabled' => true ) );
		$this->assertTrue( $result['success'] );
		$this->assertSame( '1', get_option( 'cookiebot-gcm' ) );
	}

	public function test_disable_sets_option_to_0() {
		update_option( 'cookiebot-gcm', '1' );
		$args   = ( new Toggle_Gcm_Ability( $this->logger ) )->get_args();
		$result = call_user_func( $args['execute_callback'], array( 'enabled' => false ) );
		$this->assertTrue( $result['success'] );
		$this->assertSame( '0', get_option( 'cookiebot-gcm' ) );
	}

	public function test_old_and_new_values_are_booleans() {
		update_option( 'cookiebot-gcm', '1' );
		$args   = ( new Toggle_Gcm_Ability( $this->logger ) )->get_args();
		$result = call_user_func( $args['execute_callback'], array( 'enabled' => false ) );
		$this->assertSame( true, $result['old_value'] );
		$this->assertSame( false, $result['new_value'] );
	}

	public function test_writes_audit_log() {
		update_option( 'cookiebot-gcm', '1' );
		$args = ( new Toggle_Gcm_Ability( $this->logger ) )->get_args();
		call_user_func( $args['execute_callback'], array( 'enabled' => false ) );
		$log = get_option( 'cookiebot-ai-action-log', array() );
		$this->assertCount( 1, $log );
		$this->assertSame( 'cookiebot/toggle-gcm', $log[0]['ability'] );
	}

	public function test_idempotent_same_value_succeeds() {
		update_option( 'cookiebot-gcm', '1' );
		$args   = ( new Toggle_Gcm_Ability( $this->logger ) )->get_args();
		$result = call_user_func( $args['execute_callback'], array( 'enabled' => true ) );
		$this->assertTrue( $result['success'] );
		$this->assertSame( '1', get_option( 'cookiebot-gcm' ) );
	}
}
