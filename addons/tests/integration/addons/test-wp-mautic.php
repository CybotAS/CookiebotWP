<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Wp_Mautic extends Addons_Base {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hooks for "wpmautic" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_wp_mautic() {
		$content = $this->curl_get_content( 'https://plugins.svn.wordpress.org/wp-mautic/trunk/wpmautic.php' );
		
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_head\', \'wpmautic_inject_script\' );') );
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_footer\', \'wpmautic_inject_script\' );') );
	}
}