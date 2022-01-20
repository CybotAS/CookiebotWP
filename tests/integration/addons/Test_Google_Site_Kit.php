<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\google_site_kit\Google_Site_Kit;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Google_Site_Kit extends WP_UnitTestCase {

	public function setUp() {
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\google_site_kit\Google_Site_Kit
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_is_plugin_compatible() {
		$content = Google_Site_Kit::get_svn_file_content( 'includes/Modules/Analytics/Web_Tag.php' );

		$this->assertNotFalse( strpos( $content, "wp_script_add_data( 'google_gtagjs', 'script_execution', 'async' );" ) );
	}
}
