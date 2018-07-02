<?php

namespace cookiebot_addons\tests\unit;

use cookiebot_addons\lib\buffer\Buffer_Output_Interface;
use cookiebot_addons\lib\Cookie_Consent_Interface;
use cookiebot_addons\lib\script_loader_tag\Script_Loader_Tag_Interface;
use cookiebot_addons\lib\Settings_Service_Interface;

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

		$settingsMock = $this->createMock( Settings_Service_Interface::class );
		$scriptLoaderTagMock = $this->createMock( Script_Loader_Tag_Interface::class );
		$cookieConsentMock = $this->createMock( Cookie_Consent_Interface::class );
		$bufferOutputMock = $this->createMock( Buffer_Output_Interface::class );

		foreach ( $this->plugins as $plugin ) {
			$p = new $plugin->class( $settingsMock, $scriptLoaderTagMock, $cookieConsentMock, $bufferOutputMock );

			// test if the option_name exists in the options array
			$this->assertNotContains( $p->get_option_name(), $options );

			// add name to options array
			$options[] = $p->get_option_name();
		}
	}

}