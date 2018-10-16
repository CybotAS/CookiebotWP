<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Add_To_Any extends \WP_UnitTestCase {
	
	/**
	 * This will cover the existince of the wp_enqueue_script addtoany
	 *
	 * @covers \cookiebot_addons\controller\addons\add_to_any\Add_To_Any
	 *
	 * @since 2.1.0
	 */
	public function test_script_loader_tag_addtoany() {
		$content = file_get_contents( 'http://plugins.svn.wordpress.org/add-to-any/trunk/add-to-any.php' );
		
		$this->assertNotFalse( strpos( $content, "wp_enqueue_script( 'addtoany'" ) );
	}
}