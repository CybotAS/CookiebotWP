<?php

namespace cybot\cookiebot\addons\controller\addons\jetpack\widget;

use function cybot\cookiebot\lib\cookiebot_addons_cookieconsent_optout;

class Internet_Defense_League_Jetpack_Widget extends Base_Jetpack_Widget {

	const LABEL               = 'Internet defense league';
	const WIDGET_OPTION_NAME  = 'internet_defense_league';
	const DEFAULT_PLACEHOLDER = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable internet defense league.';

	public function load_configuration() {
		if (
			// The widget is active
			is_active_widget( false, false, 'internet_defense_league_widget', true ) &&
			// The widget is enabled in Prior consent
			$this->is_widget_enabled() &&
			// The visitor didn't check the required cookie types
			! $this->cookie_consent->are_cookie_states_accepted( $this->get_widget_cookie_types() )
		) {
			/**
			 * Remove wp_footer script when the cookieconsent for marketing is not given
			 *
			 * @since 1.2.0
			 */
			$this->buffer_output->add_tag(
				'wp_footer',
				10,
				array(
					'window._idl' => $this->get_widget_cookie_types(),
				),
				false
			);

			/**
			 * Display placeholder if allowed in the backend settings
			 */
			if ( $this->is_widget_placeholder_enabled() ) {
				add_action( 'jetpack_stats_extra', array( $this, 'cookie_consent_div' ), 10, 2 );
			}
		}
	}

	/**
	 * Show consent message when the consent is not given.
	 *
	 * @param $view     string
	 * @param $widget   string
	 *
	 * @since 1.6.0
	 */
	public function cookie_consent_div( $view, $widget ) {
		if (
			( $widget === 'internet_defense_league' && $view === 'widget_view' ) &&
			( is_array( $this->get_widget_cookie_types() ) && count( $this->get_widget_cookie_types() ) > 0 )
		) {
			$classname  = cookiebot_addons_cookieconsent_optout( $this->get_widget_cookie_types() );
			$inner_html = $this->get_widget_placeholder();
			echo '<div class="' . esc_attr( $classname ) . '">
					  ' . esc_html( $inner_html ) . '
					</div>';
		}
	}
}
