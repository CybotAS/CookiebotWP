<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\wp_mautic\Wp_Mautic;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Wp_Mautic extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\wp_mautic\Wp_Mautic
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_is_plugin_compatible() {
		$content = Wp_Mautic::get_svn_file_content();

		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_head\', \'wpmautic_inject_script\' );' ) );
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_footer\', \'wpmautic_inject_script\' );' ) );
	}
}
