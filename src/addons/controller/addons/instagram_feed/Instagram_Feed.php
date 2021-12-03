<?php

namespace cybot\cookiebot\addons\controller\addons\instagram_feed;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;

class Instagram_Feed extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME                  = 'Instagram feed';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable instagram feed.';
	const OPTION_NAME                 = 'instagram_feed';
	const PLUGIN_FILE_PATH            = 'instagram-feed/instagram-feed.php';
	const DEFAULT_COOKIE_TYPES        = array( 'marketing' );
	const ENABLE_ADDON_BY_DEFAULT     = false;
	const SVN_URL_BASE_PATH           = 'https://plugins.svn.wordpress.org/instagram-feed/trunk/';
	const SVN_URL_DEFAULT_SUB_PATH    = 'instagram-feed.php';

	/**
	 * Disable scripts if state not accepted
	 *
	 * @since 1.3.0
	 */
	public function load_addon_configuration() {
		// External js, so manipulate attributes
		if ( has_action( 'wp_enqueue_scripts', 'sb_instagram_scripts_enqueue' ) ) {
			$this->script_loader_tag->add_tag( 'sb_instagram_scripts', $this->get_cookie_types() );
		}
	}
}
