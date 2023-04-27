<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\optinmonster\Optinmonster;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Optinmonster extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\optinmonster\Optinmonster
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_is_plugin_compatible() {
		$content = Optinmonster::get_svn_file_content( 'OMAPI/Output.php' );

		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_enqueue_scripts\', array( $this, \'api_script\' ) );' ) );
	}
}
