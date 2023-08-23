<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\litespeed_cache\Litespeed_Cache;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Lightspeed_Cache extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\litespeed_cache\Litespeed_Cache
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_is_plugin_compatible() {
		$content = Litespeed_Cache::get_svn_file_content( 'src/optimize.cls.php' );

		$this->assertNotFalse( strpos( $content, 'apply_filters(\'litespeed_optimize_js_excludes\'' ) );
	}
}
