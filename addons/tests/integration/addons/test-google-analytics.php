<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Google_Analytics extends Addons_Base {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hooks for "google_analytics" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_hooks() {
		$content = $this->curl_get_content( 'http://plugins.svn.wordpress.org/googleanalytics/trunk/class/Ga_Frontend.php' );

		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_head\', \'Ga_Frontend::insert_ga_script\' );') );
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_enqueue_scripts\', \'Ga_Frontend::platform_sharethis\' );') );
	}
}