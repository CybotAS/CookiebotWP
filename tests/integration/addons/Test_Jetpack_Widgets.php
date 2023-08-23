<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\jetpack\Jetpack;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Jetpack_Widgets extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\jetpack\widget\Facebook_Jetpack_Widget
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_facebook_jetpack_widget() {
		$content = Jetpack::get_svn_file_content( 'class.jetpack.php' );

		$this->assertNotFalse(
			strpos(
				$content,
				'wp_register_script(
				\'jetpack-facebook-embed\''
			)
		);
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\jetpack\widget\Goodreads_Jetpack_Widget
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_goodreads_jetpack_widget() {
		$content = Jetpack::get_svn_file_content( 'modules/widgets/goodreads.php' );

		$this->assertNotFalse(
			strpos(
				$content,
				'parent::__construct(
			\'wpcom-goodreads\''
			)
		);
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\jetpack\widget\Google_Maps_Jetpack_Widget
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_google_maps_jetpack_widget() {
		$content = Jetpack::get_svn_file_content( 'modules/widgets/contact-info.php' );

		$this->assertNotFalse( strpos( $content, 'do_action( \'jetpack_contact_info_widget_start\' );' ) );
		$this->assertNotFalse( strpos( $content, 'do_action( \'jetpack_contact_info_widget_end\' );' ) );
		$this->assertNotFalse( strpos( $content, 'do_action( \'jetpack_stats_extra\', \'widget_view\', \'contact_info\' );' ) );
	}

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\jetpack\widget\Twitter_Timeline_Jetpack_Widget
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_twitter_timeline_jetpack_widget() {
		$content = Jetpack::get_svn_file_content( 'modules/widgets/twitter-timeline.php' );

		$this->assertNotFalse( strpos( $content, 'wp_enqueue_script( \'jetpack-twitter-timeline\' );' ) );
	}
}
