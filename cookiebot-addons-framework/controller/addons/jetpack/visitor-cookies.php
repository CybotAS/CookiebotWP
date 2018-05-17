<?php

namespace cookiebot_addons_framework\controller\addons\jetpack;

class Visitor_Cookies {

	public function __construct() {
		/**
		 * When preferences consent is not given
		 * Then disable comment cookies
		 */
		if ( ! cookiebot_is_cookie_state_accepted( 'preferences' ) ) {
			$this->disable_comment_cookies();
			$this->do_not_save_mobile_or_web_view();
			$this->disable_eu_cookie_law();
			$this->disable_comment_subscriptions();
		}
	}

	/**
	 * Set comment subscribe cookie time to zero so it expires.
	 *
	 * @since 1.2.0
	 */
	protected function disable_comment_subscriptions() {
		add_filter( 'comment_cookie_lifetime', function ( $time ) {
			return 0;
		} );
	}

	/**
	 * Disable eu cookie law script
	 *
	 * @since 1.2.0
	 */
	protected function disable_eu_cookie_law() {
		cookiebot_script_loader_tag( 'eu-cookie-law-script', 'preferences' );
	}

	/**
	 * Disable cookie comments
	 *
	 * Cookies:
	 * - comment_author_{HASH}
	 * - comment_author_email_{HASH}
	 * - comment_author_url_{HASH}
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

	/**
	 * Doesn't save the visitor wish in cookie
	 *
	 * Cookie:
	 * - akm_mobile
	 *
	 * @since 1.2.0
	 */
	protected function do_not_save_mobile_or_web_view() {
		remove_action( 'init', 'jetpack_mobile_request_handler' );

		/**
		 * Show message to accept preferences consent to save
		 */
		add_action( 'wp_mobile_theme_footer', array( $this, 'view_accept_preferences_consent' ) );
	}

	/**
	 * Display message to enable
	 *
	 * @since 1.2.0
	 */
	public function view_accept_preferences_consent() {
		echo '<div class="cookieconsent-optout-preferences">
						  ' . __( 'Please <a href="javascript:Cookiebot.renew()">accept preferences-cookies</a> to switch to full page.', 'cookiebot_addons' ) . '
						</div>';
	}
}