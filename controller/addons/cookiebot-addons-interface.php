<?php

namespace cookiebot_addons_framework\controller\addons;

use cookiebot_addons_framework\lib\buffer\Cookiebot_Buffer_Output;
use cookiebot_addons_framework\lib\buffer\Cookiebot_Buffer_Output_Interface;
use cookiebot_addons_framework\lib\Cookiebot_Cookie_Consent;
use cookiebot_addons_framework\lib\Cookiebot_Cookie_Consent_Interface;
use cookiebot_addons_framework\lib\script_loader_tag\Cookiebot_Script_Loader_Tag_Interface;

Interface Cookiebot_Addons_Interface {

	/**
	 * Cookiebot_Addons_Interface constructor.
	 *
	 * @param Cookiebot_Script_Loader_Tag_Interface $script_loader_tag
	 * @param Cookiebot_Cookie_Consent_Interface $cookie_consent
	 * @param Cookiebot_Buffer_Output_Interface $buffer_output
	 */
	public function __construct( Cookiebot_Script_Loader_Tag_Interface $script_loader_tag, Cookiebot_Cookie_Consent_Interface $cookie_consent, Cookiebot_Buffer_Output_Interface $buffer_output );

	/**
	 * Loads addon configuration
	 *
	 * @since 1.3.0
	 */
	public function load_configuration();
}