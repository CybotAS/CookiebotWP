<?php

namespace cookiebot_addons_framework\controller\addons\jetpack;

class Jetpack {

	public function __construct() {
		add_action( 'init', array( $this, 'cookiebot_addon_jetpack' ), 5 );
	}

	/**
	 * Check for google analyticator action hooks
	 *
	 * @since 1.1.0
	 */
	public function cookiebot_addon_jetpack() {
		/**
		 * Replace attributes of the google maps widget iframe
		 */
		add_action( 'jetpack_contact_info_widget_start', array( $this, 'start_buffer' ) );
		add_action( 'jetpack_contact_info_widget_end', array( $this, 'stop_buffer' ) );
	}

	public function start_buffer() {
		ob_start( array( $this, 'manipulate_iframe' ) );
	}

	public function stop_buffer() {
		ob_end_flush();
	}

	public function manipulate_iframe( $buffer ) {
		/**
		 * Get jetpack google maps widget data from the cache
		 */
		$updated_scripts = get_transient( 'jetpack_google_maps_widget' );

		/**
		 * If cache is not set then build it
		 */
		if ( $updated_scripts === false ) {
			/**
			 * Pattern to get all iframes
			 */
			$pattern = "/\<iframe(.*?)?\>(.|\s)*?\<\/iframe\>/i";

			/**
			 * Get all scripts and add cookieconsent if it does match with the criterion
			 */
			$updated_scripts = preg_replace_callback( $pattern, function ( $matches ) {
				/**
				 * Matched iframe data
				 */
				$data = ( isset( $matches[0] ) ) ? $matches[0] : '';

				$data = str_replace( 'src=', 'data-cookieconsent="marketing" data-src=', $data );

				/**
				 * Displays a message to give a consent for marketing-cookies
				 */
				$data .= '<div class="cookieconsent-optout-marketing">
						  ' . printf( __( 'Please <a href="%s">accept marketing-cookies</a> to watch this video.', 'cookiebot_addons' ), 'javascript:Cookiebot.renew()' ) . '
						</div>';

				/**
				 * Return updated script data
				 */
				return $data;
			}, $buffer );

			/**
			 * Set cache for 15 minutes
			 */
			set_transient( 'jetpack_google_maps_widget', $updated_scripts, 60 * 15 );
		}

		return $updated_scripts;
	}
}