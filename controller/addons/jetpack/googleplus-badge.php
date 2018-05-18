<?php

namespace cookiebot_addons_framework\controller\addons\jetpack;

class Googleplus_Badge {

	/**
	 * Googleplus_Badge constructor.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		$this->disable_javascript_file();
		$this->div_to_enable_marketing_consent();
	}

	/**
	 * Disable javascript file if marketing consent is not given
	 *
	 * @since 1.2.0
	 */
	protected function disable_javascript_file() {
		cookiebot_script_loader_tag( 'googleplus-widget', 'marketing' );
	}

	/**
	 * Add message to go to consent settings when marketing consent is not accepted
	 *
	 * @since 1.2.0
	 */
	protected function div_to_enable_marketing_consent() {
		add_action( 'jetpack_stats_extra', array( $this, 'display_div_message_to_go_to_consent_settings' ), 10, 2 );
	}

	/**
	 * Show a messsage to go to consent settings
	 *
	 * @param $view     string
	 * @param $widget   string
	 *
	 * @since 1.2.0
	 */
	public function display_div_message_to_go_to_consent_settings($view, $widget) {
		if ( $widget == 'googleplus-badge' && $view == 'widget_view' ) {
			echo '<div class="cookieconsent-optout-marketing">
						  ' . __( 'Please <a href="javascript:Cookiebot.renew()">accept marketing-cookies</a> to watch this googleplus badge.', 'cookiebot_addons' ) . '
						</div>';
		}
	}
}