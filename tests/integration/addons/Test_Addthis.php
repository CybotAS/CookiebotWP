<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\addthis\Addthis;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Addthis extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\addthis\Addthis
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_is_plugin_compatible() {
		$content = Addthis::get_svn_file_content( 'backend/AddThisPlugin.php' );

		$this->assertNotFalse( strpos( $content, "'addthis_widget'," ) );
		$this->assertNotFalse( strpos( $content, 'window.addthis_product ' ) );
		$this->assertNotFalse( strpos( $content, 'add_action(\'wp_enqueue_scripts\', array($this, \'enqueueScripts\'));' ) );
	}
}
