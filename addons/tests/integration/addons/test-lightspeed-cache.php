<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Lightspeed_Cache extends Addons_Base {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hooks for "lightspeed cache" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_hooks() {
		$content = $this->curl_get_content( 'http://plugins.svn.wordpress.org/litespeed-cache/trunk/src/optimize.cls.php' );
		
		$this->assertNotFalse( strpos( $content, 'apply_filters( \'litespeed_optimize_js_excludes\'') );
	}
}