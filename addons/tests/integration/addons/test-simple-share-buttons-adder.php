<?php

	namespace cookiebot_addons\tests\integration\addons;

	class Test_Simple_Share_Buttons_Adder extends Addons_Base {

		/**
		 * This will cover the existince of the wp_enqueue_script ssba-sharethis
		 *
		 * @covers \cookiebot_addons\controller\addons\simple_share_buttons_adder\Simple_Share_Buttons_Adder
		 *
		 * @since 3.6.5
		 */
		public function test_script_loader_tag_addtoany() {
			$content = $this->curl_get_content( 'http://plugins.svn.wordpress.org/simple-share-buttons-adder/trunk/php/class-styles.php' );

			$this->assertNotFalse( strpos( $content, "wp_enqueue_script('ssba-sharethis'" ) );
		}
	}