<?php

namespace cookiebot_addons_framework\lib;

class Cookiebot_Buffer_Output Implements Cookiebot_Buffer_Output_Interface {

	/**
	 * Hook priority
	 *
	 * @var integer
	 *
	 * @since 1.1.0
	 */
	private $priority;

	/**
	 * Hook tag name
	 *
	 * @var string
	 *
	 * @since 1.1.0
	 */
	private $tag;

	/**
	 * Keywords to allow in the scripts
	 *
	 * @var array array
	 *
	 * @since 1.2.0
	 */
	private $keywords;

	/**
	 * Transient unique name
	 *
	 * @var string
	 *
	 * @since 1.1.0
	 */
	private $transient_name;

	/**
	 * Cookiebot_Buffer_Output constructor.
	 *
	 * @param $tag  string  Action hook name
	 * @param $priority string  Action hook priority
	 * @param $keywords array   Keywords to look for in the scripts
	 *
	 * @since 1.1.0
	 */
	public function __construct( $tag, $priority, $keywords = array() ) {
		$this->priority = $priority;
		$this->tag      = $tag;
		$this->keywords = $keywords;

		$this->transient_name = 'cookiebot_' . $this->tag . '_' . $this->priority;

		add_action( $tag, array( $this, 'cookiebot_start_buffer' ), $this->priority - 1 );
		add_action( $tag, array( $this, 'cookiebot_stop_buffer' ), $this->priority + 1 );
	}

	/**
	 * Start reading the buffer/output
	 *
	 * @since 1.1.0
	 */
	public function cookiebot_start_buffer() {
		ob_start( array( $this, 'manipulate_script' ) );
	}

	/**
	 * Stop reading the output and output buffered data through manipulate script filter.
	 *
	 * @since 1.1.0
	 */
	public function cookiebot_stop_buffer() {
		ob_end_flush();
	}

	/**
	 * Manipulate google analytic scripts to cookiebot and return it back
	 *
	 * @param $buffer
	 *
	 * @return null|string|string[]
	 *
	 * @since 1.1.0
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
				 * Keywords to look for (default)
				 **/
				if( count( $this->keywords ) == 0 ) {
					$this->keywords = array( 'gtag', 'google-analytics', '_gaq', 'www.googletagmanager.com/gtag/js?id=' );
				}

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
			set_transient( $this->transient_name, $updated_scripts, 60 * 15 );
		}

		return $updated_scripts;
	}
}