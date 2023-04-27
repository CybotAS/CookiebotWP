<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\google_analytics\Google_Analytics;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Google_Analytics extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\google_analytics\Google_Analytics
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_is_plugin_compatible() {
		$content = Google_Analytics::get_svn_file_content( 'class/class-ga-frontend.php' );

		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_head\', \'Ga_Frontend::insert_ga_script\' );' ) );
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_enqueue_scripts\', \'Ga_Frontend::platform_sharethis\' );' ) );
	}
}
