<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Wd_Google_Analytics extends \WP_UnitTestCase {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hooks for "wd_google_analytics" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_wd_google_analytics() {
		$content = file_get_contents( 'http://plugins.svn.wordpress.org/wd-google-analytics/trunk/gawd_class.php' );
		
		$this->assertNotFalse( strpos( $content, 'add_action(\'wp_head\', array($this, \'gawd_tracking_code\'), 99);') );
	}
}