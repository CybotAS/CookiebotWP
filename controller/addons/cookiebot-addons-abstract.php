<?php

namespace cookiebot_addons_framework\controller\addons;

use cookiebot_addons_framework\lib\Cookiebot_Buffer_Output;
use cookiebot_addons_framework\lib\Cookiebot_Script_Loader_Tag;
use cookiebot_addons_framework\lib\Cookiebot_Cookie_Consent;

Abstract Class Cookiebot_Addons_Abstract implements Cookiebot_Addons_Interface {

	/**
	 * @var Cookiebot_Script_Loader_Tag
	 */
	protected $script_loader_tag;

	/**
	 * @var Cookiebot_Cookie_Consent
	 */
	protected $cookie_consent;

	/**
	 * @var Cookiebot_Buffer_Output
	 */
	protected $buffer_output;

	/**
	 * Cookiebot_Addons_Abstract constructor.
	 *
	 * @param Cookiebot_Script_Loader_Tag $script_loader_tag
	 * @param Cookiebot_Cookie_Consent $cookie_consent
	 * @param Cookiebot_Buffer_Output $buffer_output
	 */
	public function __construct( Cookiebot_Script_Loader_Tag $script_loader_tag, Cookiebot_Cookie_Consent $cookie_consent, Cookiebot_Buffer_Output $buffer_output ) {
		$this->script_loader_tag = $script_loader_tag;
		$this->cookie_consent    = $cookie_consent;
		$this->buffer_output     = $buffer_output;
	}
}