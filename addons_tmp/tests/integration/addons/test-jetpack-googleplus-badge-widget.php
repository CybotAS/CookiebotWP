<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Jetpack_Googleplus_Badge_Widget extends \WP_UnitTestCase {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hook "jetpack googleplus badge widget" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_googleplus_badge_widget() {
		$content = file_get_contents('http://plugins.svn.wordpress.org/jetpack/tags/6.9/modules/widgets/googleplus-badge.php');
		
		$this->assertNotFalse( strpos( $content, 'wp_enqueue_script( \'googleplus-widget\',' ) );
	}
}