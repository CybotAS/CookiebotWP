<?php

namespace cybot\cookiebot\addons\controller\addons\jetpack\widget;

use function cybot\cookiebot\lib\cookiebot_addons_cookieconsent_optout;
use function cybot\cookiebot\lib\cookiebot_addons_manipulate_script;

class Goodreads_Jetpack_Widget extends Base_Jetpack_Widget {
	const LABEL               = 'Goodreads';
	const WIDGET_OPTION_NAME  = 'goodreads';
	const DEFAULT_PLACEHOLDER = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to watch goodreads.';

	private $transient_name;

	private $keywords;

	public function load_configuration() {
		if (
			// The widget is active
			is_active_widget( false, false, 'wpcom-goodreads', true ) &&
			// The widget is enabled in Prior consent
			$this->is_widget_enabled() &&
			// The visitor didn't check the required cookie types
			! $this->cookie_consent->are_cookie_states_accepted( $this->get_widget_cookie_types() )
		) {
			if ( $this->is_widget_placeholder_enabled() ) {
				add_action( 'jetpack_stats_extra', array( $this, 'cookie_consent_div' ), 10, 2 );
			}

			$this->transient_name = 'wpcom-goodreads';

			$this->keywords = array( 'www.goodreads.com' => $this->get_widget_cookie_types() );
			$this->block_javascript_file();
			$this->output_manipulated();
		}
	}


	/**
	 * Add message to go to consent settings when marketing consent is not accepted
	 *
	 * @since 1.2.0
	 */
	private function block_javascript_file() {
		add_action( 'dynamic_sidebar', array( $this, 'display_div_message_to_go_to_consent_settings' ), 10, 1 );
	}

	/**
	 * Show a message to go to consent settings
	 *
	 * @param $widget   array
	 *
	 * @since 1.2.0
	 */
	public function display_div_message_to_go_to_consent_settings( $widget ) {
		if ( isset( $widget['callback'][0]->id_base ) && $widget['callback'][0]->id_base === 'wpcom-goodreads' ) {
			ob_start( array( $this, 'manipulate_script' ) );
		}
	}

	/**
	 * Return widget output after dynamic sidebar is fully processed
	 *
	 * @since 1.2.0
	 */
	private function output_manipulated() {
		add_action(
			'dynamic_sidebar_after',
			function () {
				ob_end_flush();
			}
		);
	}

	/**
	 * Custom manipulation of the script
	 *
	 * @param $buffer
	 *
	 * @return mixed|null|string|string[]
	 *
	 * @since 1.2.0
	 */
	public function manipulate_script( $buffer ) {
		/**
		 * Get wp head scripts from the cache
		 */
		$updated_scripts = get_transient( $this->transient_name );

		/**
		 * If cache is not set then build it
		 */
		if ( $updated_scripts === false ) {
			$updated_scripts = cookiebot_addons_manipulate_script( $buffer, $this->keywords );

			/**
			 * Set cache for 15 minutes
			 */
			set_transient( $this->transient_name, $updated_scripts, 60 * 15 );
		}

		return $updated_scripts;
	}

	/**
	 * @param string $view
	 * @param string $widget
	 */
	public function cookie_consent_div( $view, $widget ) {
		if (
			( $widget === 'goodreads' && $view === 'widget_view' ) &&
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
