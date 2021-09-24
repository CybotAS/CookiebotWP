<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\add_to_any\Add_To_Any;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;
use function cybot\cookiebot\tests\remote_get_svn_contents;

class Test_Add_To_Any extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\add_to_any\Add_To_Any
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 */
	public function test_is_plugin_compatible() {
		$content = remote_get_svn_contents( Add_To_Any::get_svn_url() );

		// test the content
		$this->assertNotFalse( strpos( $content, "wp_enqueue_script( 'addtoany'" ) );
	}
}
