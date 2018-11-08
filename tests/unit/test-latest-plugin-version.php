<?php

namespace cookiebot_addons\tests\unit;

use cookiebot_addons\controller\addons\caos_host_analyticsjs_local_save_ga_local\CAOS_Host_Analyticsjs_Local_Save_Ga_Local;
use cookiebot_addons\controller\addons\caos_host_analyticsjs_local\CAOS_Host_Analyticsjs_Local;
use cookiebot_addons\lib\Settings_Service;

class Test_Latest_Plugin_Version extends \WP_UnitTestCase {

	/**
	 *  Test if older version has the latest plugin version
	 *
	 * @since 2.1.3
	 */
	public function test_if_older_version_has_latest_plugin_version() {
		$settings = new Settings_Service( new \stdClass() );

		$scriptLoaderTagMock = $this->getMockBuilder( 'cookiebot_addons\lib\script_loader_tag\Script_Loader_Tag_Interface' )->getMock();
		$cookieConsentMock   = $this->getMockBuilder( 'cookiebot_addons\lib\Cookie_Consent_Interface' )->getMock();
		$bufferOutputMock    = $this->getMockBuilder( 'cookiebot_addons\lib\buffer\Buffer_Output_Interface' )->getMock();

		$addonMock = new CAOS_Host_Analyticsjs_Local_Save_Ga_Local(
			$settings,
			$scriptLoaderTagMock,
			$cookieConsentMock,
			$bufferOutputMock
		);

		$this->assertFalse( $settings->is_latest_plugin_version( $addonMock ) );
	}

	/**
	 *  Test if newer version has the latest plugin version
	 *
	 * @since 2.1.3
	 */
	public function test_if_newer_version_has_latest_plugin_version() {
		$settings = new Settings_Service( new \stdClass() );

		$scriptLoaderTagMock = $this->getMockBuilder( 'cookiebot_addons\lib\script_loader_tag\Script_Loader_Tag_Interface' )->getMock();
		$cookieConsentMock   = $this->getMockBuilder( 'cookiebot_addons\lib\Cookie_Consent_Interface' )->getMock();
		$bufferOutputMock    = $this->getMockBuilder( 'cookiebot_addons\lib\buffer\Buffer_Output_Interface' )->getMock();

		$addonMock = new CAOS_Host_Analyticsjs_Local(
			$settings,
			$scriptLoaderTagMock,
			$cookieConsentMock,
			$bufferOutputMock
		);

		$this->assertTrue( $settings->is_latest_plugin_version( $addonMock ) );
	}
}