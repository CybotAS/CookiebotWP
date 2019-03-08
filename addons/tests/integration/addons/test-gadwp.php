<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Gadwp extends \WP_UnitTestCase {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hooks for "google_analytics" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_hooks() {
		$content = file_get_contents( 'http://plugins.svn.wordpress.org/google-analytics-dashboard-for-wp/trunk/front/views/tagmanager-code.php' );

		$this->assertNotFalse( strpos( $content, 'https://www.googletagmanager.com') );

		$content = file_get_contents( 'http://plugins.svn.wordpress.org/google-analytics-dashboard-for-wp/trunk/front/views/analytics-optout-code.php' );

		$this->assertNotFalse( strpos( $content, 'ga-disable-') );

		$content = file_get_contents( 'http://plugins.svn.wordpress.org/google-analytics-dashboard-for-wp/trunk/front/views/analytics-code.php' );

		$this->assertNotFalse( strpos( $content, 'tracking_script_path') );
	}
}