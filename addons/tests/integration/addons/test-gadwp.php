<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Gadwp extends \WP_UnitTestCase {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hooks for "google_analytics" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_hooks() {
		$content = file_get_contents( 'http://plugins.svn.wordpress.org/google-analytics-dashboard-for-wp/trunk/includes/frontend/tracking/class-tracking-analytics.php' );

		$this->assertNotFalse( strpos( $content, 'GoogleAnalyticsObject') );

		$content = file_get_contents( 'http://plugins.svn.wordpress.org/google-analytics-dashboard-for-wp/trunk/includes/frontend/frontend.php' );

		$this->assertNotFalse( strpos( $content, "add_action( 'wp_head', 'exactmetrics_tracking_script', 6 );") );

		$content = file_get_contents( 'http://plugins.svn.wordpress.org/google-analytics-dashboard-for-wp/trunk/includes/frontend/events/class-analytics-events.php' );

		$this->assertNotFalse( strpos( $content, "wp_enqueue_script( 'exactmetrics-frontend-script',") );
	}
}
