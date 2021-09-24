<?php

namespace cybot\cookiebot\tests\unit;

use cybot\cookiebot\lib\buffer\Buffer_Output_Interface;
use cybot\cookiebot\lib\Cookie_Consent_Interface;
use cybot\cookiebot\lib\Open_Source_Addon_Interface;
use cybot\cookiebot\lib\script_loader_tag\Script_Loader_Tag_Interface;
use cybot\cookiebot\lib\Settings_Service_Interface;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;
use function cybot\cookiebot\tests\remote_get_svn_contents;
use const cybot\cookiebot\addons\PLUGIN_ADDONS;

class Test_Addon_File_Name extends WP_UnitTestCase {

	public function setUp() {
	}

	/**
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 */
	public function test_plugins_are_valid() {
		foreach ( PLUGIN_ADDONS as $plugin ) {
			$check = class_exists( $plugin );
			$this->assertTrue( $check );
		}
	}

	/**
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 */
	public function test_get_svn_url() {
		$settings_mock          = $this->getMockBuilder( Settings_Service_Interface::class )
			->disableOriginalConstructor()
			->getMock();
		$script_loader_tag_mock = $this->getMockBuilder( Script_Loader_Tag_Interface::class )
			->disableOriginalConstructor()
			->getMock();
		$cookie_consent_mock    = $this->getMockBuilder( Cookie_Consent_Interface::class )
			->disableOriginalConstructor()
			->getMock();
		$buffer_output_mock     = $this->getMockBuilder( Buffer_Output_Interface::class )
			->disableOriginalConstructor()
			->getMock();

		foreach ( PLUGIN_ADDONS as $plugin_addon_classname ) {
			$plugin_addon = new $plugin_addon_classname(
				$settings_mock,
				$script_loader_tag_mock,
				$cookie_consent_mock,
				$buffer_output_mock
			);

			if ( is_a( $plugin_addon, Open_Source_Addon_Interface::class ) ) {
				$svn_url = $plugin_addon->get_svn_url();
				if ( ! empty( $svn_url ) ) {
					$content = remote_get_svn_contents( $svn_url );
					$this->assertNotFalse( $content );
				}
			}
		}
	}
}
