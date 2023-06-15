<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\wp_piwik\Wp_Piwik;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Wp_Piwik extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\wp_piwik\Wp_Piwik
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_is_plugin_compatible() {
		$content = Wp_Piwik::get_svn_file_content( 'classes/WP_Piwik.php' );

		$this->assertNotFalse( strpos( $content, '\'disableCookies\' => self::$settings->getGlobalOption ( \'disable_cookies\' ) ? 1 : 0' ) );
	}
}
