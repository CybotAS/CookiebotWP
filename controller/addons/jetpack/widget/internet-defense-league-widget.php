<?php

namespace cookiebot_addons_framework\controller\addons\jetpack\widget;

use cookiebot_addons_framework\lib\Cookie_Consent_Interface;

class Internet_Defense_league_Widget {

	/**
	 * @var array   list of supported cookie types
	 *
	 * @since 1.3.0
	 */
	protected $cookie_types;

	/**
	 * @var Cookie_Consent_Interface
	 */
	private $cookie_consent;

	/**
	 * Internet_Defense_league_Widget constructor.
	 *
	 * @param   $widget_enabled boolean     true if the widget is activated
	 * @param   $cookie_types   array       List of supported cookie types
	 * @param   $placeholder_enabled   boolean      true - display placeholder div
	 * @param $cookie_consent \cookiebot_addons_framework\lib\Cookie_Consent_Interface
	 *
	 * @since 1.2.0
	 */
	public function __construct( $widget_enabled, $cookie_types, $placeholder_enabled, Cookie_Consent_Interface $cookie_consent ) {
		if ( is_active_widget( false, false, 'internet_defense_league_widget', true ) ) {

			if( $widget_enabled ) {
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
}