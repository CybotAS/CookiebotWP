<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Jetpack_Facebook_Widget extends Addons_Base {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hook "jetpack facebook widget" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_facebook_widget() {
		$content = $this->curl_get_content( 'http://plugins.svn.wordpress.org/jetpack/trunk/class.jetpack.php' );
		
		$this->assertNotFalse( strpos( $content, 'wp_register_script(
				\'jetpack-facebook-embed\'') );
	}
}