<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Hubspot_Tracking_Code extends Addons_Base {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hook for "hubspot_tracking_code" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_hubspot_tracking_code() {
		$content = $this->curl_get_content( 'http://plugins.svn.wordpress.org/hubspot-tracking-code/trunk/inc/class-hubspot-tracking-code-analytics.php' );
		
		$this->assertNotFalse( strpos( $content, '<script type="text/javascript" id="hs-script-loader"') );
	}
}