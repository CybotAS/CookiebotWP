<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Jetpack_Facebook_Widget extends \WP_UnitTestCase {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hook "jetpack facebook widget" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_facebook_widget() {
		$content = file_get_contents( 'http://plugins.svn.wordpress.org/jetpack/trunk/class.jetpack.php' );
		
		$this->assertNotFalse( strpos( $content, 'wp_register_script(
				\'jetpack-facebook-embed\'') );
	}
}