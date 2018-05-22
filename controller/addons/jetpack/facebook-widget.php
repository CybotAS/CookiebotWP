<?php

namespace cookiebot_addons_framework\controller\addons\jetpack;

/**
 * This class is used to add cookiebot consent to facebook widget
 *
 * @since 1.2.0
 */
class Facebook_Widget {

	/**
	 * Facebook_Widget constructor.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
		if( is_active_widget(false, false, 'facebook-likebox', true) ) {
			$this->add_consent_attribute_to_facebook_embed_javascript();
			$this->add_cookie_consent_div();
		}
	}

	/**
	 * Tag external Facebook javascript file with cookiebot consent.
	 *
	 * @since 1.2.0
	 */
	protected function add_consent_attribute_to_facebook_embed_javascript() {
		cookiebot_script_loader_tag( 'jetpack-facebook-embed', 'marketing' );
	}

	/**
	 * Add div to re-check cookiebot settings
	 *
	 * @since 1.2.0
	 */
	protected function add_cookie_consent_div() {
		add_action( 'jetpack_stats_extra', array( $this, 'cookie_consent_div' ), 10, 2 );
	}

	/**
	 * Output link to Cookiebot settings
	 *
	 * @param $view     string
	 * @param $widget   string
	 *
	 * @since 1.2.0
	 */
	public function cookie_consent_div( $view, $widget ) {
		if ( $widget == 'facebook-likebox' && $view == 'widget_view' ) {
			echo '<div class="cookieconsent-optout-marketing">
						  ' . __( 'Please <a href="javascript:Cookiebot.renew()">accept marketing-cookies</a> to watch this facebook page.', 'cookiebot_addons' ) . '
						</div>';
		}
	}
}