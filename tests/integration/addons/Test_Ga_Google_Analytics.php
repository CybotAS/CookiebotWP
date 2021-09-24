<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\ga_google_analytics\Ga_Google_Analytics;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;
use function cybot\cookiebot\tests\remote_get_svn_contents;

class Test_Ga_Google_Analytics extends WP_UnitTestCase {

	public function setUp() {
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\ga_google_analytics\Ga_Google_Analytics
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 */
	public function test_is_plugin_compatible() {
		$content = remote_get_svn_contents( Ga_Google_Analytics::get_svn_url( 'inc/plugin-core.php' ) );

		$this->assertNotFalse( strpos( $content, 'ga_google_analytics_tracking_code' ) );
		$this->assertNotFalse( strpos( $content, 'add_action(\'wp_head\', $function);' ) );
		$this->assertNotFalse( strpos( $content, 'add_action(\'wp_footer\', $function);' ) );
	}
}
