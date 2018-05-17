<?php

namespace cookiebot_addons_framework\controller\addons\jetpack;

class Visitor_Cookies {

	public function __construct() {
		/**
		 * When preferences consent is not given
		 * Then disable comment cookies
		 */
		if( !cookiebot_is_cookie_state_accepted('preferences') ) {
			$this->disable_comment_cookies();
		}
	}

	/**
	 * Disable cookie comments
	 *
	 * @since 1.2.0
	 */
	protected function disable_comment_cookies() {
		/**
		 * Remove action comment cookies in jetpack
		 */
		cookiebot_remove_class_action( 'comment_post', 'Highlander_Comments_Base', 'set_comment_cookies' );

		/**
		 * Remove action comment cookies in wordpress core
		 */
		remove_action( 'set_comment_cookies', 'wp_set_comment_cookies' );
	}
}