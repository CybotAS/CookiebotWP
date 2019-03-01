<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Ninja_Forms extends \WP_UnitTestCase {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hook "ninja_forms" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_host_analyticsjs_local() {
		$content = file_get_contents( 'http://plugins.svn.wordpress.org/ninja-forms/trunk/includes/Display/Render.php' );
		
		$this->assertNotFalse( strpos( $content, 'wp_enqueue_script(\'nf-google-recaptcha\'') );
	}
}