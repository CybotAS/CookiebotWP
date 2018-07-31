<?php

namespace cookiebot_addons\tests\unit;

class Test_Get_Option_Name extends \WP_UnitTestCase {

	/**
	 * The file path of addons json
	 *
	 * @var string
	 */
	protected $file_path;

	/**
	 * The list of all addons, json encoded
	 *
	 * @var string
	 */
	protected $file;

	public function setUp() {
		$this->get_plugins();
	}
	
	/**
	 * Load the addons through json file.
	 */
	private function get_plugins() {
		$this->file_path = dirname( dirname( __DIR__ ) ) . '/addons.json';
		$this->file     = file_get_contents( $this->file_path );
		$this->plugins  = json_decode( $this->file );
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
	 * @covers \cookiebot_addons\lib\Settings_Service_Interface
	 * @covers \cookiebot_addons\lib\script_loader_tag\Script_Loader_Tag_Interface
	 * @covers \cookiebot_addons\lib\script_loader_tag\Script_Loader_Tag_Interface
	 * @covers \cookiebot_addons\lib\buffer\Buffer_Output_Interface
	 */
	public function test_get_option_name_unique() {
		$options = array();

		$settingsMock        = $this->getMockBuilder( 'cookiebot_addons\lib\Settings_Service_Interface' )->getMock();
		$scriptLoaderTagMock = $this->getMockBuilder( 'cookiebot_addons\lib\script_loader_tag\Script_Loader_Tag_Interface' )->getMock();
		$cookieConsentMock   = $this->getMockBuilder( 'cookiebot_addons\lib\Cookie_Consent_Interface' )->getMock();
		$bufferOutputMock    = $this->getMockBuilder( 'cookiebot_addons\lib\buffer\Buffer_Output_Interface' )->getMock();

		foreach ( $this->plugins as $plugin ) {
			$p = new $plugin->class( $settingsMock, $scriptLoaderTagMock, $cookieConsentMock, $bufferOutputMock );

			// test if the option_name exists in the options array
			$this->assertNotContains( $p->get_option_name(), $options );

			// add name to options array
			$options[] = $p->get_option_name();
		}
	}

}