<?php

namespace cookiebot_addons\tests\unit;

class Test_Get_Option_Name extends \WP_UnitTestCase {

	protected $plugins;

	public function setUp() {
		$this->get_plugins();
	}

	/**
	 * Load the addons through json file.
	 */
	private function get_plugins() {
		$file          = file_get_contents( COOKIEBOT_ADDONS_DIR . 'addons.json' );
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
	 * Check if get_option_name is unique in every addon.
	 */
	public function test_get_option_name_unique() {
		$options = array();

		$settingsMock = $this->createMock( 'cookiebot_addons\lib\Settings_Service_Interface' );
		$scriptLoaderTagMock = $this->createMock( 'cookiebot_addons\lib\script_loader_tag\Script_Loader_Tag_Interface' );
		$cookieConsentMock = $this->createMock( 'cookiebot_addons\lib\Cookie_Consent_Interface' );
		$bufferOutputMock = $this->createMock( 'cookiebot_addons\lib\buffer\Buffer_Output_Interface' );

		foreach ( $this->plugins as $plugin ) {
			$p = new $plugin->class( $settingsMock, $scriptLoaderTagMock, $cookieConsentMock, $bufferOutputMock );

			// test if the option_name exists in the options array
			$this->assertNotContains( $p->get_option_name(), $options );

			// add name to options array
			$options[] = $p->get_option_name();
		}
	}

}