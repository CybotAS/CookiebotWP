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
    /**
     * @var mixed|void
     */
    private $plugins;

    /**
	 * Get the plugins
	 *
	 * @since 1.8.0
	 */
	public function setUp() {
		$this->get_plugins();
	}

	/**
	 * Load the addons through json file.
	 *
	 * @since 1.8.0
	 */
	private function get_plugins() {
		$this->file_path = dirname( dirname( __DIR__ ) ) . '/addons.json';
		$this->file      = file_get_contents( $this->file_path );
		$this->plugins   = apply_filters( 'cookiebot_addons_list', json_decode( $this->file ) );
	}

	/**
	 * Validate if the plugins in addons.json do exist as a class in addons controller directory.
	 *
	 * @since 1.8.0
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
	 *
	 * @version 2.1.3
	 * @since 1.8.0
	 */
	public function test_get_option_name_unique() {
		$options = array();

		$settingsMock        = $this->getMockBuilder( 'cookiebot_addons\lib\Settings_Service_Interface' )
            ->disableOriginalConstructor()
            ->getMock();
		$scriptLoaderTagMock = $this->getMockBuilder( 'cookiebot_addons\lib\script_loader_tag\Script_Loader_Tag_Interface' )
            ->disableOriginalConstructor()
            ->getMock();
		$cookieConsentMock   = $this->getMockBuilder( 'cookiebot_addons\lib\Cookie_Consent_Interface' )
            ->disableOriginalConstructor()
            ->getMock();
		$bufferOutputMock    = $this->getMockBuilder( 'cookiebot_addons\lib\buffer\Buffer_Output_Interface' )
            ->disableOriginalConstructor()
            ->getMock();

		foreach ( $this->plugins as $plugin ) {
			$p = new $plugin->class( $settingsMock, $scriptLoaderTagMock, $cookieConsentMock, $bufferOutputMock );

			// if it doesn't have parent class
			if ( get_parent_class( $p ) === false ) {
				// test if the option_name exists in the options array
				$this->assertNotContains( $p->get_option_name(), $options );

				// add name to options array
				$options[] = $p->get_option_name();
			}

		}
	}

}