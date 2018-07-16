<?php

namespace cookiebot_addons\tests\integration;

class Test_Buffer_Priorities extends \WP_UnitTestCase {
	
	public function setUp() {
	
	}
	
	/**
	 * @covers \cookiebot_addons\controller\addons\custom_facebook_feed\Custom_Facebook_Feed
	 */
	public function test_custom_facebook_feed() {
		$content = file_get_contents( 'http://plugins.svn.wordpress.org/custom-facebook-feed/trunk/custom-facebook-feed.php' );
		
		$this->assertNotFalse( strpos( $content, "add_action( 'wp_footer', 'cff_js' );" ) );
	}
	
	/**
	 * @covers \cookiebot_addons\controller\addons\caos_host_analyticsjs_local\CAOS_Host_Analyticsjs_Local
	 */
	public function test_host_analyticsjs_local() {
		$content = file_get_contents( 'http://plugins.svn.wordpress.org/host-analyticsjs-local/trunk/save-ga-local.php' );
		
		$this->assertNotFalse( strpos( $content, 'add_action(\'wp_footer\', \'add_ga_header_script\', $sgal_enqueue_order);' ) );
		$this->assertNotFalse( strpos( $content, 'add_action(\'wp_head\', \'add_ga_header_script\', $sgal_enqueue_order);' ) );
	}
	
	/**
	 * @covers \cookiebot_addons\controller\addons\ga_google_analytics\Ga_Google_Analytics
	 */
	public function test_ga_google_analytics() {
		$content = file_get_contents( 'http://plugins.svn.wordpress.org/ga-google-analytics/trunk/inc/plugin-core.php' );
		
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