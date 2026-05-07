<?php
/**
 * Test_Set_Cbid_Ability class.
 *
 * @package cookiebot
 * @subpackage tests
 */

namespace cybot\cookiebot\tests\integration\abilities;

use cybot\cookiebot\abilities\Ability_Audit_Logger;
use cybot\cookiebot\abilities\Set_Cbid_Ability;
use WP_UnitTestCase;

/**
 * Tests for Set_Cbid_Ability.
 *
 * @since 4.8.0
 */
class Test_Set_Cbid_Ability extends WP_UnitTestCase {

	/**
	 * @var Ability_Audit_Logger
	 */
	private $logger;

	public function set_up() {
		parent::set_up();
		$this->logger = new Ability_Audit_Logger();
		delete_option( 'cookiebot-ai-action-log' );
		update_option( 'cookiebot-cbid', '11111111-1111-1111-1111-111111111111' );
	}

	public function test_get_name() {
		$this->assertSame( 'cookiebot/set-cbid', ( new Set_Cbid_Ability( $this->logger ) )->get_name() );
	}

	public function test_updates_option_on_valid_cbid() {
		$args   = ( new Set_Cbid_Ability( $this->logger ) )->get_args();
		$result = call_user_func( $args['execute_callback'], array( 'cbid' => 'AABBCCDD-1111-2222-3333-AABBCCDDEEFF' ) );
		$this->assertTrue( $result['success'] );
		$this->assertSame( 'AABBCCDD-1111-2222-3333-AABBCCDDEEFF', get_option( 'cookiebot-cbid' ) );
	}

	public function test_returns_cbid_set_flags_not_raw_values() {
		$args   = ( new Set_Cbid_Ability( $this->logger ) )->get_args();
		$result = call_user_func( $args['execute_callback'], array( 'cbid' => 'AABBCCDD-1111-2222-3333-AABBCCDDEEFF' ) );
		// Response must use boolean flags, never the raw CBID value (security: CBID is sensitive).
		$this->assertTrue( $result['old_cbid_set'] );
		$this->assertTrue( $result['new_cbid_set'] );
		$this->assertArrayNotHasKey( 'old_cbid', $result );
		$this->assertArrayNotHasKey( 'new_cbid', $result );
	}

	public function test_old_cbid_set_false_when_previously_unset() {
		update_option( 'cookiebot-cbid', '' );
		$args   = ( new Set_Cbid_Ability( $this->logger ) )->get_args();
		$result = call_user_func( $args['execute_callback'], array( 'cbid' => 'AABBCCDD-1111-2222-3333-AABBCCDDEEFF' ) );
		$this->assertFalse( $result['old_cbid_set'] );
		$this->assertTrue( $result['new_cbid_set'] );
	}

	public function test_writes_audit_log_on_success() {
		$args = ( new Set_Cbid_Ability( $this->logger ) )->get_args();
		call_user_func( $args['execute_callback'], array( 'cbid' => 'AABBCCDD-1111-2222-3333-AABBCCDDEEFF' ) );
		$log = get_option( 'cookiebot-ai-action-log', array() );
		$this->assertCount( 1, $log );
		$this->assertSame( 'cookiebot/set-cbid', $log[0]['ability'] );
		// Audit log must NOT store raw CBID values.
		$this->assertNotContains( 'AABBCCDD-1111-2222-3333-AABBCCDDEEFF', $log[0] );
		$this->assertNotContains( '11111111-1111-1111-1111-111111111111', $log[0] );
	}

	public function test_returns_wp_error_on_invalid_uuid() {
		$args   = ( new Set_Cbid_Ability( $this->logger ) )->get_args();
		$result = call_user_func( $args['execute_callback'], array( 'cbid' => 'not-a-uuid' ) );
		$this->assertInstanceOf( 'WP_Error', $result );
		$this->assertSame( 'cookiebot_invalid_cbid', $result->get_error_code() );
	}

	public function test_returns_wp_error_on_empty_cbid() {
		$args   = ( new Set_Cbid_Ability( $this->logger ) )->get_args();
		$result = call_user_func( $args['execute_callback'], array( 'cbid' => '' ) );
		$this->assertInstanceOf( 'WP_Error', $result );
		$this->assertSame( 'cookiebot_invalid_cbid', $result->get_error_code() );
	}

	public function test_no_log_written_on_error() {
		$args = ( new Set_Cbid_Ability( $this->logger ) )->get_args();
		call_user_func( $args['execute_callback'], array( 'cbid' => 'bad' ) );
		$this->assertEmpty( get_option( 'cookiebot-ai-action-log', array() ) );
	}
}
