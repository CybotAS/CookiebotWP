<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Wp_Mautic extends \WP_UnitTestCase {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hooks for "wpmautic" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_wp_mautic() {
		$content = file_get_contents( 'https://plugins.svn.wordpress.org/wp-mautic/trunk/wpmautic.php' );
		
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_head\', \'wpmautic_inject_script\' );') );
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_footer\', \'wpmautic_inject_script\' );') );
	}
}