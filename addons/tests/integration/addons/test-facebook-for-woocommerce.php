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
}