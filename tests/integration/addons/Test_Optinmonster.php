<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\optinmonster\Optinmonster;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;
use function cybot\cookiebot\tests\remote_get_svn_contents;

class Test_Optinmonster extends WP_UnitTestCase {

	public function setUp() {
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\optinmonster\Optinmonster
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 */
	public function test_is_plugin_compatible() {
		$content = remote_get_svn_contents( Optinmonster::get_svn_url( 'OMAPI/Output.php' ) );

		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_enqueue_scripts\', array( $this, \'api_script\' ) );' ) );
	}
}
