<?php

namespace cybot\cookiebot\addons\controller\addons\google_site_kit;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;

class Google_Site_Kit extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME                  = 'Google Site Kit';
	const OPTION_NAME                 = 'google_site_kit';
	const DEFAULT_COOKIE_TYPES        = array( 'marketing', 'statistics' );
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable Google Analytics.';
	const PLUGIN_FILE_PATH            = 'google-site-kit/google-site-kit.php';
	const ENABLE_ADDON_BY_DEFAULT     = false;
	const SVN_URL_BASE_PATH           = 'https://plugins.svn.wordpress.org/google-site-kit/trunk/';
	const SVN_URL_DEFAULT_SUB_PATH    = 'google-site-kit.php';

	/**
	 * Disable scripts if state not accepted
	 *
	 * @since 1.3.0
	 */
	public function load_addon_configuration() {
		// Google Tag Manager
		$this->script_loader_tag->add_tag( 'google_gtagjs', $this->get_cookie_types() );
	}

	/**
	 * @return array
	 */
	public function get_extra_information() {
		return array(
			__( 'Blocks Google Analytics scripts', 'cookiebot' ),
		);
	}
}
