<?php

namespace cookiebot_addons\lib\buffer;

Interface Buffer_Output_Tag_Interface {

	/**
	 * Buffer_Output_Tag_Interface constructor.
	 *
	 * @param $tag
	 * @param $priority
	 * @param array $keywords
	 *
	 * @version 1.3.0
	 * @since 1.1.0
	 */
	public function __construct( $tag, $priority, $keywords = array() );

	/**
	 * Start buffering
	 *
	 * @since 1.1.0
	 */
	public function cookiebot_start_buffer();

	/**
	 * End buffer and return manipulated output
	 *
	 * @since 1.1.0
	 */
	public function cookiebot_stop_buffer();

	/**
	 * Manipulate the output and add scritp attributes if it does match the criterion
	 *
	 * @param $buffer   string  Catched output
	 *
	 * @return mixed    string  Manipulated Output
	 *
	 * @since 1.1.0
	 */
	public function manipulate_script( $buffer );
}
