<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\ga_google_analytics\Ga_Google_Analytics;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Ga_Google_Analytics extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\ga_google_analytics\Ga_Google_Analytics
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_is_plugin_compatible() {
		$content = Ga_Google_Analytics::get_svn_file_content( 'inc/plugin-core.php' );

		$this->assertNotFalse( strpos( $content, 'ga_google_analytics_tracking_code' ) );
		$this->assertNotFalse( strpos( $content, 'add_action(\'wp_head\', $function);' ) );
		$this->assertNotFalse( strpos( $content, 'add_action(\'wp_footer\', $function);' ) );
	}
}
