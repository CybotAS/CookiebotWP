<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\google_analytics\Google_Analytics;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;
use function cybot\cookiebot\tests\remote_get_svn_contents;

class Test_Google_Analytics extends WP_UnitTestCase {

	public function setUp() {
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\google_analytics\Google_Analytics
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 */
	public function test_is_plugin_compatible() {
		$content = remote_get_svn_contents( Google_Analytics::get_svn_url( 'class/Ga_Frontend.php' ) );

		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_head\', \'Ga_Frontend::insert_ga_script\' );' ) );
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_enqueue_scripts\', \'Ga_Frontend::platform_sharethis\' );' ) );
	}
}
