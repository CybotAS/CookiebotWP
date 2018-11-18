<?php

namespace cookiebot_addons\tests\unit;

use cookiebot_addons\controller\addons\caos_host_analyticsjs_local_save_ga_local\CAOS_Host_Analyticsjs_Local_Save_Ga_Local;
use cookiebot_addons\controller\addons\caos_host_analyticsjs_local\CAOS_Host_Analyticsjs_Local;
use cookiebot_addons\lib\Settings_Service;

class Test_Previous_Version_Active extends \WP_UnitTestCase {

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

		$addon = $this->getMockBuilder( 'cookiebot_addons\controller\addons\caos_host_analyticsjs_local_save_ga_local\CAOS_Host_Analyticsjs_Local_Save_Ga_Local' )
		              ->setConstructorArgs(
			              array(
				              $settings,
				              $scriptLoaderTagMock,
				              $cookieConsentMock,
				              $bufferOutputMock
			              )
		              )
		              ->getMock();

		$addon->expects( $this->any() )
		      ->method( 'is_addon_activated' )
		      ->will( $this->returnValue( true ) );

		$addon->expects( $this->any() )
		      ->method( 'get_parent_class' )
		      ->will( $this->returnValue( 'CAOS_Host_Analyticsjs_Local' ) );

		$this->assertTrue( $settings->is_previous_version_active( array( $addon ), 'CAOS_Host_Analyticsjs_Local' ) );
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

		$addon = $this->getMockBuilder( 'cookiebot_addons\controller\addons\caos_host_analyticsjs_local\CAOS_Host_Analyticsjs_Local' )
		              ->setConstructorArgs(
			              array(
				              $settings,
				              $scriptLoaderTagMock,
				              $cookieConsentMock,
				              $bufferOutputMock
			              )
		              )
		              ->getMock();

		$addon->expects( $this->any() )
		      ->method( 'is_addon_activated' )
		      ->will( $this->returnValue( true ) );

		$this->assertFalse( $settings->is_previous_version_active( array( $addon ), 'CAOS_Host_Analyticsjs_Local' ) );
	}
}