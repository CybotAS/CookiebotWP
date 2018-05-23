<?php

namespace cookiebot_addons_framework\controller\addons\ga_google_analytics;

use cookiebot_addons_framework\controller\addons\Cookiebot_Addons_Abstract;
use cookiebot_addons_framework\lib\Cookiebot_Buffer_Output;
use cookiebot_addons_framework\lib\Cookiebot_Script_Loader_Tag;
use cookiebot_addons_framework\lib\Cookiebot_Cookie_Consent;

class Ga_Google_Analytics extends Cookiebot_Addons_Abstract {

	/**
	 * Ga_Google_Analytics constructor.
	 *
	 * @param Cookiebot_Script_Loader_Tag $script_loader_tag
	 * @param Cookiebot_Cookie_Consent $cookie_consent
	 * @param Cookiebot_Buffer_Output $buffer_output
	 */
	public function __construct( Cookiebot_Script_Loader_Tag $script_loader_tag, Cookiebot_Cookie_Consent $cookie_consent, Cookiebot_Buffer_Output $buffer_output ) {
		parent::__construct( $script_loader_tag, $cookie_consent, $buffer_output );

		/**
		 * We add the action after wp_loaded and replace the original GA Google
		 * Analytics action with our own adjusted version.
		 */
		add_action( 'wp_loaded', array( $this, 'cookiebot_addon_ga_google_analytics' ), 5 );
	}

	/**
	 * Manipulate the scripts if they are loaded.
	 *
	 * @since 1.1.0
	 */
	public function cookiebot_addon_ga_google_analytics() {
		//Check if GA Google Analytics is loaded.
		if ( ! function_exists( 'ga_google_analytics_init' ) ) {
			return;
		}
		//Check if Cookiebot is activated and active.
		if ( ! function_exists( 'cookiebot_active' ) || ! cookiebot_active() ) {
			return;
		}

		//Remove GA Google action and replace it with our own
		if ( has_action( 'wp_head', 'ga_google_analytics_tracking_code' ) ) {
			$this->buffer_output->add_tag( 'wp_head', 10 );
		} elseif ( has_action( 'wp_footer', 'ga_google_analytics_tracking_code' ) ) {
			$this->buffer_output->add_tag( 'wp_footer', 10 );
		}
	}
}