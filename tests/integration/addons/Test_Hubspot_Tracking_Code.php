<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\hubspot_tracking_code\Hubspot_Tracking_Code;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Hubspot_Tracking_Code extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\hubspot_tracking_code\Hubspot_Tracking_Code
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_is_plugin_compatible() {
		$content = Hubspot_Tracking_Code::get_svn_file_content( 'inc/class-hubspot-tracking-code-analytics.php' );

		$this->assertNotFalse( strpos( $content, '<script type="text/javascript" id="hs-script-loader"' ) );
	}
}
