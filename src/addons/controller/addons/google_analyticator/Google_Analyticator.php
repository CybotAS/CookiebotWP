<?php

namespace cybot\cookiebot\addons\controller\addons\google_analyticator;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;

class Google_Analyticator extends Base_Cookiebot_Plugin_Addon {
	const ADDON_NAME                  = 'Google Analyticator';
	const OPTION_NAME                 = 'google_analyticator';
	const PLUGIN_FILE_PATH            = 'google-analyticator/google-analyticator.php';
	const DEFAULT_COOKIE_TYPES        = array( 'statistics' );
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to watch this video.';
	const SVN_URL_BASE_PATH           = 'https://plugins.svn.wordpress.org/google-analyticator/trunk/';
	const SVN_URL_DEFAULT_SUB_PATH    = 'google-analyticator.php';

	public function load_addon_configuration() {
		/**
		 * ga scripts are loaded in wp_head priority set to 99
		 */
		if ( has_action( 'wp_head', 'add_google_analytics' ) ) {
			/**
			 * Consent not given - no cache
			 */
			$this->buffer_output->add_tag(
				'wp_head',
				99,
				array(
					'gtag'                                 => $this->get_cookie_types(),
					'google-analytics'                     => $this->get_cookie_types(),
					'_gaq'                                 => $this->get_cookie_types(),
					'www.googletagmanager.com/gtag/js?id=' => $this->get_cookie_types(),
				),
				false
			);
		}

		/**
		 * ga scripts are loaded in login_head priority set to 99
		 */
		if ( has_action( 'login_head', 'add_google_analytics' ) ) {
			/**
			 * Consent not given - no cache
			 */
			$this->buffer_output->add_tag(
				'login_head',
				99,
				array(
					'gtag'                                 => $this->get_cookie_types(),
					'google-analytics'                     => $this->get_cookie_types(),
					'_gaq'                                 => $this->get_cookie_types(),
					'www.googletagmanager.com/gtag/js?id=' => $this->get_cookie_types(),
				)
			);
		}

		/**
		 * External js, so manipulate attributes
		 */
		if ( has_action( 'wp_print_scripts', 'ga_external_tracking_js' ) ) {
			/**
			 * Catch external js file and add cookiebot attributes to it
			 *
			 * @since 1.1.0
			 */
			$this->script_loader_tag->add_tag( 'ga-external-tracking', $this->get_cookie_types() );
		}
	}
}
