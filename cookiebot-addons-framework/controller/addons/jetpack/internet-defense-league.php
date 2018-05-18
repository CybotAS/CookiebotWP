<?php

namespace cookiebot_addons_framework\controller\addons\jetpack;

class Internet_Defense_league {

	public function __construct() {
		add_action( 'wp_footer', function () {
			cookiebot_remove_class_action( 'wp_footer', 'Jetpack_Internet_Defense_League_Widget', 'footer_script' );
		}, 10 );

		//cookiebot_buffer_output( 'wp_footer', '10' );

	}
}