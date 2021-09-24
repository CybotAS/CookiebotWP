<?php

namespace cybot\cookiebot\addons\controller\addons\google_site_kit;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;
use cybot\cookiebot\lib\Addon_With_Extra_Information_Interface;
use cybot\cookiebot\lib\Open_Source_Addon_Interface;

class Google_Site_Kit extends Base_Cookiebot_Plugin_Addon implements Open_Source_Addon_Interface, Addon_With_Extra_Information_Interface {

	const ADDON_NAME                  = 'Google Site Kit';
	const OPTION_NAME                 = 'google_site_kit';
	const DEFAULT_COOKIE_TYPES        = array( 'marketing', 'statistics' );
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable Google Analytics.';
	const PLUGIN_FILE_PATH            = 'google-site-kit/google-site-kit.php';
	const ENABLE_ADDON_BY_DEFAULT     = false;

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
	 * Adds extra information under the label
	 *
	 * @return string[]
	 *
	 * @since 1.8.0
	 */
	public function get_extra_information() {
		return array(
			__( 'Blocks Google Analytics scripts', 'cookiebot-addons' ),
		);
	}

	/**
	 * Returns the url of WordPress SVN repository or another link where we can verify the plugin file.
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public static function get_svn_url( $path = 'google-site-kit.php' ) {
		return 'http://plugins.svn.wordpress.org/google-site-kit/trunk/' . $path;
	}
}
