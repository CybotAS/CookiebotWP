<?php

namespace cybot\cookiebot\addons\controller\addons\custom_facebook_feed_pro;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;

class Custom_Facebook_Feed_Pro extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME                  = 'Custom Facebook Feed Pro';
	const OPTION_NAME                 = 'custom_facebook_feed_pro';
	const PLUGIN_FILE_PATH            = 'custom-facebook-feed-pro/custom-facebook-feed.php';
	const DEFAULT_COOKIE_TYPES        = array( 'marketing' );
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to watch this video.';

	/**
	 * Manipulate the scripts if they are loaded.
	 *
	 * @since 2.1.4
	 */
	public function load_addon_configuration() {

		// Remove cff_js action and replace it with our own
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
