<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Facebook_For_Woocommerce extends \WP_UnitTestCase {
	
	public function setUp() {
	
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
		
		$content = file_get_contents( 'https://raw.githubusercontent.com/facebookincubator/facebook-for-woocommerce/master/facebook-commerce-events-tracker.php' );
		
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_head\', array( $this, \'apply_filters\' ) );' ) );
		$this->assertNotFalse( strpos( $content, 'add_action( \'woocommerce_after_single_product\', [ $this, \'inject_view_content_event\' ] );' ) );
		$this->assertNotFalse( strpos( $content, 'array( $this, \'inject_view_category_event\' )' ) );
		$this->assertNotFalse( strpos( $content, 'array( $this, \'inject_search_event\' )' ) );
		$this->assertNotFalse( strpos( $content, 'add_action( \'woocommerce_add_to_cart\',             [ $this, \'inject_add_to_cart_event\' ], 40, 4 );' ) );
		$this->assertNotFalse( strpos( $content, 'add_action( \'woocommerce_ajax_added_to_cart\', [ $this, \'add_filter_for_add_to_cart_fragments\' ] );' ) );
		$this->assertNotFalse( strpos( $content, 'array( $this, \'inject_initiate_checkout_event\' )' ) );
		$this->assertNotFalse( strpos( $content, 'array( $this, \'inject_gateway_purchase_event\' ),' ) );
		$this->assertNotFalse( strpos( $content, 'array( $this, \'inject_purchase_event\' ),' ) );
		$this->assertNotFalse( strpos( $content, 'const FB_PRIORITY_LOW     = 11;' ) );
		$this->assertNotFalse( strpos( $content, 'const FB_PRIORITY_HIGH    = 2;' ) );
	}
}
