<?php

namespace cookiebot_addons\tests\unit;

class Test_Get_Option_Name extends \WP_UnitTestCase {

	protected $plugins;

	private $container;

	public function setUp() {
		Parent::setUp();

		$this->get_plugins();
	}

	/**
	 * Load the addons through json file.
	 */
	private function get_plugins() {
		$file          = file_get_contents( CAF_DIR . 'addons.json' );
		$this->plugins = json_decode( $file );
	}

	/**
	 * Validate if the plugins in addons.json do exist as a class in addons controller directory.
	 */
	public function test_plugins_are_valid() {
		foreach ( $this->plugins as $plugin ) {
			$check = is_object( $plugin ) && class_exists( $plugin->class );
			$this->assertTrue( $check );
		}
	}

	/**
	 * get_option_name is unique in every addon.
	 */
	public function test_get_option_name_unique() {
		$options = array();

		foreach ( $this->plugins as $plugin ) {
			$p = new $plugin->class(null, null, null, null);

			$this->assertNotContains( $p->get_option_name(), $options );

			$options[] = $p;
		}
	}

}