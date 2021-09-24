<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\litespeed_cache\Litespeed_Cache;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;
use function cybot\cookiebot\tests\remote_get_svn_contents;

class Test_Lightspeed_Cache extends WP_UnitTestCase {

	public function setUp() {
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\litespeed_cache\Litespeed_Cache
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 */
	public function test_is_plugin_compatible() {
		$content = remote_get_svn_contents( Litespeed_Cache::get_svn_url( 'src/optimize.cls.php' ) );

		$this->assertNotFalse( strpos( $content, 'apply_filters( \'litespeed_optimize_js_excludes\'' ) );
	}
}
