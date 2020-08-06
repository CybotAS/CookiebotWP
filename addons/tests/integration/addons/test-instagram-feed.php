<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Instagram_Feed extends Addons_Base {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hook "instagram_feed" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_instagram_feed() {
		$content = $this->curl_get_content( 'http://plugins.svn.wordpress.org/instagram-feed/trunk/inc/if-functions.php' );
		
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_enqueue_scripts\', \'sb_instagram_scripts_enqueue\', 2 );') );
	}
}
