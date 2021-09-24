<?php

	namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\simple_share_buttons_adder\Simple_Share_Buttons_Adder;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;
use function cybot\cookiebot\tests\remote_get_svn_contents;

class Test_Simple_Share_Buttons_Adder extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\simple_share_buttons_adder\Simple_Share_Buttons_Adder
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 */
	public function test_is_plugin_compatible() {
		$content = remote_get_svn_contents( Simple_Share_Buttons_Adder::get_svn_url( 'php/class-styles.php' ) );

		$this->assertNotFalse( strpos( $content, "wp_enqueue_script('ssba-sharethis'" ) );
	}
}
