<?php

	namespace cookiebot_addons\tests\integration\addons;

	class Test_Hotjar extends \WP_UnitTestCase {

		/**
		 * This will cover the existince of the wc_enqueue_js
		 *
		 * @covers \cookiebot_addons\controller\addons\enhanced_ecommerce_for_woocommerce_store\Enhanced_Ecommerce_For_WooCommerce_Store
		 *
		 * @since 3.6.6
		 */
		public function test_script_loader_tag_addtoany() {
			$content = file_get_contents( 'https://plugins.svn.wordpress.org/hotjar/trunk/includes/class-hotjar.php' );

			$this->assertNotFalse( strpos( $content, "static.hotjar.com" ) );
		}
	}