<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Wp_Analytify extends Addons_Base {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hooks for "wp_analytify" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_wp_analytify() {
		$content = $this->curl_get_content( 'http://plugins.svn.wordpress.org/wp-analytify/trunk/wp-analytify.php' );
		
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_head\', array( $this, \'analytify_add_analytics_code\' ) );') );
	}
}