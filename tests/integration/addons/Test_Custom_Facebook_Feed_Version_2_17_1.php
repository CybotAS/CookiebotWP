<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\custom_facebook_feed\Custom_Facebook_Feed_Version_2_17_1;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;
use function cybot\cookiebot\tests\remote_get_svn_contents;

class Test_Custom_Facebook_Feed_Version_2_17_1 extends WP_UnitTestCase {

	public function setUp() {
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\custom_facebook_feed\Custom_Facebook_Feed_Version_2_17_1
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 */
	public function test_is_plugin_compatible() {
		$content = remote_get_svn_contents( Custom_Facebook_Feed_Version_2_17_1::get_svn_url() );

		$this->assertNotFalse( strpos( $content, 'echo \'var cfflinkhashtags = "\' .' ) );
		$this->assertNotFalse( strpos( $content, "wp_register_script( 'cffscripts'," ) );
		$this->assertNotFalse( strpos( $content, "add_action( 'wp_footer', 'cff_js' );" ) );
		$this->assertNotFalse( strpos( $content, "add_action( 'wp_enqueue_scripts', 'cff_scripts_method' );" ) );
	}
}
