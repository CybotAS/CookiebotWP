<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\add_to_any\Add_To_Any;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Add_To_Any extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\add_to_any\Add_To_Any
	 * @throws ExpectationFailedException|InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_is_plugin_compatible() {
		$content = Add_To_Any::get_svn_file_content();

		// test the content
		$this->assertNotFalse( strpos( $content, "add_action( 'wp_enqueue_scripts', 'A2A_SHARE_SAVE_stylesheet'" ) );
		$this->assertNotFalse( strpos( $content, "\$script_handle = 'addtoany-core';" ) );
		$this->assertNotFalse( strpos( $content, 'wp_enqueue_script( $script_handle );' ) );
		$this->assertNotFalse( strpos( $content, "wp_enqueue_script( 'addtoany-jquery'" ) );
	}
}
