<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\gadwp\Gadwp;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Gadwp extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\gadwp\Gadwp
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_is_plugin_compatible() {
		$content = Gadwp::get_svn_file_content( 'includes/frontend/frontend.php' );
		$this->assertNotFalse( strpos( $content, "add_action( 'wp_head', 'exactmetrics_tracking_script', 6 );" ) );
	}
}
