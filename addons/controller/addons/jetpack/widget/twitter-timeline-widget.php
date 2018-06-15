<?php

namespace cookiebot_addons_framework\controller\addons\jetpack\widget;

use cookiebot_addons_framework\lib\script_loader_tag\Script_Loader_Tag_Interface;

class Twitter_Timeline_Widget {

	/**
	 * @var array   list of supported cookie types
	 *
	 * @since 1.3.0
	 */
	protected $cookie_types;

	/**
	 * @var Script_Loader_Tag_Interface
	 */
	private $script_loader_tag;

	/**
	 * Twitter_Timeline_Widget constructor.
	 *
	 * @param   $widget_enabled boolean     true if the widget is activated
	 * @param   $cookie_types   array       List of supported cookie types
	 * @param   $placeholder_enabled   boolean      true - display placeholder div
	 * @param   $script_loader_tag  Script_Loader_Tag_Interface
	 *
	 * @since 1.2.0
	 */
	public function __construct( $widget_enabled, $cookie_types, $placeholder_enabled, Script_Loader_Tag_Interface $script_loader_tag ) {
		if ( is_active_widget( false, false, 'twitter_timeline', true ) ) {
			if ( $widget_enabled ) {
				$this->cookie_types      = $cookie_types;
				$this->script_loader_tag = $script_loader_tag;

				$this->disable_javascript_file();

				if ( $placeholder_enabled ) {
					$this->div_to_enable_marketing_consent();
				}
			}

		}
	}

	/**
	 * Disable javascript file if marketing consent is not given
	 *
	 * @since 1.2.0
	 */
	protected function disable_javascript_file() {
		$this->script_loader_tag->add_tag( 'jetpack-twitter-timeline', $this->cookie_types );
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
		if ( $widget == 'twitter_timeline' && $view == 'widget_view' ) {
			if ( is_array( $this->cookie_types ) && count( $this->cookie_types ) > 0 ) {
				echo '<div class="cookieconsent-optout-' . cookiebot_get_one_cookie_type( $this->cookie_types ) . '">
						  ' . sprintf( __( 'Please <a href="javascript:Cookiebot.renew()">accept %s cookies</a> to watch the twitter timeline.', 'cookiebot_addons' ), cookiebot_output_cookie_types( $this->cookie_types ) ) . '
						</div>';
			}
		}
	}
}