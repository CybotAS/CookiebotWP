<?php

namespace cookiebot_addons_framework\lib\buffer;

Interface Cookiebot_Buffer_Output_Tag_Interface {

	/**
	 * Cookiebot_Buffer_Output_Interface constructor.
	 *
	 * @since 1.1.0
	 */
	public function __construct();

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