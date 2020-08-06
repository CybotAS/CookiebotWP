<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Wp_Piwik extends Addons_Base {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hook "wp_piwik" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_host_analyticsjs_local() {
		$content = $this->curl_get_content( 'http://plugins.svn.wordpress.org/wp-piwik/trunk/classes/WP_Piwik.php' );
		
		$this->assertNotFalse( strpos( $content, '\'disableCookies\' => self::$settings->getGlobalOption ( \'disable_cookies\' ) ? 1 : 0') );
	}
}