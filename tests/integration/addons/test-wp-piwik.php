<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Wp_Piwik extends \WP_UnitTestCase {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hook "wp_piwik" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_host_analyticsjs_local() {
		$content = file_get_contents( 'http://plugins.svn.wordpress.org/wp-piwik/trunk/classes/WP_Piwik.php' );
		
		$this->assertNotFalse( strpos( $content, '\'disableCookies\' => self::$settings->getGlobalOption ( \'disable_cookies\' ) ? 1 : 0') );
	}
}