<?php

namespace cookiebot_addons_framework\controller\addons\ga_google_analytics;

class Ga_Google_Analytics {

	public function __construct() {
		/**
		 * We add the action after wp_loaded and replace the original GA Google
		 * Analytics action with our own adjusted version.
		 */
		add_action( 'wp_loaded', array( $this, 'cookiebot_addon_ga_google_analytics' ), 5 );
	}

	/**
	 * Manipulate the scripts if they are loaded.
	 *
	 * @since 1.0.0
	 */
	public function cookiebot_addon_ga_google_analytics() {
		//Check if GA Google Analytics is loaded.
		if ( ! function_exists( 'ga_google_analytics_init' ) ) {
			return;
		}
		//Check if Cookiebot is activated and active.
		if ( ! function_exists( 'cookiebot_active' ) || ! cookiebot_active() ) {
			return;
		}

		//Remove GA Google action and replace it with our own
		if ( has_action( 'wp_head', 'ga_google_analytics_tracking_code' ) ) {
			cookie_buffer_output( 'wp_head', 10 );
		} elseif ( has_action( 'wp_footer', 'ga_google_analytics_tracking_code' ) ) {
			cookie_buffer_output( 'wp_footer', 10 );
		}
	}
}