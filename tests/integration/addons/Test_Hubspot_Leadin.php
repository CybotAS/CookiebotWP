<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\hubspot_leadin\Hubspot_Leadin;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Hubspot_Leadin extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\hubspot_leadin\Hubspot_Leadin
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_is_plugin_compatible() {
		$content = Hubspot_Leadin::get_svn_file_content( 'public/class-assetsmanager.php' );

		$this->assertNotFalse( strpos( $content, "const TRACKING_CODE      = 'leadin-script-loader-js';" ) );
	}
}
