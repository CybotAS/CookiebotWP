<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Jetpack_Google_Maps_Widget extends Addons_Base {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hook "jetpack google maps widget" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_google_maps_widget() {
		$content = $this->curl_get_content('http://plugins.svn.wordpress.org/jetpack/trunk/modules/widgets/contact-info.php');
		
		$this->assertNotFalse( strpos( $content, 'do_action( \'jetpack_contact_info_widget_start\' );' ) );
		$this->assertNotFalse( strpos( $content, 'do_action( \'jetpack_contact_info_widget_end\' );' ) );
		$this->assertNotFalse( strpos( $content, 'do_action( \'jetpack_stats_extra\', \'widget_view\', \'contact_info\' );' ) );
	}
}