<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Facebook_For_Woocommerce extends Addons_Base {
	
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
		
		$content = file_get_contents( 'https://raw.githubusercontent.com/facebookincubator/facebook-for-woocommerce/master/facebook-commerce-pixel-event.php' );

		$this->assertNotFalse( strpos( $content, 'apply_filters( \'wc_facebook_pixel_script_attributes\',' ) );

	}
}
