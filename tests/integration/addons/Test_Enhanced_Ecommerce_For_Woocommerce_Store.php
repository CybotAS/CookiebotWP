<?php

	namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\enhanced_ecommerce_for_woocommerce_store\Enhanced_Ecommerce_For_WooCommerce_Store;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Enhanced_Ecommerce_For_WooCommerce_Store extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\enhanced_ecommerce_for_woocommerce_store\Enhanced_Ecommerce_For_WooCommerce_Store
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_is_plugin_compatible() {
		$content = Enhanced_Ecommerce_For_WooCommerce_Store::get_svn_file_content( 'public/class-enhanced-ecommerce-google-analytics-public.php' );

		$this->assertNotFalse( strpos( $content, 'wc_enqueue_js(' ) );
	}
}
