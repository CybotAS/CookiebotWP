<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Wp_Seopress extends Addons_Base {

	/**
	 * @covers \cookiebot_addons\controller\addons\wp_seopress\Wp_Seopress
	 *
	 * @since 2.1.0
	 */
	public function test_buffer_output_tag_wp_seopress() {
		$url     = 'https://plugins.svn.wordpress.org/wp-seopress/trunk/inc/functions/options-google-analytics.php';
		$content = $this->curl_get_content( $url );

		// test the content
		$this->assertNotFalse( strpos( $content, "add_action('wp_head', 'seopress_google_analytics_js_arguments', 999, 1);" ) );
	}
}
