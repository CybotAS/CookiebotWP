<?php

	namespace cookiebot_addons\tests\integration\addons;

	class Test_Enhanced_Ecommerce_For_WooCommerce_Store extends Addons_Base {

		/**
		 * This will cover the existince of the wc_enqueue_js
		 *
		 * @covers \cookiebot_addons\controller\addons\enhanced_ecommerce_for_woocommerce_store\Enhanced_Ecommerce_For_WooCommerce_Store
		 *
		 * @since 3.6.6
		 */
		public function test_script_loader_tag_addtoany() {
			$content = $this->curl_get_content( 'https://plugins.svn.wordpress.org/enhanced-e-commerce-for-woocommerce-store/trunk/public/class-enhanced-ecommerce-google-analytics-public.php' );

			$this->assertNotFalse( strpos( $content, "wc_enqueue_js(" ) );
		}
	}