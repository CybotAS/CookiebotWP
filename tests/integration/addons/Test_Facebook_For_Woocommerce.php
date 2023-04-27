<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\facebook_for_woocommerce\Facebook_For_Woocommerce;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Facebook_For_Woocommerce extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\facebook_for_woocommerce\Facebook_For_Woocommerce
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_facebook_for_woocommerce_main_file() {
		$content = Facebook_For_Woocommerce::get_svn_file_content();

		$this->assertNotFalse( strpos( $content, 'WC_Facebookcommerce' ) );
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\facebook_for_woocommerce\Facebook_For_Woocommerce
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_facebook_for_woocommerce_hooks() {
		$content = Facebook_For_Woocommerce::get_svn_file_content( 'facebook-commerce-pixel-event.php' );

		$this->assertNotFalse( strpos( $content, 'apply_filters( \'wc_facebook_pixel_script_attributes\',' ) );
	}
}
