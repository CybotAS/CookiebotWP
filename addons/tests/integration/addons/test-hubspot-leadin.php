<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Hubspot_Leadin extends Addons_Base {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hook for "hubspot_leadin" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_hook() {
		$content = $this->curl_get_content( 'http://plugins.svn.wordpress.org/leadin/trunk/src/class-assetsmanager.php' );
		
		$this->assertNotFalse( strpos( $content, "const TRACKING_CODE = 'leadin-script-loader-js';") );
	}
}
