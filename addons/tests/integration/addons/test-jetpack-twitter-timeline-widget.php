<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Jetpack_Twitter_Timeline_Widget extends Addons_Base {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hook "jetpack twitter timeline widget" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_twitter_timeline_widget() {
		$content = $this->curl_get_content('http://plugins.svn.wordpress.org/jetpack/trunk/modules/widgets/twitter-timeline.php');
		
		$this->assertNotFalse( strpos( $content, 'wp_enqueue_script( \'jetpack-twitter-timeline\' );' ) );
	}
}