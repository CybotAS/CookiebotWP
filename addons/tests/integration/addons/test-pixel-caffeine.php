<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Pixel_Caffeine extends Addons_Base {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hooks "pixel_caffeine" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_pixel_caffeine() {
		$content = $this->curl_get_content( 'http://plugins.svn.wordpress.org/pixel-caffeine/trunk/includes/class-aepc-pixel-scripts.php' );
		
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_head\', array( __CLASS__, \'pixel_init\' ), 99 );') );
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_footer\', array( __CLASS__, \'pixel_init\' ), 1 );') );
		$this->assertNotFalse( strpos( $content, 'wp_register_script( \'aepc-pixel-events\',') );
	}
}