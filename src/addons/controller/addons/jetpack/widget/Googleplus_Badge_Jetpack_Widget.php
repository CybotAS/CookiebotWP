<?php

namespace cybot\cookiebot\addons\controller\addons\jetpack\widget;

use function cybot\cookiebot\lib\cookiebot_addons_cookieconsent_optout;

class Googleplus_Badge_Jetpack_Widget extends Base_Jetpack_Widget {

	const LABEL               = 'Google Plus Badge';
	const WIDGET_OPTION_NAME  = 'googleplus_badge';
	const DEFAULT_PLACEHOLDER = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable google plus badge.';

	/**
	 * @var array   list of supported cookie types
	 *
	 * @since 1.3.0
	 */
	private $cookie_types = array();

	/**
	 * Load configuration
	 */
	public function load_configuration() {
		if (
			// The widget is active
			is_active_widget( false, false, 'googleplus-badge', true ) &&
			// The widget is enabled in Prior consent
			$this->is_widget_enabled() &&
			// The visitor didn't check the required cookie types
			! $this->cookie_consent->are_cookie_states_accepted( $this->get_widget_cookie_types() )
		) {
			$this->cookie_types = $this->get_widget_cookie_types();

			$this->disable_javascript_file();

			if ( $this->is_widget_placeholder_enabled() ) {
				add_action( 'jetpack_stats_extra', array( $this, 'display_div_message_to_go_to_consent_settings' ), 10, 2 );
			}
		}
	}

	/**
	 * Disable javascript file if marketing consent is not given
	 *
	 * @since 1.2.0
	 */
	private function disable_javascript_file() {
		$this->script_loader_tag->add_tag( 'googleplus-widget', $this->cookie_types );
	}

	/**
	 * Show a message to go to consent settings
	 *
	 * @param $view     string
	 * @param $widget   string
	 *
	 * @since 1.2.0
	 */
	public function display_div_message_to_go_to_consent_settings( $view, $widget ) {
		if (
			( $widget === 'googleplus-badge' && $view === 'widget_view' ) &&
			( is_array( $this->cookie_types ) && count( $this->cookie_types ) > 0 )
		) {
			$classname  = cookiebot_addons_cookieconsent_optout( $this->cookie_types );
			$inner_html = $this->get_widget_placeholder();
			echo '<div class="' . esc_attr( $classname ) . '">' . esc_html( $inner_html ) . '</div>';
		}
	}
}
