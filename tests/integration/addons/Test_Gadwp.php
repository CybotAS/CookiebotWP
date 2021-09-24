<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\gadwp\Gadwp;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;
use function cybot\cookiebot\tests\remote_get_svn_contents;

class Test_Gadwp extends WP_UnitTestCase {

	public function setUp() {
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\gadwp\Gadwp
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 */
	public function test_is_plugin_compatible() {
		$content = remote_get_svn_contents( Gadwp::get_svn_url( 'includes/frontend/tracking/class-tracking-analytics.php' ) );
		$this->assertNotFalse( strpos( $content, 'GoogleAnalyticsObject' ) );

		$content = remote_get_svn_contents( Gadwp::get_svn_url( 'includes/frontend/frontend.php' ) );
		$this->assertNotFalse( strpos( $content, "add_action( 'wp_head', 'exactmetrics_tracking_script', 6 );" ) );

		$content = remote_get_svn_contents( Gadwp::get_svn_url( 'includes/frontend/events/class-analytics-events.php' ) );
		$this->assertNotFalse( strpos( $content, "wp_enqueue_script( 'exactmetrics-frontend-script'," ) );
	}
}
