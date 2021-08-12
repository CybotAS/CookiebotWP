<?php

namespace cybot\cookiebot\addons\controller\addons\jetpack\widget;

interface Jetpack_Widget_Interface {

	public function get_label();

	public function get_widget_option_name();

	public function get_widget_cookie_types();

	/**
	 * @return bool
	 */
	public function is_widget_enabled();

	public function is_widget_placeholder_enabled();

	public function widget_has_placeholder();

	public function get_widget_placeholder();

	/**
	 * @return array|false
	 */
	public function get_widget_placeholders();

	/**
	 * @return string
	 */
	public function get_widget_default_placeholder();

	public function load_configuration();

	/**
	 * @param $view
	 * @param $widget
	 *
	 * @return void
	 */
	public function cookie_consent_div( $view, $widget );

	public function get_extra_information();
}
