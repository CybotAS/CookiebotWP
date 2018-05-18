<?php

namespace cookiebot_addons_framework\controller\addons\jetpack;

class Internet_Defense_league_Widget {

	/**
	 * Internet_Defense_league constructor.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		add_action( 'wp_footer', function () {
			/**
			 * Remove wp_footer script when the cookieconsent for marketing is not given
			 *
			 * @since 1.2.0
			 */
			if ( ! cookiebot_is_cookie_state_accepted( 'marketing' ) ) {
				cookiebot_remove_class_action( 'wp_footer', 'Jetpack_Internet_Defense_League_Widget', 'footer_script' );
			}
		}, 9 );
	}
}