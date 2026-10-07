<?php

namespace cybot\cookiebot\tests\unit;

use cybot\cookiebot\settings\pages\Iab_Page;
use ReflectionMethod;

class Test_Iab_Page extends \WP_UnitTestCase {

	/**
	 * PHP 8.4 deprecates calling str_getcsv() without an explicit $escape argument.
	 * The extra providers CSV has ~600 lines, so a regression floods debug.log on every IAB page load.
	 *
	 * @covers \cybot\cookiebot\settings\pages\Iab_Page::get_extra_providers
	 */
	public function test_get_extra_providers_parses_csv_without_deprecations() {
		$method = new ReflectionMethod( Iab_Page::class, 'get_extra_providers' );
		if ( PHP_VERSION_ID < 80100 ) {
			$method->setAccessible( true );
		}

		$deprecations = array();
		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_set_error_handler -- Collect deprecations raised by the call under test.
		set_error_handler(
			function ( $errno, $errstr, $errfile, $errline ) use ( &$deprecations ) {
				$deprecations[] = $errstr . ' in ' . $errfile . ':' . $errline;
				return true;
			},
			E_DEPRECATED | E_USER_DEPRECATED
		);
		try {
			$providers = $method->invoke( new Iab_Page() );
		} finally {
			restore_error_handler();
		}

		$this->assertSame( array(), $deprecations );
		$this->assertNotEmpty( $providers );
		$this->assertTrue( is_int( $providers[0]['id'] ) && $providers[0]['id'] > 0 );
		$this->assertNotEmpty( $providers[0]['name'] );
	}
}
