<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Jetpack_Goodreads_Widget extends \WP_UnitTestCase {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hook "jetpack goodreads widget" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_goodreads_widget() {
		$content = file_get_contents( 'http://plugins.svn.wordpress.org/jetpack/trunk/modules/widgets/goodreads.php' );
		
		$this->assertNotFalse( strpos( $content, 'parent::__construct(
			\'wpcom-goodreads\'') );
	}
}