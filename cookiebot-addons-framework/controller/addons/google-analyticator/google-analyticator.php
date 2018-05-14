<?php

namespace cookiebot_addons_framework\controller\addons\google_analyticator;

class Google_Analyticator {

	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'cookiebot_addon_google_analyticator' ), 5 );
	}

	/**
	 * Check for google analyticator action hooks
	 *
	 * @since 1.0.0
	 */
	public function cookiebot_addon_google_analyticator() {
		/**
		 * ga scripts are loaded in wp_head priority set to 99
		 */
		if ( has_action( 'wp_head', 'add_google_analytics' ) ) {
			cookie_buffer_output( 'wp_head', 99 );
		}

		/**
		 * ga scripts are loaded in login_head priority set to 99
		 */
		if ( has_action( 'login_head', 'add_google_analytics' ) ) {
			cookie_buffer_output( 'login_head', 99 );
		}

		/**
		 * External js, so manipulate attributes
		 */
		if ( has_action( 'wp_print_scripts', 'ga_external_tracking_js' ) ) {
			/**
			 * Catch external js file and add cookiebot attributes to it
			 *
			 * @since 1.0.0
			 */
			cookiebot_script_loader_tag( 'ga-external-tracking' );
		}
	}
}