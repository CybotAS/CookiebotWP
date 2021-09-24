<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\addthis\Addthis;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;
use function cybot\cookiebot\tests\remote_get_svn_contents;

class Test_Addthis extends WP_UnitTestCase {

	public function setUp() {
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\addthis\Addthis
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 */
	public function test_is_plugin_compatible() {
		$content = remote_get_svn_contents( Addthis::get_svn_url( 'backend/AddThisPlugin.php' ) );

		$this->assertNotFalse( strpos( $content, "'addthis_widget'," ) );
		$this->assertNotFalse( strpos( $content, 'window.addthis_product ' ) );
		$this->assertNotFalse( strpos( $content, 'add_action(\'wp_enqueue_scripts\', array($this, \'enqueueScripts\'));' ) );
	}
}
