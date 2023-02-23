<?php

namespace cybot\cookiebot\lib;

class WP_Rocket_Helper {
	public function register_hooks() {
		// Add filter if WP rocket is enabled
		if ( defined( 'WP_ROCKET_VERSION' ) ) {
			add_filter( 'rocket_minify_excluded_external_js', array( $this, 'wp_rocket_exclude_external_js' ) );
		}
		// Add filter SiteGround Optimizer
		add_filter( 'sgo_javascript_combine_excluded_external_paths', array( $this, 'sgo_exclude_external_js' ) );
	}

	/**
	 * Cookiebot_WP Adding Cookiebot domain(s) to exclude list for WP Rocket minification.
	 *
	 * @version 1.6.1
	 * @since   1.6.1
	 */
	public function wp_rocket_exclude_external_js( $external_js_hosts ) {
		$external_js_hosts[] = 'consent.cookiebot.com';      // Add cookiebot domains
		$external_js_hosts[] = 'consentcdn.cookiebot.com';

		return $external_js_hosts;
	}

	/**
	 * Cookiebot_WP Adding Cookiebot domain(s) to exclude list for SGO minification.
	 *
	 * @version 3.6.5
	 * @since   3.6.5
	 */
	public function sgo_exclude_external_js( $exclude_list ) {
		// Uses same format as WP Rocket - for now we just use WP Rocket function
		return $this->wp_rocket_exclude_external_js( $exclude_list );
	}
}
