<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Gadwp extends \WP_UnitTestCase {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hooks for "google_analytics" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_hooks() {
		$content = file_get_contents( 'http://plugins.svn.wordpress.org/google-analytics-dashboard-for-wp/trunk/front/tracking-tagmanager.php' );
		
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_footer\', array( $this, \'output\' ), 99 );') );
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_head\', array( $this, \'output\' ), 99 );') );
		$this->assertNotFalse( strpos( $content, 'add_action( \'amp_post_template_footer\', array( $this, \'amp_output\' ) );') );

		$content = file_get_contents( 'http://plugins.svn.wordpress.org/google-analytics-dashboard-for-wp/trunk/front/tracking-analytics.php' );

		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_footer\', array( $this, \'output\' ), 99 );') );
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_head\', array( $this, \'output\' ), 99 );') );
		$this->assertNotFalse( strpos( $content, 'add_action( \'amp_post_template_footer\', array( $this, \'output\' ) );') );
	}
}