<?php

namespace cybot\cookiebot\lib;

use cybot\cookiebot\widgets\Cookiebot_Declaration_Widget;
use cybot\cookiebot\widgets\Dashboard_Widget_Cookiebot_Status;

class Widgets {
	public function register_hooks() {
		// Loading widgets
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
		( new Dashboard_Widget_Cookiebot_Status() )->register_hooks();
	}

	public function register_widgets() {
		register_widget( Cookiebot_Declaration_Widget::class );
	}
}
