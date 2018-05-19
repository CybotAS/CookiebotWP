<?php

namespace cookiebot_addons_framework\controller\addons\hubspot_tracking_code;

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

class Hubspot_Tracking_Code {

	public function __construct() {
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
		$options = get_option('hs_settings');
		if ( !isset($options['hs_portal']) || $options['hs_portal'] == '' ) {
			return;
		}

		// Check if Cookiebot is activated and active.
		if ( ! function_exists( 'cookiebot_active' ) || ! cookiebot_active() ) {
			return;
		}

		// Replace original HubSpot Tracking Code with own one
		if ( is_plugin_active('hubspot-tracking-code/hubspot-tracking-code.php') ) {
			new Hubspot_Tracking_Code_Buffer_Output( 'wp_footer', 10 );
		}
	}
}
