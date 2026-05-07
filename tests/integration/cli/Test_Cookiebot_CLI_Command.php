<?php
/**
 * Tests for Cookiebot_CLI_Command.
 *
 * @package cookiebot
 * @subpackage tests
 */

namespace cybot\cookiebot\tests\integration\cli;

use cybot\cookiebot\cli\Cookiebot_CLI_Command;
use cybot\cookiebot\cli\Output_Adapter;
use WP_UnitTestCase;

/**
 * Fake output adapter that records all output calls for assertions.
 */
class Fake_Output implements Output_Adapter {
	public $successes = array();
	public $errors    = array();
	public $items     = array();

	public function success( $message ) {
		$this->successes[] = $message;
	}

	public function error( $message ) {
		$this->errors[] = $message;
	}

	public function format_items( $items, $fields, $format ) {
		$this->items[] = array(
			'items'  => $items,
			'fields' => $fields,
			'format' => $format,
		);
	}
}

/**
 * Integration tests for Cookiebot_CLI_Command.
 *
 * @since 4.8.0
 */
class Test_Cookiebot_CLI_Command extends WP_UnitTestCase {

	/**
	 * Set up test fixtures.
	 *
	 * @return void
	 */
	public function set_up() {
		parent::set_up();
		// Run as an administrator so that manage_options passes in execute().
		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $user_id );
		update_option( 'cookiebot-cbid', 'aabbccdd-1111-2222-3333-aabbccddeeff' );
		update_option( 'cookiebot-gcm', '1' );
		update_option( 'cookiebot-cookie-blocking-mode', 'auto' );
		update_option( 'cookiebot-banner-enabled', '1' );
	}

	public function test_status_calls_get_status_ability_and_outputs_one_row() {
		$output  = new Fake_Output();
		$command = new Cookiebot_CLI_Command( $output );

		$command->status( array(), array( 'format' => 'json' ) );

		$this->assertCount( 1, $output->items );
		$this->assertCount( 1, $output->items[0]['items'] );
		$row = $output->items[0]['items'][0];
		$this->assertArrayHasKey( 'cbid_set', $row );
		$this->assertArrayHasKey( 'gcm_enabled', $row );
		$this->assertArrayHasKey( 'plugin_version', $row );
		$this->assertEmpty( $output->errors );
	}

	public function test_set_cbid_with_invalid_uuid_routes_to_error() {
		$output  = new Fake_Output();
		$command = new Cookiebot_CLI_Command( $output );

		$command->set_cbid( array( 'not-a-uuid' ), array() );

		$this->assertCount( 1, $output->errors );
		$this->assertEmpty( $output->successes );
	}

	public function test_set_cbid_with_valid_uuid_calls_success() {
		$output  = new Fake_Output();
		$command = new Cookiebot_CLI_Command( $output );

		$command->set_cbid( array( '12345678-1234-1234-1234-123456789abc' ), array() );

		$this->assertCount( 1, $output->successes );
		// The success message must NOT contain the CBID value (security: CBID is sensitive).
		$this->assertStringNotContainsString( '12345678', $output->successes[0] );
		$this->assertStringContainsString( 'CBID updated', $output->successes[0] );
		$this->assertEmpty( $output->errors );
	}

	public function test_toggle_gcm_without_flag_routes_to_error() {
		$output  = new Fake_Output();
		$command = new Cookiebot_CLI_Command( $output );

		$command->toggle_gcm( array(), array() );

		$this->assertCount( 1, $output->errors );
	}
}
