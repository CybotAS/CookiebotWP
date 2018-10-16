<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Optinmonster extends \WP_UnitTestCase {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hook "optinmonster" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_optinmonster() {
		$content = file_get_contents('https://plugins.svn.wordpress.org/optinmonster/trunk/optin-monster-wp-api.php');
		
		$this->assertNotFalse( strpos( $content, 'wp_enqueue_script( \'optinmonster-api-script\' );' ) );
	}
}