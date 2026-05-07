<?php
/**
 * Tests for Cookiebot_Abilities_Registrar.
 *
 * @package cookiebot
 * @subpackage tests
 */

namespace cybot\cookiebot\tests\integration\abilities;

use cybot\cookiebot\abilities\Cookiebot_Abilities_Registrar;
use WP_UnitTestCase;

/**
 * Test class for Cookiebot_Abilities_Registrar.
 *
 * @since 4.8.0
 */
class Test_Cookiebot_Abilities_Registrar extends WP_UnitTestCase {

	/**
	 * Test that register_hooks adds both actions.
	 *
	 * @return void
	 */
	public function test_register_hooks_adds_both_actions() {
		$registrar = new Cookiebot_Abilities_Registrar();
		$registrar->register_hooks();
		$this->assertNotFalse( has_action( 'wp_abilities_api_categories_init', array( $registrar, 'register_category' ) ) );
		$this->assertNotFalse( has_action( 'wp_abilities_api_init', array( $registrar, 'register' ) ) );
	}

	/**
	 * Test that all six abilities are registered after hooks fire.
	 *
	 * @return void
	 */
	public function test_all_six_abilities_are_registered_after_hooks_fire() {
		$expected = array(
			'cookiebot/get-status',
			'cookiebot/verify-setup',
			'cookiebot/get-compliance-summary',
			'cookiebot/set-cbid',
			'cookiebot/toggle-gcm',
			'cookiebot/install-ppg',
		);
		foreach ( $expected as $name ) {
			$this->assertTrue( wp_has_ability( $name ), "Expected ability '{$name}' to be registered." );
		}
	}
}
