<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Jetpack_Goodreads_Widget extends Addons_Base {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hook "jetpack goodreads widget" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_goodreads_widget() {
		$content = $this->curl_get_content( 'http://plugins.svn.wordpress.org/jetpack/trunk/modules/widgets/goodreads.php' );
		
		$this->assertNotFalse( strpos( $content, 'parent::__construct(
			\'wpcom-goodreads\'') );
	}
}