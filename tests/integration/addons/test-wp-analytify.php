<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Wp_Analytify extends \WP_UnitTestCase {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hooks for "wp_analytify" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_wp_analytify() {
		$content = file_get_contents( 'http://plugins.svn.wordpress.org/wp-analytify/trunk/wp-analytify.php' );
		
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_head\', array( $this, \'analytify_add_analytics_code\' ) );') );
	}
}