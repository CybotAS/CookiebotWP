<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\custom_facebook_feed\Custom_Facebook_Feed_Version_2_17_1;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Custom_Facebook_Feed_Version_2_17_1 extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\custom_facebook_feed\Custom_Facebook_Feed_Version_2_17_1
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_is_plugin_compatible() {
		$content = Custom_Facebook_Feed_Version_2_17_1::get_svn_file_content();

		$this->assertNotFalse( strpos( $content, 'echo \'var cfflinkhashtags = "\' .' ) );
		$this->assertNotFalse( strpos( $content, "wp_register_script( 'cffscripts'," ) );
		$this->assertNotFalse( strpos( $content, "add_action( 'wp_footer', 'cff_js' );" ) );
		$this->assertNotFalse( strpos( $content, "add_action( 'wp_enqueue_scripts', 'cff_scripts_method' );" ) );
	}
}
