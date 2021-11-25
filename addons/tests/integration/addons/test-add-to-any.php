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
		$this->assertNotFalse( strpos( $content, "add_action( 'wp_enqueue_scripts', 'A2A_SHARE_SAVE_stylesheet'" ) );
		$this->assertNotFalse( strpos( $content, "\$script_handle = 'addtoany-core';" ) );
		$this->assertNotFalse( strpos( $content, "wp_enqueue_script( \$script_handle );" ) );
		$this->assertNotFalse( strpos( $content, "wp_enqueue_script( 'addtoany-jquery'" ) );
	}
}
