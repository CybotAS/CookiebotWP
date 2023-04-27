<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\custom_facebook_feed\Custom_Facebook_Feed;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Custom_Facebook_Feed extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\custom_facebook_feed\Custom_Facebook_Feed
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_is_plugin_compatible() {
		$content = Custom_Facebook_Feed::get_svn_file_content( 'inc/Custom_Facebook_Feed.php' );

		$this->assertNotFalse( strpos( $content, 'echo \'var cfflinkhashtags = "\' . $cff_link_hashtags . \'";\';' ) );
		$this->assertNotFalse( strpos( $content, 'wp_enqueue_script( \'cffscripts\' );' ) );
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_footer\', [ self::$instance, \'cff_js\' ] );' ) );
	}
}
