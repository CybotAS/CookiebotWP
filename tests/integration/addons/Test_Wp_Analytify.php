<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\wp_analytify\Wp_Analytify;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Wp_Analytify extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\wp_analytify\Wp_Analytify
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_is_plugin_compatible() {
		$content = Wp_Analytify::get_svn_file_content();

		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_head\', array( $this, \'analytify_add_analytics_code\' ) );' ) );
	}
}
