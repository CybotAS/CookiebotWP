<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\wp_piwik\Wp_Piwik;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;
use function cybot\cookiebot\tests\remote_get_svn_contents;

class Test_Wp_Piwik extends WP_UnitTestCase {

	public function setUp() {
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\wp_piwik\Wp_Piwik
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 */
	public function test_is_plugin_compatible() {
		$content = remote_get_svn_contents( Wp_Piwik::get_svn_url( 'classes/WP_Piwik.php' ) );

		$this->assertNotFalse( strpos( $content, '\'disableCookies\' => self::$settings->getGlobalOption ( \'disable_cookies\' ) ? 1 : 0' ) );
	}
}