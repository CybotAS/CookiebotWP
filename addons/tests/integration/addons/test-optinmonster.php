<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Optinmonster extends Addons_Base {

	public function setUp() {
	
	}

	/**
	 * This will validate if the hook "api-scripts" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_optinmonster() {
		$content = $this->curl_get_content( 'http://plugins.svn.wordpress.org/optinmonster/trunk/OMAPI/Output.php' );

		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_enqueue_scripts\', array( $this, \'api_script\' ) );') );
	}	
}
