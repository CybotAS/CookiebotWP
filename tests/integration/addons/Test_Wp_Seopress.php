<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\wp_seopress\Wp_Seopress;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Wp_Seopress extends WP_UnitTestCase {

	/**
	 * @covers cybot\cookiebot\addons\controller\addons\wp_seopress\Wp_Seopress
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_buffer_output_tag_wp_seopress() {
		$content = Wp_Seopress::get_svn_file_content( 'inc/functions/options-google-analytics.php' );

		// test the content
		$this->assertNotFalse( strpos( $content, "add_action('wp_head', 'seopress_google_analytics_js_arguments', 929, 1);" ) );
	}
}
