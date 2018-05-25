<?php

namespace cookiebot_addons_framework\controller\addons;

use cookiebot_addons_framework\lib\buffer\Cookiebot_Buffer_Output_Interface;
use cookiebot_addons_framework\lib\Cookiebot_Cookie_Consent_Interface;
use cookiebot_addons_framework\lib\Cookiebot_Settings_Interface;
use cookiebot_addons_framework\lib\script_loader_tag\Cookiebot_Script_Loader_Tag_Interface;

Interface Cookiebot_Addons_Interface {

	/**
	 * Cookiebot_Addons_Interface constructor.
	 *
	 * @param Cookiebot_Settings_Interface $settings
	 * @param Cookiebot_Script_Loader_Tag_Interface $script_loader_tag
	 * @param Cookiebot_Cookie_Consent_Interface $cookie_consent
	 * @param Cookiebot_Buffer_Output_Interface $buffer_output
	 */
	public function __construct( Cookiebot_Settings_Interface $settings, Cookiebot_Script_Loader_Tag_Interface $script_loader_tag, Cookiebot_Cookie_Consent_Interface $cookie_consent, Cookiebot_Buffer_Output_Interface $buffer_output );

	/**
	 * Loads addon configuration
	 *
	 * @since 1.3.0
	 */
	public function load_configuration();

	/**
	 * Return addon/plugin name
	 *
	 * @return string
	 */
	public function get_addon_name();

	/**
	 * The addon is checked in the backend, so update the status to 1.
	 */
	public function enable_addon();

	/**
	 * The addon is unchecked in the backend, so update the status to 0.
	 */
	public function disable_addon();

	/**
	 * Check if plugin is activated and checked in the backend
	 */
	public function is_addon_enabled();

	/**
	 * Checks if addon is installed
	 */
	public function is_plugin_installed();
}