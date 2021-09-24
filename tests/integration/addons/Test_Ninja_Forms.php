<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\ninja_forms\Ninja_Forms;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;
use function cybot\cookiebot\tests\remote_get_svn_contents;

class Test_Ninja_Forms extends WP_UnitTestCase {

	public function setUp() {
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\ninja_forms\Ninja_Forms
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 */
	public function test_is_plugin_compatible() {
		$content = remote_get_svn_contents( Ninja_Forms::get_svn_url( 'includes/Display/Render.php' ) );

		$this->assertNotFalse( strpos( $content, 'wp_enqueue_script(\'nf-google-recaptcha\'' ) );
	}
}
