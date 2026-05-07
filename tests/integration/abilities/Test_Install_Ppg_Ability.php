<?php
// tests/integration/abilities/Test_Install_Ppg_Ability.php

namespace cybot\cookiebot\tests\integration\abilities;

use cybot\cookiebot\abilities\Ability_Audit_Logger;
use cybot\cookiebot\abilities\Install_Ppg_Ability;
use WP_UnitTestCase;

class Test_Install_Ppg_Ability extends WP_UnitTestCase {

	/**
	 * @var Ability_Audit_Logger
	 */
	private $logger;

	public function set_up() {
		parent::set_up();
		$this->logger = new Ability_Audit_Logger();
		delete_option( 'cookiebot-ai-action-log' );
	}

	public function tear_down() {
		remove_all_filters( 'pre_option_active_plugins' );
		remove_all_filters( 'plugins_api' );
		remove_all_filters( 'all_plugins' );
		parent::tear_down();
	}

	public function test_get_name() {
		$this->assertSame( 'cookiebot/install-ppg', ( new Install_Ppg_Ability( $this->logger ) )->get_name() );
	}

	public function test_returns_was_already_active_when_plugin_active() {
		add_filter( 'pre_option_active_plugins', function() {
			return array( 'privacy-policy-usercentrics/privacy-policy-usercentrics.php' );
		} );
		$args   = ( new Install_Ppg_Ability( $this->logger ) )->get_args();
		$result = call_user_func( $args['execute_callback'] );
		$this->assertTrue( $result['was_already_active'] );
		$this->assertTrue( $result['success'] );
		$this->assertArrayHasKey( 'admin_url', $result );
	}

	public function test_writes_audit_log_when_already_active() {
		add_filter( 'pre_option_active_plugins', function() {
			return array( 'privacy-policy-usercentrics/privacy-policy-usercentrics.php' );
		} );
		$args = ( new Install_Ppg_Ability( $this->logger ) )->get_args();
		call_user_func( $args['execute_callback'] );
		$log = get_option( 'cookiebot-ai-action-log', array() );
		$this->assertCount( 1, $log );
		$this->assertSame( 'cookiebot/install-ppg', $log[0]['ability'] );
	}

	public function test_returns_wp_error_when_plugins_api_fails() {
		// Force plugins cache to empty so get_plugins() skips the filesystem scan
		// and returns [] without finding privacy-policy-usercentrics.
		wp_cache_set( 'plugins', array( '' => array() ), 'plugins' );
		// Ensure plugin is not active.
		add_filter( 'pre_option_active_plugins', function() {
			return array();
		} );
		add_filter( 'plugins_api', function() {
			return new \WP_Error( 'api_error', 'Connection failed.' );
		} );
		$args   = ( new Install_Ppg_Ability( $this->logger ) )->get_args();
		$result = call_user_func( $args['execute_callback'] );
		$this->assertInstanceOf( 'WP_Error', $result );
		$this->assertSame( 'cookiebot_ppg_install_failed', $result->get_error_code() );
	}

	public function test_show_in_rest_true() {
		$args = ( new Install_Ppg_Ability( $this->logger ) )->get_args();
		$this->assertTrue( $args['meta']['show_in_rest'] );
	}
}
