<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Custom_Facebook_Feed extends Addons_Base {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hook "caos_analytics_render_tracking_code" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_host_analyticsjs_local() {
		$content = $this->curl_get_content( 'http://plugins.svn.wordpress.org/custom-facebook-feed/trunk/inc/Custom_Facebook_Feed.php' );

		$this->assertNotFalse( strpos( $content, 'echo \'var cfflinkhashtags = "\' . $cff_link_hashtags . \'";\';') );
		$this->assertNotFalse( strpos( $content, 'wp_enqueue_script( \'cffscripts\' );') );
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_footer\', [ self::$instance, \'cff_js\' ] );') );
	}
}