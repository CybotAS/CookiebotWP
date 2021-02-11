<?php

namespace cookiebot_addons\tests\integration;

use cookiebot_addons\tests\integration\addons\Addons_Base;

class Test_Buffer_Priorities extends Addons_Base {
	
	public function setUp() {
	
	}
	
	/**
	 * @covers \cookiebot_addons\controller\addons\caos_host_analyticsjs_local\CAOS_Host_Analyticsjs_Local
	 */
	public function test_host_analyticsjs_local() {
		$content = $this->curl_get_content( 'http://plugins.svn.wordpress.org/host-analyticsjs-local/trunk/includes/frontend/class-tracking.php' );

		$this->assertNotFalse( strpos( $content,
			'add_filter(\'woocommerce_google_analytics_script_src\'' ) );

		$this->assertNotFalse( strpos( $content,
			'\'render_tracking_code\']' ) );
	}
	
	/**
	 * @covers \cookiebot_addons\controller\addons\ga_google_analytics\Ga_Google_Analytics
	 */
	public function test_ga_google_analytics() {
		$content = $this->curl_get_content( 'http://plugins.svn.wordpress.org/ga-google-analytics/trunk/inc/plugin-core.php' );

		$this->assertNotFalse( strpos( $content, 'add_action(\'wp_head\', $function);' ) );
		$this->assertNotFalse( strpos( $content, 'add_action(\'wp_footer\', $function);' ) );
	}
	
	/**
	 * @covers \cookiebot_addons|controller\addons\google_analyticator\Google_Analyticator
	 */
//	public function test_google_analyticator() {
//		$content = file_get_contents( 'http://plugins.svn.wordpress.org/ga-google-analytics/trunk/inc/plugin-core.php' );
//
//		$this->assertNotFalse( strpos( $content, 'add_action(\'wp_head\', \'add_google_analytics\',99);' ) );
//		$this->assertNotFalse( strpos( $content, 'add_action(\'login_head\', \'add_google_analytics\', 99);' ) );
//	}
}