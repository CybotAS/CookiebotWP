<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Addthis extends \WP_UnitTestCase {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hook "caos_analytics_render_tracking_code" still exists
	 *
	 */
	public function test_addthis() {
		$content = file_get_contents( 'https://plugins.svn.wordpress.org/addthis/trunk/backend/AddThisPlugin.php' );
		
		$this->assertNotFalse( strpos( $content, "'addthis_widget',") );
		$this->assertNotFalse( strpos( $content, "window.addthis_product ") );
		$this->assertNotFalse( strpos( $content, 'add_action(\'wp_enqueue_scripts\', array($this, \'enqueueScripts\'));') );

	}
}
