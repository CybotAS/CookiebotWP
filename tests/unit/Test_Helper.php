<?php

namespace cybot\cookiebot\tests\unit;

use function cybot\cookiebot\lib\cookiebot_assist;

class Test_Helper extends \WP_UnitTestCase {

	public function test_cookiebot_assist_with_an_array_parameter() {
		$output = cookiebot_assist( array( 'statistics' ) );

		$this->assertEquals( ' type="text/plain" data-cookieconsent="statistics"', $output );
	}

	public function test_cookiebot_assist_with_a_string_parameter() {
		$output = cookiebot_assist( 'statistics' );

		$this->assertEquals( ' type="text/plain" data-cookieconsent="statistics"', $output );
	}

	public function test_cookiebot_assist_with_no_parameter() {
		$output = cookiebot_assist();

		$this->assertEquals( ' type="text/plain" data-cookieconsent="statistics"', $output );
	}

	public function test_cookiebot_assist_with_empty_parameter() {
		$output = cookiebot_assist( '' );

		$this->assertEmpty( $output );
	}
}
