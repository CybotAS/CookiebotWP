<?php

namespace cybot\cookiebot\addons\controller\addons\hubspot_tracking_code;

require_once ABSPATH . 'wp-admin/includes/plugin.php';

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;
use cybot\cookiebot\addons\lib\Open_Source_Addon_Interface;

class Hubspot_Tracking_Code extends Base_Cookiebot_Plugin_Addon implements Open_Source_Addon_Interface {

	const ADDON_NAME                  = 'Hubspot Tracking Code';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable tracking.';
	const OPTION_NAME                 = 'hubspot_tracking_code';
	const PLUGIN_FILE_PATH            = 'hubspot-tracking-code/hubspot-tracking-code.php';
	const DEFAULT_COOKIE_TYPES        = array( 'marketing', 'statistics' );
	const ENABLE_ADDON_BY_DEFAULT     = false;

	/**
	 * Manipulate the scripts if they are loaded.
	 *
	 * @since 1.3.0
	 */
	public function load_addon_configuration() {
		// Check if HubSpot Tracking Code is loaded
		$options = get_option( 'hs_settings' );
		if ( empty( $options['hs_portal'] ) ) {
			return;
		}

		// Replace original HubSpot Tracking Code with own one and delete cookie if
		// it was perviously set.

		$this->buffer_output->add_tag( 'wp_footer', 10, array( 'hs-script-loader' => $this->get_cookie_types() ), false );

		if ( ! $this->cookie_consent->are_cookie_states_accepted( $this->get_cookie_types() ) && isset( $_COOKIE['hubspotutk'] ) ) {
			unset( $_COOKIE['hubspotutk'] );
		}

	}

	/**
	 * Adds extra information under the label
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_extra_information() {
		return '';
	}

	/**
	 * Returns the url of WordPress SVN repository or another link where we can verify the plugin file.
	 *
	 * @return boolean
	 *
	 * @since 1.8.0
	 */
	public function get_svn_url() {
		return 'http://plugins.svn.wordpress.org/hubspot-tracking-code/trunk/hubspot-tracking-code.php';
	}
}
