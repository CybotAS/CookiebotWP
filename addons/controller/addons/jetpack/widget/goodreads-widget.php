<?php

namespace cookiebot_addons_framework\controller\addons\jetpack\widget;

use cookiebot_addons_framework\lib\buffer\Buffer_Output_Interface;

class Goodreads_Widget {

	protected $widget_id;

	protected $transient_name;

	protected $keywords;

	/**
	 * Goodreads_Widget constructor.
	 *
	 * @param   $widget_enabled boolean                 true if the widget is activated
	 * @param   $cookie_types   array                   List of supported cookie types
	 * @param   $placeholder_enabled   boolean          true - display placeholder div
	 * @param   $buffer_output  Buffer_Output_Interface Buffer class to edit the output
	 *
	 * @since 1.2.0
	 */
	public function __construct( $widget_enabled, $cookie_types, $placeholder_enabled, Buffer_Output_Interface $buffer_output ) {
		if ( is_active_widget( false, false, 'wpcom-goodreads', true ) ) {
			if ( $widget_enabled ) {
				$this->transient_name = 'wpcom-goodreads';

				$this->keywords = array( 'www.goodreads.com' => $cookie_types );
				$this->block_javascript_file();
				$this->output_manipulated();
			}
		}
	}

	/**
	 * Add message to go to consent settings when marketing consent is not accepted
	 *
	 * @since 1.2.0
	 */
	protected function block_javascript_file() {
		add_action( 'dynamic_sidebar', array( $this, 'display_div_message_to_go_to_consent_settings' ), 10, 1 );
	}

	/**
	 * Show a messsage to go to consent settings
	 *
	 * @param $widget   string
	 *
	 * @since 1.2.0
	 */
	public function display_div_message_to_go_to_consent_settings( $widget ) {
		$callback = $widget['callback'][0];

		if ( $callback->id_base == 'wpcom-goodreads' ) {
			ob_start( array( $this, 'manipulate_script' ) );
		}
	}

	/**
	 * Return widget output after dynamic sidebar is fully processed
	 *
	 * @since 1.2.0
	 */
	public function output_manipulated() {
		add_action( 'dynamic_sidebar_after', function ( $index ) {
			ob_end_flush();
		} );
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

			$updated_scripts = cookiebot_manipulate_script( $buffer, $this->keywords );

			/**
			 * Set cache for 15 minutes
			 */
			set_transient( $this->transient_name, $updated_scripts, 60 * 15 );
		}

		return $updated_scripts;
	}
}