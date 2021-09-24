<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\pixel_caffeine\Pixel_Caffeine;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;
use function cybot\cookiebot\tests\remote_get_svn_contents;

class Test_Pixel_Caffeine extends WP_UnitTestCase {

	public function setUp() {
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\pixel_caffeine\Pixel_Caffeine
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 */
	public function test_is_plugin_compatible() {
		$content = remote_get_svn_contents( Pixel_Caffeine::get_svn_url( 'includes/class-aepc-pixel-scripts.php' ) );

		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_head\', array( __CLASS__, \'pixel_init\' ), 99 );' ) );
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_footer\', array( __CLASS__, \'pixel_init\' ), 1 );' ) );
		$this->assertNotFalse( strpos( $content, 'wp_register_script( \'aepc-pixel-events\',' ) );
	}
}
