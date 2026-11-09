<?php
/**
 * Tests for Consent_API_Helper.
 *
 * @package cookiebot
 * @subpackage tests
 */

namespace cybot\cookiebot\tests\integration\lib;

use cybot\cookiebot\lib\Consent_API_Helper;
use WP_UnitTestCase;

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound -- The hook is owned by WP Consent API and built from the plugin basename.

/**
 * CMS-3316: WP Consent API Site Health registration.
 */
class Test_Consent_API_Helper extends WP_UnitTestCase {

	/**
	 * Basename must point at the main plugin file.
	 */
	public function test_plugin_basename_points_to_main_file() {
		$this->assertSame( 'cookiebot.php', basename( Consent_API_Helper::get_plugin_basename() ) );
	}

	/**
	 * Filter is already registered after plugin bootstrap (before Site Health runs).
	 */
	public function test_registered_at_plugin_load() {
		$hook = 'wp_consent_api_registered_' . Consent_API_Helper::get_plugin_basename();
		$this->assertTrue( apply_filters( $hook, false ) );
	}

	/**
	 * register_hooks() declares compliance even when WP Consent API is not active.
	 */
	public function test_register_hooks_declares_compliance_without_consent_api() {
		$helper = new Consent_API_Helper();
		$hook   = 'wp_consent_api_registered_' . Consent_API_Helper::get_plugin_basename();
		remove_all_filters( $hook );

		$this->assertFalse( $helper->is_wp_consent_api_active() );
		$this->assertFalse( apply_filters( $hook, false ) );

		$helper->register_hooks();

		$this->assertTrue( apply_filters( $hook, false ) );
	}
}
