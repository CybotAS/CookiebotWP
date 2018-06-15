<?php

namespace cookiebot_addons_framework\controller\addons\jetpack\widget;

class Google_Maps_Widget {

	/**
	 * @var array   list of supported cookie types
	 *
	 * @since 1.3.0
	 */
	protected $cookie_types;

	/**
	 * This class is used to support facebook page widget in jetpack
	 *
	 * Google_Maps_Widget constructor.
	 *
	 * @param   $widget_enabled boolean             true - if the widget is activated
	 * @param   $cookie_types   array               List of supported cookie types
	 * @param   $placeholder_enabled   boolean      true - display placeholder div
	 *
	 * @version 1.3.0
	 * @since 1.2.0
	 */
	public function __construct( $widget_enabled, $cookie_types, $placeholder_enabled ) {
		if ( is_active_widget( false, false, 'widget_contact_info', true ) ) {
			/**
			 * Widget is disabled in the backend
			 */
			if ( $widget_enabled ) {
				$this->cookie_types = $cookie_types;

				/**
				 * Replace attributes of the google maps widget iframe
				 */
				add_action( 'jetpack_contact_info_widget_start', array( $this, 'start_buffer' ) );
				add_action( 'jetpack_contact_info_widget_end', array( $this, 'stop_buffer' ) );
			}
		}
	}

	/**
	 * Start catching the output
	 *
	 * @since 1.2.0
	 */
	public function start_buffer() {
		ob_start( array( $this, 'manipulate_iframe' ) );
	}

	/**
	 * Clear the buffer
	 *
	 * @since 1.2.0
	 */
	public function stop_buffer() {
		ob_end_flush();
	}

	/**
	 * Return manipulated output with cookieconsent attribute
	 *
	 * @param $buffer
	 *
	 * @return mixed|null|string|string[]
	 *
	 * @since 1.2.0
	 */
	public function manipulate_iframe( $buffer ) {
		/**
		 * Get wp head scripts from the cache
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

				$data = ( isset( $matches[0] ) ) ? $matches[0] : '';

				$data = str_replace( 'src=', 'data-cookieconsent="' . cookiebot_output_cookie_types( $this->cookie_types ) . '" data-src=', $data );

				if ( is_array( $this->cookie_types ) && count( $this->cookie_types ) > 0 ) {
					$data .= '<div class="cookieconsent-optout-' . cookiebot_get_one_cookie_type( $this->cookie_types ) . '">
						  ' . sprintf( __( 'Please <a href="javascript:Cookiebot.renew()">accept %s cookies</a> to watch this google map.', 'cookiebot_addons' ), cookiebot_output_cookie_types( $this->cookie_types ) ) . '
						</div>';	
				}
				

				/**
				 * Return updated iframe tag
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