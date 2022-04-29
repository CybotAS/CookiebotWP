<?php

	namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\simple_share_buttons_adder\Simple_Share_Buttons_Adder;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Simple_Share_Buttons_Adder extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\simple_share_buttons_adder\Simple_Share_Buttons_Adder
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_is_plugin_compatible() {
		$content = Simple_Share_Buttons_Adder::get_svn_file_content( 'php/class-styles.php' );

		$this->assertNotFalse( strpos( $content, "wp_enqueue_script( 'ssba-sharethis'" ) );
	}
}
