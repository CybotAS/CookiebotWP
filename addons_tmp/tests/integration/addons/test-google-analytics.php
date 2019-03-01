<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Google_Analytics extends \WP_UnitTestCase {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hooks for "google_analytics" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_hooks() {
		$content = file_get_contents( 'http://plugins.svn.wordpress.org/googleanalytics/trunk/class/Ga_Frontend.php' );
		
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_footer\', \'Ga_Frontend::insert_ga_script\' );') );
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_enqueue_scripts\', \'Ga_Frontend::platform_sharethis\' );') );
	}
}