<?php

namespace cybot\cookiebot\addons\controller\addons\hubspot_leadin;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;

class Hubspot_Leadin extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME                  = 'HubSpot - Free Marketing Plugin for WordPress';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable tracking.';
	const OPTION_NAME                 = 'hubspot_leadin';
	const PLUGIN_FILE_PATH            = 'leadin/leadin.php';
	const DEFAULT_COOKIE_TYPES        = array( 'marketing', 'statistics' );
	const ENABLE_ADDON_BY_DEFAULT     = false;
	const SVN_URL_BASE_PATH           = 'https://plugins.svn.wordpress.org/leadin/trunk/';
	const SVN_URL_DEFAULT_SUB_PATH    = 'leadin.php';

	/**
	 * Manipulate the scripts if they are loaded.
	 *
	 * @since 1.3.0
	 */
	public function load_addon_configuration() {

		// block the script untill the consent is given

		if ( defined( 'LEADIN_PLUGIN_VERSION' ) ) {
			if ( version_compare( LEADIN_PLUGIN_VERSION, '7.10.0', '>=' ) ) {
				$this->script_loader_tag->add_tag( 'leadin-script-loader-js', $this->get_cookie_types() );
			} else {
				$this->script_loader_tag->add_tag( 'leadin-scriptloader-js', $this->get_cookie_types() );
			}
		}
	}
}
