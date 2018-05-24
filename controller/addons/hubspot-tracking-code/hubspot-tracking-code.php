<?php

namespace cookiebot_addons_framework\controller\addons\hubspot_tracking_code;

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

use cookiebot_addons_framework\controller\addons\Cookiebot_Addons_Abstract;
use cookiebot_addons_framework\controller\addons\Cookiebot_Addons_Interface;
use cookiebot_addons_framework\lib\Cookiebot_Script_Loader_Tag;
use cookiebot_addons_framework\lib\Cookiebot_Cookie_Consent;
use cookiebot_addons_framework\lib\Cookiebot_Buffer_Output;

class Hubspot_Tracking_Code implements Cookiebot_Addons_Interface {

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
	 * Hubspot_Tracking_Code constructor.
	 *
	 * @param Cookiebot_Script_Loader_Tag $script_loader_tag
	 * @param Cookiebot_Cookie_Consent $cookie_consent
	 * @param Cookiebot_Buffer_Output $buffer_output
	 */
	public function __construct( Cookiebot_Script_Loader_Tag $script_loader_tag, Cookiebot_Cookie_Consent $cookie_consent, Cookiebot_Buffer_Output $buffer_output ) {
		$this->script_loader_tag = $script_loader_tag;
		$this->cookie_consent    = $cookie_consent;
		$this->buffer_output     = $buffer_output;

		/**
		 * We add the action after wp_loaded and replace the original
		 * HubSpot Tracking Code action with our own adjusted version.
		 */
		add_action( 'wp_loaded', array( $this, 'cookiebot_addon_hubspot_tracking_code' ), 10 );
	}

	/**
	 * Manipulate the scripts if they are loaded.
	 *
	 * @since 1.2.0
	 */
	public function cookiebot_addon_hubspot_tracking_code() {
		// Check if HubSpot Tracking Code is loaded
		$options = get_option( 'hs_settings' );
		if ( ! isset( $options['hs_portal'] ) || $options['hs_portal'] == '' ) {
			return;
		}

		// Check if Cookiebot is activated and active.
		if ( ! function_exists( 'cookiebot_active' ) || ! cookiebot_active() ) {
			return;
		}

		// Replace original HubSpot Tracking Code with own one and delete cookie if
		// it was perviously set.
		if ( is_plugin_active( 'hubspot-tracking-code/hubspot-tracking-code.php' ) ) {
			$this->buffer_output->add_tag( 'wp_footer', 10, array( 'hs-script-loader' => 'marketing' ) );

			if ( ! $this->cookie_consent->is_cookie_state_accepted( 'marketing' ) && isset( $_COOKIE['hubspotutk'] ) ) {
				unset( $_COOKIE['hubspotutk'] );
			}
		}
	}
}
