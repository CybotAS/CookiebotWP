<?php

namespace cybot\cookiebot\tests\unit;

use cybot\cookiebot\lib\script_loader_tag\Script_Loader_Tag;
use WP_UnitTestCase;

class Test_Script_Loader_Tag extends WP_UnitTestCase {


	const EXAMPLE_SRC = 'path-to-file';

	public function test_script_loader_tag_with_ignore_script() {
		$script_loader_tag = new Script_Loader_Tag();
		$script_loader_tag->ignore_script( static::EXAMPLE_SRC );

        //phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
		$html = '<script src="' . static::EXAMPLE_SRC . '" id="test"></script>';

		$return = $script_loader_tag->cookiebot_add_consent_attribute_to_tag( $html, 'test', static::EXAMPLE_SRC );

        //phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
		$expected_result = '<script data-cookieconsent="ignore" src="' . static::EXAMPLE_SRC . '" id="test"></script>';

		$this->assertEquals( $return, $expected_result );
	}

	public function test_script_loader_tag_without_ignore_script() {
		$script_loader_tag = new Script_Loader_Tag();

        //phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
		$html = '<script src="' . static::EXAMPLE_SRC . '" id="test"></script>';

		$return = $script_loader_tag->cookiebot_add_consent_attribute_to_tag( $html, 'test', static::EXAMPLE_SRC );

        //phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
		$expected_result = '<script src="' . static::EXAMPLE_SRC . '" id="test"></script>';

		$this->assertEquals( $return, $expected_result );
	}

	public function test_script_loader_tag_with_adding_cookieconsent_and_single_cookie_type() {
		$script_loader_tag = new Script_Loader_Tag();
		$script_loader_tag->add_tag( 'test', array( 'statistics' ) );

        //phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
		$html = '<script src="' . static::EXAMPLE_SRC . '" id="test"></script>';

		$return = $script_loader_tag->cookiebot_add_consent_attribute_to_tag( $html, 'test', static::EXAMPLE_SRC );

        //phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
		$expected_result = '<script src="' . static::EXAMPLE_SRC . '" type="text/plain" data-cookieconsent="statistics"></script>';

		$this->assertEquals( $return, $expected_result );
	}

	public function test_script_loader_tag_with_adding_cookieconsent_and_multiple_cookie_types() {
		$script_loader_tag = new Script_Loader_Tag();
		$script_loader_tag->add_tag( 'test', array( 'statistics', 'marketing' ) );

        //phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
		$html = '<script src="' . static::EXAMPLE_SRC . '" id="test"></script>';

		$return = $script_loader_tag->cookiebot_add_consent_attribute_to_tag( $html, 'test', static::EXAMPLE_SRC );

        //phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
		$expected_result = '<script src="' . static::EXAMPLE_SRC . '" type="text/plain" data-cookieconsent="statistics,marketing"></script>';

		$this->assertEquals( $return, $expected_result );
	}

	public function test_script_loader_tag_with_adding_cookieconsent_and_no_cookie_types() {
		$script_loader_tag = new Script_Loader_Tag();
		$script_loader_tag->add_tag( 'test', array() );

        //phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
		$html = '<script src="' . static::EXAMPLE_SRC . '" id="test"></script>';

		$return = $script_loader_tag->cookiebot_add_consent_attribute_to_tag( $html, 'test', static::EXAMPLE_SRC );

        //phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
		$expected_result = '<script src="' . static::EXAMPLE_SRC . '" id="test"></script>';

		$this->assertEquals( $return, $expected_result );
	}
}
