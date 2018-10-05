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

		$this->assertNotFalse( strpos( $content,
			'add_action(\'wp_footer\', \'caos_analytics_render_tracking_code\', $sgal_enqueue_order);' ) );
		$this->assertNotFalse( strpos( $content,
			'add_action(\'wp_head\', \'caos_analytics_render_tracking_code\', $sgal_enqueue_order);' ) );
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
	 * @covers \cookiebot_addons\controller\addons\google_analyticator\Google_Analyticator
	 */
	public function test_google_analyticator() {
		$content = file_get_contents( 'http://plugins.svn.wordpress.org/google-analyticator/trunk/google-analyticator.php' );

		$this->assertNotFalse( strpos( $content, 'add_action(\'login_head\', \'add_google_analytics\', 99);' ) );
		$this->assertNotFalse( strpos( $content,
			'add_action(\'wp_print_scripts\', \'ga_external_tracking_js\',99999);' ) );
	}

	/**
	 * @covers \cookiebot_addons\controller\addons\facebook_for_woocommerce\Facebook_For_Woocommerce
	 */
	public function test_facebook_for_woocommerce_main_file() {
		$content = file_get_contents( 'https://raw.githubusercontent.com/facebookincubator/facebook-for-woocommerce/master/facebook-commerce.php' );

		$this->assertNotFalse( strpos( $content, 'WC_Facebookcommerce' ) );
	}

	/**
	 * @covers \cookiebot_addons\controller\addons\facebook_for_woocommerce\Facebook_For_Woocommerce
	 */
	public function test_facebook_for_woocommerce_hooks() {
		$content = file_get_contents( 'https://raw.githubusercontent.com/facebookincubator/facebook-for-woocommerce/233b6fcf2296f8936d8ea259931b9ef14eacc4bd/facebook-commerce-events-tracker.php' );

		$this->assertNotFalse( strpos( $content, 'add_action(\'wp_head\', array($this, \'apply_filters\'));' ) );
		$this->assertNotFalse( strpos( $content, 'add_action(\'woocommerce_after_single_product' ) );
		$this->assertNotFalse( strpos( $content, 'add_action(\'woocommerce_after_shop_loop' ) );
		$this->assertNotFalse( strpos( $content, 'add_action(\'pre_get_posts' ) );
		$this->assertNotFalse( strpos( $content, 'add_action(\'woocommerce_after_cart' ) );
		$this->assertNotFalse( strpos( $content, 'add_action(\'woocommerce_add_to_cart' ) );
		$this->assertNotFalse( strpos( $content, 'add_action(\'wc_ajax_fb_inject_add_to_cart_event' ) );
		$this->assertNotFalse( strpos( $content, 'add_action(\'woocommerce_after_checkout_form' ) );
		$this->assertNotFalse( strpos( $content, 'add_action(\'woocommerce_thankyou' ) );
		$this->assertNotFalse( strpos( $content, 'add_action(\'woocommerce_payment_complete' ) );
	}

	/**
	 * @covers \cookiebot_addons\controller\addons\hubspot_tracking_code\Hubspot_Tracking_Code
	 */
	public function test_hubspot_tracking_code() {
		$content = file_get_contents('http://plugins.svn.wordpress.org/hubspot-tracking-code/trunk/inc/class-hubspot-tracking-code-analytics.php');

		$this->assertNotFalse( strpos( $content, 'add_action(\'wp_footer\', array($this, \'hubspot_analytics_insert\'));' ) );
		$this->assertNotFalse( strpos( $content, '<script type="text/javascript" id="hs-script-loader"' ) );
	}

	/**
	 * @covers cookiebot_addons\controller\addons\jetpack\widget\Google_Maps_Widget
	 */
	public function test_google_maps_widget() {
		$content = file_get_contents('http://plugins.svn.wordpress.org/jetpack/trunk/modules/widgets/contact-info.php');

		$this->assertNotFalse( strpos( $content, 'do_action( \'jetpack_contact_info_widget_start\' );' ) );
		$this->assertNotFalse( strpos( $content, 'do_action( \'jetpack_contact_info_widget_end\' );' ) );
		$this->assertNotFalse( strpos( $content, 'do_action( \'jetpack_stats_extra\', \'widget_view\', \'contact_info\' );' ) );
	}
}
