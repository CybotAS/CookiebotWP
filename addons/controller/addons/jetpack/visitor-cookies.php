<?php

namespace cookiebot_addons_framework\controller\addons\jetpack;

use cookiebot_addons_framework\lib\Cookie_Consent_Interface;
use cookiebot_addons_framework\lib\script_loader_tag\Script_Loader_Tag_Interface;

class Visitor_Cookies {

	/**
	 * @var Script_Loader_Tag_Interface
	 */
	private $script_loader_tag;

	/**
	 * @var Cookie_Consent_Interface
	 */
	private $cookie_consent;

	/**
	 * Visitor_Cookies constructor.
	 *
	 * @param Script_Loader_Tag_Interface $script_loader_tag
	 * @param Cookie_Consent_Interface $cookie_consent
	 *
	 * @since 1.2.0
	 */
	public function __construct( Script_Loader_Tag_Interface $script_loader_tag, Cookie_Consent_Interface $cookie_consent ) {
		/**
		 * When preferences consent is not given
		 * Then disable comment cookies
		 */
		if ( ! $cookie_consent->is_cookie_state_accepted( 'preferences' ) ) {
			$this->script_loader_tag = $script_loader_tag;

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
		$this->script_loader_tag->add_tag( 'eu-cookie-law-script', array( 'preferences' ) );
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
	protected function do_not_save_mobile_or_web_view() {
		if ( has_action( 'init', 'jetpack_mobile_request_handler' ) ) {
			remove_action( 'init', 'jetpack_mobile_request_handler' );
		}

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