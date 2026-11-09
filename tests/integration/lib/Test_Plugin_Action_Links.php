<?php
/**
 * Tests for the plugin action links hooks.
 *
 * @package cookiebot
 * @subpackage tests
 */

namespace cybot\cookiebot\tests\integration\lib;

use cybot\cookiebot\lib\Cookiebot_Admin_Links;
use cybot\cookiebot\lib\Cookiebot_Review;
use WP_UnitTestCase;
use function cybot\cookiebot\lib\get_plugin_basename;

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound -- The hook is owned by WordPress and built from the plugin basename.

/**
 * CMS-3316: action links must follow the real plugin basename, not a hardcoded folder name.
 */
class Test_Plugin_Action_Links extends WP_UnitTestCase {

	/**
	 * Settings action link is added for the real basename.
	 */
	public function test_admin_links_use_plugin_basename() {
		$hook = 'plugin_action_links_' . get_plugin_basename();
		remove_all_filters( $hook );

		$links = new Cookiebot_Admin_Links();
		$links->register_hooks();

		$this->assertSame( 10, has_filter( $hook, array( $links, 'set_settings_action_link' ) ) );
	}

	/**
	 * Deactivate survey link is added for the real basename.
	 */
	public function test_review_uses_plugin_basename() {
		$hook = 'plugin_action_links_' . get_plugin_basename();
		remove_all_filters( $hook );

		$review = new Cookiebot_Review();
		$review->register_hooks();

		$this->assertSame( 10, has_filter( $hook, array( $review, 'plugin_action_links' ) ) );
	}
}
