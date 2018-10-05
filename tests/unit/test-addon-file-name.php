<?php

namespace cookiebot_addons\tests\unit;

use DI\ContainerBuilder;
use DI;

class Test_Addon_File_Name extends \WP_UnitTestCase {
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
		$this->file      = file_get_contents( $this->file_path );
		$this->plugins   = json_decode( $this->file );
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

	public function test_get_svn_url() {
		$settingsMock        = $this->getMockBuilder( 'cookiebot_addons\lib\Settings_Service_Interface' )->getMock();
		$scriptLoaderTagMock = $this->getMockBuilder( 'cookiebot_addons\lib\script_loader_tag\Script_Loader_Tag_Interface' )->getMock();
		$cookieConsentMock   = $this->getMockBuilder( 'cookiebot_addons\lib\Cookie_Consent_Interface' )->getMock();
		$bufferOutputMock    = $this->getMockBuilder( 'cookiebot_addons\lib\buffer\Buffer_Output_Interface' )->getMock();
		$result              = array();

		foreach ( $this->plugins as $plugin ) {
			$p = new $plugin->class( $settingsMock, $scriptLoaderTagMock, $cookieConsentMock, $bufferOutputMock );
			if ( method_exists( $p, 'get_svn_url' ) && ! $p->get_svn_url() ) {
				$svn_address = $p->get_svn_url();
				if ( ! empty( $svn_address ) ) {
					$content     = file_get_contents($svn_address);
					$this->assertNotFalse( $content, $plugin->class );
				}
			}
		}
	}
}
