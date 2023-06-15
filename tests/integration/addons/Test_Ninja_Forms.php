<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\ninja_forms\Ninja_Forms;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Ninja_Forms extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\ninja_forms\Ninja_Forms
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_is_plugin_compatible() {
		$content = Ninja_Forms::get_svn_file_content( 'includes/Display/Render.php' );

		$this->assertNotFalse( strpos( $content, 'wp_enqueue_script(\'nf-google-recaptcha\'' ) );
	}
}
