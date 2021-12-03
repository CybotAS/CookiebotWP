<?php

namespace cybot\cookiebot\addons\controller\addons\custom_facebook_feed;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;

class Custom_Facebook_Feed_Version_2_17_1 extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME                  = 'Custom Facebook Feed (<= 2.17.1)';
	const OPTION_NAME                 = 'custom_facebook_feed';
	const PLUGIN_FILE_PATH            = 'custom-facebook-feed/custom-facebook-feed.php';
	const DEFAULT_COOKIE_TYPES        = array( 'marketing' );
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to watch this video.';
	const SVN_URL_BASE_PATH           = 'https://plugins.svn.wordpress.org/custom-facebook-feed/tags/2.17.1/';
	const SVN_URL_DEFAULT_SUB_PATH    = 'custom-facebook-feed.php';
	/**
	 * Manipulate the scripts if they are loaded.
	 *
	 * @since 1.1.0
	 */
	public function load_addon_configuration() {
		if ( has_action( 'wp_footer', 'cff_js' ) ) {
			/**
			 * Consent not given - no cache
			 */
			$this->buffer_output->add_tag( 'wp_footer', 10, array( 'cfflinkhashtags' => $this->get_cookie_types() ), false );
		}

		// External js, so manipulate attributes
		if ( has_action( 'wp_enqueue_scripts', 'cff_scripts_method' ) ) {
			/**
			 * Consent not given - no cache
			 */
			$this->script_loader_tag->add_tag( 'cffscripts', $this->get_cookie_types() );
		}
	}
}
