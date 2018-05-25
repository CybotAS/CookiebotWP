<?php

namespace cookiebot_addons_framework\controller\addons\jetpack;

use cookiebot_addons_framework\lib\Cookiebot_Cookie_Consent_Interface;

class Internet_Defense_league_Widget {

	/**
	 * @var Cookiebot_Cookie_Consent
	 */
	private $cookie_consent;

	/**
	 * Internet_Defense_league_Widget constructor.
	 *
	 * @param $cookie_consent \cookiebot_addons_framework\lib\Cookiebot_Cookie_Consent_Interface
	 *
	 * @since 1.2.0
	 */
	public function __construct( Cookiebot_Cookie_Consent_Interface $cookie_consent ) {
		if ( is_active_widget( false, false, 'internet_defense_league_widget', true ) ) {
			$this->cookie_consent = $cookie_consent;

			add_action( 'wp_footer', function () {
				/**
				 * Remove wp_footer script when the cookieconsent for marketing is not given
				 *
				 * @since 1.2.0
				 */
				if ( ! $this->cookie_consent->is_cookie_state_accepted( 'marketing' ) ) {
					cookiebot_remove_class_action( 'wp_footer', 'Jetpack_Internet_Defense_League_Widget', 'footer_script' );
				}
			}, 9 );
		}
	}
}