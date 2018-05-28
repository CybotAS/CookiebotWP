<?php

namespace cookiebot_addons_framework\controller\addons\jetpack;

use cookiebot_addons_framework\lib\script_loader_tag\Script_Loader_Tag_Interface;

class Googleplus_Badge_Widget {

	/**
	 * @var Script_Loader_Tag_Interface
	 */
	protected $script_loader_tag;

	/**
	 * Googleplus_Badge_Widget constructor.
	 *
	 * @param   $script_loader_tag  Script_Loader_Tag_Interface
	 *
	 * @since 1.2.0
	 */
	public function __construct( Script_Loader_Tag_Interface $script_loader_tag ) {

		if ( is_active_widget( false, false, 'googleplus-badge', true ) ) {
			$this->script_loader_tag = $script_loader_tag;

			$this->disable_javascript_file();
			$this->div_to_enable_marketing_consent();
		}
	}

	/**
	 * Disable javascript file if marketing consent is not given
	 *
	 * @since 1.2.0
	 */
	protected function disable_javascript_file() {
		$this->script_loader_tag->add_tag( 'googleplus-widget', array('marketing') );
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
	public function display_div_message_to_go_to_consent_settings( $view, $widget ) {
		if ( $widget == 'googleplus-badge' && $view == 'widget_view' ) {
			echo '<div class="cookieconsent-optout-marketing">
						  ' . __( 'Please <a href="javascript:Cookiebot.renew()">accept marketing-cookies</a> to watch this googleplus badge.', 'cookiebot_addons' ) . '
						</div>';
		}
	}
}