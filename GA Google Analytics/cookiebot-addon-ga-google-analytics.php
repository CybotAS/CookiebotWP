<?php
/**
 * Plugin Name:    Cookiebot Addon for `GA Google Analytics`
 * Description: Adding support for Cookiebot
 * Author: Johan Holst Nielsen
 * Version: 1.5.4
 */

function cookiebot_addon_ga_google_analytics() {
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
		add_action( 'wp_head', 'cookiebot_addon_ga_start_buffer', 9 );
		add_action( 'wp_head', 'cookiebot_addon_ga_end_buffer', 11 );
	} elseif ( has_action( 'wp_footer', 'ga_google_analytics_tracking_code' ) ) {
		add_action( 'wp_footer', 'cookiebot_addon_ga_start_buffer', 9 );
		add_action( 'wp_footer', 'cookiebot_addon_ga_end_buffer', 11 );
	}
}

/**
 * Start reading the buffer/output
 *
 * @since 1.0.0
 */
function cookiebot_addon_ga_start_buffer() {
	ob_start( 'cookiebot_addon_ga_manipulate_script' );
}

/**
 * Stop reading the output and output buffered data through manipulate script filter.
 *
 * @since 1.0.0
 */
function cookiebot_addon_ga_end_buffer() {
	ob_end_flush();
}

/**
 * Manipulate google analytic scripts to cookiebot and return it back
 *
 * @param $buffer
 *
 * @return null|string|string[]
 *
 * @since 1.0.0
 */
function cookiebot_addon_ga_manipulate_script( $buffer ) {
	/**
	 * Get wp head scripts from the cache
	 */
	$updated_scripts = get_transient( 'cookiebot_wp_head_scripts' );

	/**
	 * If cache is not set then build it
	 */
	if ( $updated_scripts === false ) {
		/**
		 * Pattern to get all scripts
		 */
		$pattern = "/\<script(.*?)?\>(.|\s)*?\<\/script\>/i";

		/**
		 * Get all scripts and add cookieconsent if it does match with the criterion
		 */
		$updated_scripts = preg_replace_callback( $pattern, function ( $matches ) {
			/**
			 * Matched script data
			 */
			$data = ( isset( $matches[0] ) ) ? $matches[0] : '';

			/**
			 * Keywords to look for
			 **/
			$needles = array( 'gtag', 'google-analytics', '_gaq', 'www.googletagmanager.com/gtag/js?id=' );

			/**
			 * Check if the script contains the keywords, checks keywords one by one
			 *
			 * If one match, then the rest of the keywords will be skipped.
			 **/
			foreach ( $needles as $needle ) {
				/**
				 * The script contains the needle
				 **/
				if ( strpos( $data, $needle ) !== false ) {
					$data = preg_replace( '/\<script/', '<script type="text/plain" data-cookieconsent="statistics"', $data );

					/**
					 * matched already so we can skip other keywords
					 **/
					continue;
				}
			}

			/**
			 * Return updated script data
			 */
			return $data;
		}, $buffer );

		/**
		 * Set cache for 15 minutes
		 */
		set_transient( 'cookiebot_wp_head_scripts', $updated_scripts, 60 * 15 );
	}

	return $updated_scripts;
}

/*
 * We add the action after wp_loaded and replace the original GA Google
 * Analytics action with our own adjusted version.
 */
add_action( 'wp_loaded', 'cookiebot_addon_ga_google_analytics', 5 );
