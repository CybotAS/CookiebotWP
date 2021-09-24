<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\instagram_feed\Instagram_Feed;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;
use function cybot\cookiebot\tests\remote_get_svn_contents;

class Test_Instagram_Feed extends WP_UnitTestCase {

	public function setUp() {
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\instagram_feed\Instagram_Feed
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 */
	public function test_is_plugin_compatible() {
		$content = remote_get_svn_contents( Instagram_Feed::get_svn_url( 'inc/if-functions.php' ) );

		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_enqueue_scripts\', \'sb_instagram_scripts_enqueue\', 2 );' ) );
	}
}
