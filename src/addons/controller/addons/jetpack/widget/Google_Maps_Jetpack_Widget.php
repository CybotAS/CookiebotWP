<?php

namespace cybot\cookiebot\addons\controller\addons\jetpack\widget;

use function cybot\cookiebot\lib\cookiebot_addons_cookieconsent_optout;
use function cybot\cookiebot\lib\cookiebot_addons_output_cookie_types;

class Google_Maps_Jetpack_Widget extends Base_Jetpack_Widget {

	const LABEL               = 'Google Maps';
	const WIDGET_OPTION_NAME  = 'google_maps';
	const DEFAULT_PLACEHOLDER = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable google maps.';

	/**
	 * @var array   list of supported cookie types
	 *
	 * @since 1.3.0
	 */
	private $cookie_types = array();

	public function load_configuration() {
		if (
			// The widget is active
			is_active_widget( false, false, 'widget_contact_info', true ) &&
			// The widget is enabled in Prior consent
			$this->is_widget_enabled() &&
			// The visitor didn't check the required cookie types
			! $this->cookie_consent->are_cookie_states_accepted( $this->get_widget_cookie_types() )
		) {
			$this->cookie_types = $this->get_widget_cookie_types();

			/**
			 * Replace attributes of the google maps widget iframe
			 */
			add_action( 'jetpack_contact_info_widget_start', array( $this, 'start_buffer' ) );
			add_action( 'jetpack_contact_info_widget_end', array( $this, 'stop_buffer' ) );

			if ( $this->is_widget_placeholder_enabled() ) {
				add_action( 'jetpack_stats_extra', array( $this, 'cookie_consent_div' ), 10, 2 );
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
			$pattern = '/<iframe(.*?)?>(.|\s)*?<\/iframe>/i';

			/**
			 * Get all scripts and add cookieconsent if it does match with the criterion
			 */
			$updated_scripts = preg_replace_callback(
				$pattern,
				function ( $matches ) {
					$data                = ( isset( $matches[0] ) ) ? $matches[0] : '';
					$cookie_types_output = cookiebot_addons_output_cookie_types( $this->cookie_types );

					/**
					 * Return updated iframe tag
					 */
					return str_replace(
						'src=',
						'data-cookieconsent="' . $cookie_types_output . '" data-src=',
						$data
					);
				},
				$buffer
			);

			/**
			 * Set cache for 15 minutes
			 */
			set_transient( 'jetpack_google_maps_widget', $updated_scripts, 60 * 15 );
		}

		return $updated_scripts;
	}

	/**
	 * @param string $view
	 * @param string $widget
	 */
	public function cookie_consent_div( $view, $widget ) {
		if (
			( $widget === 'contact_info' && $view === 'widget_view' ) &&
			( is_array( $this->get_widget_cookie_types() ) && count( $this->get_widget_cookie_types() ) > 0 )
		) {
			$classname  = cookiebot_addons_cookieconsent_optout( $this->get_widget_cookie_types() );
			$inner_html = $this->get_widget_placeholder();
			echo '<div class="' . esc_attr( $classname ) . '">
					  ' . esc_html( $inner_html ) . '
					</div>';
		}
	}
}
