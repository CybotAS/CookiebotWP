<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Custom_Facebook_Feed extends \WP_UnitTestCase {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hook "caos_analytics_render_tracking_code" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_host_analyticsjs_local() {
		$content = file_get_contents( 'http://plugins.svn.wordpress.org/custom-facebook-feed/trunk/custom-facebook-feed.php' );
		
		$this->assertNotFalse( strpos( $content, 'echo \'var cfflinkhashtags = "\' .') );
		$this->assertNotFalse( strpos( $content, "wp_register_script( 'cffscripts',") );
		$this->assertNotFalse( strpos( $content, "add_action( 'wp_footer', 'cff_js' );") );
		$this->assertNotFalse( strpos( $content, "add_action( 'wp_enqueue_scripts', 'cff_scripts_method' );") );
	}
}