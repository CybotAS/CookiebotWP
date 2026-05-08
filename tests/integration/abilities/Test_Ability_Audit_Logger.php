<?php
// tests/integration/abilities/Test_Ability_Audit_Logger.php

namespace cybot\cookiebot\tests\integration\abilities;

use cybot\cookiebot\abilities\Ability_Audit_Logger;
use WP_UnitTestCase;

class Test_Ability_Audit_Logger extends WP_UnitTestCase {

	public function set_up() {
		parent::set_up();
		delete_option( 'cookiebot-ai-action-log' );
	}

	public function test_log_creates_entry_with_correct_structure() {
		$logger = new Ability_Audit_Logger();
		$logger->log( 'cookiebot/set-cbid', 'old', 'new' );
		$log = get_option( 'cookiebot-ai-action-log', array() );
		$this->assertCount( 1, $log );
		$this->assertSame( 'cookiebot/set-cbid', $log[0]['ability'] );
		$this->assertSame( 'old', $log[0]['old'] );
		$this->assertSame( 'new', $log[0]['new'] );
		$this->assertArrayHasKey( 'ts', $log[0] );
		$this->assertArrayHasKey( 'user_id', $log[0] );
	}

	public function test_log_prepends_newest_first() {
		$logger = new Ability_Audit_Logger();
		$logger->log( 'cookiebot/set-cbid', 'a', 'b' );
		$logger->log( 'cookiebot/toggle-gcm', 'c', 'd' );
		$log = get_option( 'cookiebot-ai-action-log', array() );
		$this->assertSame( 'cookiebot/toggle-gcm', $log[0]['ability'] );
		$this->assertSame( 'cookiebot/set-cbid', $log[1]['ability'] );
	}

	public function test_log_caps_at_50_entries() {
		$logger = new Ability_Audit_Logger();
		for ( $i = 0; $i < 55; $i++ ) {
			$logger->log( 'cookiebot/set-cbid', 'old', 'new' );
		}
		$log = get_option( 'cookiebot-ai-action-log', array() );
		$this->assertCount( 50, $log );
	}

	public function test_log_recovers_from_corrupted_option() {
		update_option( 'cookiebot-ai-action-log', 'not-an-array' );
		$logger = new Ability_Audit_Logger();
		$logger->log( 'cookiebot/set-cbid', 'old', 'new' );
		$log = get_option( 'cookiebot-ai-action-log', array() );
		$this->assertCount( 1, $log );
		$this->assertSame( 'cookiebot/set-cbid', $log[0]['ability'] );
	}
}
