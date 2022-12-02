<?php

namespace cybot\cookiebot\addons\controller\addons\jetpack\widget;

use function cybot\cookiebot\lib\cookiebot_addons_cookieconsent_optout;
use function cybot\cookiebot\lib\cookiebot_addons_remove_class_action;

class Visitor_Cookies_Jetpack_Widget extends Base_Jetpack_Widget {
	const LABEL               = 'Visitor cookies';
	const WIDGET_OPTION_NAME  = 'visitor_cookies';
	const DEFAULT_PLACEHOLDER = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to watch this video.';

	public function load_configuration() {
		/**
		 * When consent is not given
		 * Then disable comment cookies
		 *
		 * @TODO is_cookie_state_accepted only accepts a string but an array is given, what should be the correct behaviour?
		 */
		if ( ! $this->cookie_consent->is_cookie_state_accepted( $this->get_widget_cookie_types() ) ) {
			$this->disable_comment_cookies();
			$this->do_not_save_mobile_or_web_view();
			$this->disable_eu_cookie_law();
			$this->disable_comment_subscriptions();
		}
	}

	/**
	 * Set comment subscribe cookie time to zero, so it expires.
	 *
	 * @since 1.2.0
	 */
	private function disable_comment_subscriptions() {
		add_filter(
			'comment_cookie_lifetime',
			function () {
				return 0;
			},
			10,
			0
		);
	}

	/**
	 * Disable eu cookie law script
	 *
	 * @since 1.2.0
	 */
	private function disable_eu_cookie_law() {
		$this->script_loader_tag->add_tag( 'eu-cookie-law-script', array( 'preferences' ) );
	}

	/**
	 * Disable cookie comments
	 *
	 * Cookies:
	 * - comment_author_{HASH}
	 * - comment_author_email_{HASH}
	 * - comment_author_url_{HASH}
	 *
	 * @since 1.2.0
	 */
	private function disable_comment_cookies() {
		/**
		 * Remove action comment cookies in jetpack
		 *
		 * we have to remove this action, because it does manually add the cookie.
		 */
		cookiebot_addons_remove_class_action( 'comment_post', 'Highlander_Comments_Base', 'set_comment_cookies' );

		/**
		 * Remove action comment cookies in WordPress core
		 *
		 * we have to remove this action, because it does manually add the cookie.
		 */
		if ( has_action( 'set_comment_cookies', 'wp_set_comment_cookies' ) ) {
			remove_action( 'set_comment_cookies', 'wp_set_comment_cookies' );
		}
	}

	/**
	 * Doesn't save the visitor wish in cookie
	 *
	 * Cookie:
	 * - akm_mobile
	 *
	 * @since 1.2.0
	 */
	private function do_not_save_mobile_or_web_view() {
		/**
		 * we have to remove this action, because it does manually add the cookie.
		 */
		if ( has_action( 'init', 'jetpack_mobile_request_handler' ) ) {
			remove_action( 'init', 'jetpack_mobile_request_handler' );
		}

		/**
		 * Show message to accept preferences consent to save
		 */
		if ( $this->is_widget_placeholder_enabled() ) {
			add_action( 'wp_footer', array( $this, 'view_accept_preferences_consent' ) );
		}
	}

	/**
	 * Display message to enable
	 *
	 * @since 1.2.0
	 */
	public function view_accept_preferences_consent() {
		$classname  = cookiebot_addons_cookieconsent_optout( $this->get_widget_cookie_types() );
		$inner_html = $this->get_default_placeholder();
		echo '<div class="' . esc_attr( $classname ) . '">
						  ' . esc_html( $inner_html ) . '
						</div>';
	}

}
