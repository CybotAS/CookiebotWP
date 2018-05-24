<?php

namespace cookiebot_addons_framework\controller\addons\google_analyticator;

use cookiebot_addons_framework\controller\addons\Cookiebot_Addons_Abstract;
use cookiebot_addons_framework\controller\addons\Cookiebot_Addons_Interface;
use cookiebot_addons_framework\lib\Cookiebot_Buffer_Output;
use cookiebot_addons_framework\lib\Cookiebot_Cookie_Consent;
use cookiebot_addons_framework\lib\Cookiebot_Script_Loader_Tag;

class Google_Analyticator implements Cookiebot_Addons_Interface {

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
	 * Google_Analyticator constructor.
	 *
	 * @param Cookiebot_Script_Loader_Tag $script_loader_tag
	 * @param Cookiebot_Cookie_Consent $cookie_consent
	 * @param Cookiebot_Buffer_Output $buffer_output
	 */
	public function __construct( Cookiebot_Script_Loader_Tag $script_loader_tag, Cookiebot_Cookie_Consent $cookie_consent, Cookiebot_Buffer_Output $buffer_output ) {
		$this->script_loader_tag = $script_loader_tag;
		$this->cookie_consent    = $cookie_consent;
		$this->buffer_output     = $buffer_output;

		add_action( 'wp_loaded', array( $this, 'cookiebot_addon_google_analyticator' ), 5 );
	}

	/**
	 * Check for google analyticator action hooks
	 *
	 * @since 1.1.0
	 */
	public function cookiebot_addon_google_analyticator() {
		/**
		 * ga scripts are loaded in wp_head priority set to 99
		 */
		if ( has_action( 'wp_head', 'add_google_analytics' ) ) {
			$this->buffer_output->add_tag( 'wp_head', 99 );
		}

		/**
		 * ga scripts are loaded in login_head priority set to 99
		 */
		if ( has_action( 'login_head', 'add_google_analytics' ) ) {
			$this->buffer_output->add_tag( 'login_head', 99 );
		}

		/**
		 * External js, so manipulate attributes
		 */
		if ( has_action( 'wp_print_scripts', 'ga_external_tracking_js' ) ) {
			/**
			 * Catch external js file and add cookiebot attributes to it
			 *
			 * @since 1.1.0
			 */
			$this->script_loader_tag->add_tag( 'ga-external-tracking', 'statistics' );
		}
	}
}