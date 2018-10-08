<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Facebook_For_Woocommerce extends \WP_UnitTestCase {
	
	public function setUp() {
	
	}
	
	/**
	 * This will verify if the used values are still valid
	 *
	 * @since 2.1.0
	 */
	public function test_host_analyticsjs_local() {
		$content = file_get_contents( 'https://raw.githubusercontent.com/facebookincubator/facebook-for-woocommerce/master/facebook-commerce-events-tracker.php' );
		
		$this->assertNotFalse( strpos( $content, 'add_action(\'wp_head\',
      array($this, \'inject_base_pixel\'));
    add_action(\'wp_footer\',
      array($this, \'inject_base_pixel_noscript\'));
    add_action(\'woocommerce_after_single_product\',
      array($this, \'inject_view_content_event\'), self::FB_PRIORITY_HIGH);
    add_action(\'woocommerce_after_shop_loop\',
      array($this, \'inject_view_category_event\'));
    add_action(\'pre_get_posts\',
      array($this, \'inject_search_event\'));
    add_action(\'woocommerce_after_cart\',
      array($this, \'inject_add_to_cart_redirect_event\'));
    add_action(\'woocommerce_add_to_cart\',
      array($this, \'inject_add_to_cart_event\'), self::FB_PRIORITY_HIGH);
    add_action(\'wc_ajax_fb_inject_add_to_cart_event\',
      array($this, \'inject_ajax_add_to_cart_event\' ), self::FB_PRIORITY_HIGH);
    add_action(\'woocommerce_after_checkout_form\',
      array($this, \'inject_initiate_checkout_event\'));
    add_action(\'woocommerce_thankyou\',
      array($this, \'inject_gateway_purchase_event\'), self::FB_PRIORITY_HIGH);
    add_action(\'woocommerce_payment_complete\',
      array($this, \'inject_purchase_event\'), self::FB_PRIORITY_HIGH);
    add_action(\'wpcf7_contact_form\',
      array($this, \'inject_lead_event_hook\'), self::FB_PRIORITY_LOW);') );
		
		
	}
}