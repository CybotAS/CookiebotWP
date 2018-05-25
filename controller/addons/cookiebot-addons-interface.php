<?php

namespace cookiebot_addons_framework\controller\addons;

use cookiebot_addons_framework\lib\Cookiebot_Buffer_Output;
use cookiebot_addons_framework\lib\Cookiebot_Cookie_Consent;
use cookiebot_addons_framework\lib\Cookiebot_Script_Loader_Tag_Interface;

Interface Cookiebot_Addons_Interface {

	/**
	 * Cookiebot_Addons_Interface constructor.
	 *
	 * @param Cookiebot_Script_Loader_Tag $script_loader_tag
	 * @param Cookiebot_Cookie_Consent $cookie_consent
	 * @param Cookiebot_Buffer_Output $buffer_output
	 */
	public function __construct( Cookiebot_Script_Loader_Tag_Interface $script_loader_tag, Cookiebot_Cookie_Consent $cookie_consent, Cookiebot_Buffer_Output $buffer_output );
}