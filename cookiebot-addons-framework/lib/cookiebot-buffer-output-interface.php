<?php

namespace cookiebot_addons_framework\lib;

Interface Cookiebot_Buffer_Output_Interface {

	/**
	 * Cookiebot_Buffer_Output_Interface constructor.
	 *
	 * @param $tag  string  Action hook name
	 * @param $priority integer Action hook priority
	 *
	 * @since 1.0.0
	 */
	public function __construct( $tag, $priority );

	/**
	 * Start buffering
	 *
	 * @since 1.0.0
	 */
	public function cookiebot_start_buffer();

	/**
	 * End buffer and return manipulated output
	 *
	 * @since 1.0.0
	 */
	public function cookiebot_stop_buffer();

	/**
	 * Manipulate the output and add scritp attributes if it does match the criterion
	 *
	 * @param $buffer   string  Catched output
	 *
	 * @return mixed    string  Manipulated Output
	 *
	 * @since 1.0.0
	 */
	public function manipulate_script( $buffer );
}