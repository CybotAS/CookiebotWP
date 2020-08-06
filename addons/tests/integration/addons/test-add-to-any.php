<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Add_To_Any extends Addons_Base {

	/**
	 * This will cover the existince of the wp_enqueue_script addtoany
	 *
	 * @covers \cookiebot_addons\controller\addons\add_to_any\Add_To_Any
	 *
	 * @since 2.1.0
	 */
	public function test_script_loader_tag_addtoany() {
		$url     = 'http://plugins.svn.wordpress.org/add-to-any/trunk/add-to-any.php';
		$content = $this->curl_get_content( $url );

		// test the content
		$this->assertNotFalse( strpos( $content, "wp_enqueue_script( 'addtoany'" ) );
	}
}