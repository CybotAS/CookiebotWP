<?php

namespace cookiebot_addons\controller\addons\jetpack\widget;

Interface Jetpack_Widget_Interface {

	public function get_label();

	public function get_widget_option_name();

	public function get_widget_cookie_types();

	public function is_widget_enabled();

	public function is_widget_placeholder_enabled();

	public function widget_has_placeholder();

	public function get_widget_placeholder();

	public function get_widget_placeholders();
}
