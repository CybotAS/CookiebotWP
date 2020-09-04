<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Jetpack_Googleplus_Badge_Widget extends Addons_Base {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hook "jetpack googleplus badge widget" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_googleplus_badge_widget() {
		$content = $this->curl_get_content('http://plugins.svn.wordpress.org/jetpack/tags/6.9/modules/widgets/googleplus-badge.php');
		
		$this->assertNotFalse( strpos( $content, 'wp_enqueue_script( \'googleplus-widget\',' ) );
	}
}