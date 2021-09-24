<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\wd_google_analytics\Wd_Google_Analytics;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;
use function cybot\cookiebot\tests\remote_get_svn_contents;

class Test_Wd_Google_Analytics extends WP_UnitTestCase {

	public function setUp() {
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\wd_google_analytics\Wd_Google_Analytics
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 */
	public function test_is_plugin_compatible() {
		$content = remote_get_svn_contents( Wd_Google_Analytics::get_svn_url( 'gawd_class.php' ) );

		$this->assertNotFalse( strpos( $content, 'add_action(\'wp_head\', array($this, \'gawd_tracking_code\'), 99);' ) );
	}
}
