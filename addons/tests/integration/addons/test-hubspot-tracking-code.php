<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Hubspot_Tracking_Code extends \WP_UnitTestCase {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hook for "hubspot_tracking_code" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_hubspot_tracking_code() {
		$content = file_get_contents( 'http://plugins.svn.wordpress.org/hubspot-tracking-code/trunk/inc/class-hubspot-tracking-code-analytics.php' );
		
		$this->assertNotFalse( strpos( $content, '<script type="text/javascript" id="hs-script-loader"') );
	}
}