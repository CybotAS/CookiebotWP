<?php

namespace cookiebot_addons_framework\controller\addons\jetpack;

class Goodreads_Widget {

	protected $widget_id;

	protected $transient_name;

	protected $keywords;

	/**
	 * Goodreads_Widget constructor.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		if ( is_active_widget( false, false, 'wpcom-goodreads', true ) ) {
			$this->transient_name = 'wpcom-goodreads';

			$this->keywords = array( 'www.goodreads.com' );
			$this->block_javascript_file();
			$this->output_manipulated();
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

	public function output_manipulated() {
		add_action( 'dynamic_sidebar_after', function ( $index ) {
			ob_end_flush();
		} );
	}

	/**
	 * TODO refactor
	 * @param $buffer
	 *
	 * @return mixed|null|string|string[]
	 */
	public function manipulate_script($buffer) {
		/**
		 * Get wp head scripts from the cache
		 */
		$updated_scripts = get_transient( $this->transient_name );

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
				 * Check if the script contains the keywords, checks keywords one by one
				 *
				 * If one match, then the rest of the keywords will be skipped.
				 **/
				foreach ( $this->keywords as $needle ) {
					/**
					 * The script contains the needle
					 **/
					if ( strpos( $data, $needle ) !== false ) {
						$data = preg_replace( '/\<script/', '<script type="text/plain" data-cookieconsent="marketing"', $data );

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
			set_transient( $this->transient_name, $updated_scripts, 60 * 15 );
		}

		return $updated_scripts;
	}
}