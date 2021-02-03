<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Google_Site_Kit extends Addons_Base {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hook script with handle "google_gtagjs" still exists
	 *
	 */
	public function test_google_site_kit() {
		$content = $this->curl_get_content( 'http://plugins.svn.wordpress.org/google-site-kit/trunk/includes/Modules/Analytics/Web_Tag.php' );

		$this->assertNotFalse( strpos( $content, "wp_script_add_data( 'google_gtagjs', 'script_execution', 'async' );") );

	}
}
