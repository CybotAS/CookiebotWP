<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\google_analyticator\Google_Analyticator;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;
use function cybot\cookiebot\tests\remote_get_svn_contents;

class Test_Google_Analyticator extends WP_UnitTestCase {

	public function setUp() {
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\google_analyticator\Google_Analyticator
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 */
	public function test_is_plugin_compatible() {
		$content = remote_get_svn_contents( Google_Analyticator::get_svn_url() );

		$this->assertNotFalse( strpos( $content, 'add_action(\'wp_print_scripts\', \'ga_external_tracking_js\',99999);' ) );
		$this->assertNotFalse( strpos( $content, 'add_action(\'login_head\', \'add_google_analytics\', 99);' ) );
		$this->assertNotFalse( strpos( $content, 'add_action(\'wp_head\', \'add_google_analytics\',99);' ) );
		$this->assertNotFalse( strpos( $content, 'wp_enqueue_script(\'ga-external-tracking\', plugins_url' ) );
	}
}
