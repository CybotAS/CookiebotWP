<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Ninja_Forms extends Addons_Base {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hook "ninja_forms" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_host_analyticsjs_local() {
		$content = $this->curl_get_content( 'http://plugins.svn.wordpress.org/ninja-forms/trunk/includes/Display/Render.php' );
		
		$this->assertNotFalse( strpos( $content, 'wp_enqueue_script(\'nf-google-recaptcha\'') );
	}
}