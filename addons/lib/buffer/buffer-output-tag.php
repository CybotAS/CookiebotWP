<?php

namespace cookiebot_addons\lib\buffer;

class Buffer_Output_Tag implements Buffer_Output_Tag_Interface {

	/**
	 * Hook priority
	 *
	 * @var integer
	 *
	 * @since 1.1.0
	 */
	public $priority;

	/**
	 * Hook tag name
	 *
	 * @var string
	 *
	 * @since 1.2.0
	 */
	public $tag;

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
	 *
	 * Use transient cache
	 *
	 * @var boolean
	 *
	 * @since 1.2.0
	 */
	private	$use_cache;

	/**
	 * Cookiebot_Buffer_Output_Tag constructor.
	 *
	 * @param $tag
	 * @param $priority
	 * @param array $keywords
	 * @param boolean $use_cache
	 *
	 * @since 1.2.0
	 */
	public function __construct( $tag, $priority, $keywords = array(), $use_cache = true ) {
		$this->tag      = $tag;
		$this->priority = $priority;
		$this->keywords = $keywords;

		$this->transient_name = "cookiebot_output_buffer_{$tag}_{$priority}";

		$this->set_use_cache($use_cache);
	}

	/**
	 * Merges new keywords in existence keywords variable
	 *
	 * @param $keywords
	 *
	 * @since 1.2.0
	 */
	public function merge_keywords( $keywords ) {
		$this->keywords = array_merge( $this->keywords, $keywords );
	}

	/**
	 * Set use cache
	 *
	 * @param $use_cache
	 */
	public function set_use_cache($use_cache) {
		$this->use_cache = $use_cache;
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
		if( $this->use_cache ) {
			$updated_scripts = get_transient( $this->transient_name );
		}

		/**
		 * If cache is not set then build it
		 */
		if ( !$this->use_cache || $updated_scripts === false ) {
			/**
			 * Get all scripts and add cookieconsent if it does match with the criterion
			 */
			$updated_scripts = cookiebot_addons_manipulate_script( $buffer, $this->keywords );

			if( $this->use_cache ) {
				/**
				 * Set cache for 15 minutes
				 */
				set_transient( $this->transient_name, $updated_scripts, 60 * 15 );
			}
		}

		return $updated_scripts;
	}
}
