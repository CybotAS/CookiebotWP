<?php

namespace cookiebot_addons_framework\controller\addons\jetpack;

use cookiebot_addons_framework\lib\Cookiebot_Script_Loader_Tag;

class Twitter_Timeline_Widget {

	/**
	 * @var Cookiebot_Script_Loader_Tag
	 */
	private $script_loader_tag;

	/**
	 * Twitter_Timeline_Widget constructor.
	 *
	 * @param   $script_loader_tag  Cookiebot_Script_Loader_Tag
	 *
	 * @since 1.2.0
	 */
	public function __construct( Cookiebot_Script_Loader_Tag $script_loader_tag ) {
		if ( is_active_widget( false, false, 'twitter_timeline', true ) ) {
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
		$this->script_loader_tag->add_tag( 'jetpack-twitter-timeline', 'marketing' );
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
			echo '<div class="cookieconsent-optout-marketing">
						  ' . __( 'Please <a href="javascript:Cookiebot.renew()">accept marketing-cookies</a> to watch the twitter timeline.', 'cookiebot_addons' ) . '
						</div>';
		}
	}
}