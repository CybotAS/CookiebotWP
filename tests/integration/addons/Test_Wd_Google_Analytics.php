<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\wd_google_analytics\Wd_Google_Analytics;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Wd_Google_Analytics extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\wd_google_analytics\Wd_Google_Analytics
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_is_plugin_compatible() {
		$content = Wd_Google_Analytics::get_svn_file_content( 'gawd_class.php' );

		$this->assertNotFalse( strpos( $content, 'add_action(\'wp_head\', array($this, \'gawd_tracking_code\'), 99);' ) );
	}
}
