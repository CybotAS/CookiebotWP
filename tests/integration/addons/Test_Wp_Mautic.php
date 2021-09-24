<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\wp_mautic\Wp_Mautic;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;
use function cybot\cookiebot\tests\remote_get_svn_contents;

class Test_Wp_Mautic extends WP_UnitTestCase {

	public function setUp() {
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\wp_mautic\Wp_Mautic
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 */
	public function test_is_plugin_compatible() {
		$content = remote_get_svn_contents( Wp_Mautic::get_svn_url() );

		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_head\', \'wpmautic_inject_script\' );' ) );
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_footer\', \'wpmautic_inject_script\' );' ) );
	}
}
