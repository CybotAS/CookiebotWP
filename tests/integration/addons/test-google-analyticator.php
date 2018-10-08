<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Google_Analyticator extends \WP_UnitTestCase {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hooks for "google_analyticator" still exists
	 *
	 * @since 2.1.1
	 */
	public function test_hooks() {
		$content = file_get_contents( 'http://plugins.svn.wordpress.org/google-analyticator/trunk/google-analyticator.php' );
		
		$this->assertNotFalse( strpos( $content, 'add_action(\'wp_print_scripts\', \'ga_external_tracking_js\',99999);') );
		$this->assertNotFalse( strpos( $content, 'add_action(\'login_head\', \'add_google_analytics\', 99);') );
		$this->assertNotFalse( strpos( $content, 'add_action(\'wp_head\', \'add_google_analytics\',99);') );
		$this->assertNotFalse( strpos( $content, 'wp_enqueue_script(\'ga-external-tracking\', plugins_url') );
	}
}