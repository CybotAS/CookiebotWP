<?php

namespace cookiebot_addons_framework\controller\addons\jetpack\widget;

use cookiebot_addons_framework\lib\script_loader_tag\Script_Loader_Tag_Interface;

/**
 * This class is used to add cookiebot consent to facebook widget
 *
 * @since 1.2.0
 */
class Facebook_Widget {

	/**
	 * @var array   list of supported cookie types
	 *
	 * @since 1.3.0
	 */
	protected $cookie_types;

	/**
	 * @var Script_Loader_Tag_Interface
	 *
	 * @since 1.2.0
	 */
	private $script_loader_tag;

	/**
	 * Facebook_Widget constructor.
	 *
	 * @param   $widget_enabled boolean     true if the widget is activated
	 * @param   $cookie_types   array       List of supported cookie types
	 * @param   $placeholder_enabled   boolean      true - display placeholder div
	 * @param   $script_loader_tag Script_Loader_Tag_Interface
	 *
	 * @version 1.3.0
	 * @since 1.2.0
	 */
	public function __construct( $widget_enabled, $cookie_types, $placeholder_enabled, Script_Loader_Tag_Interface $script_loader_tag ) {
		if ( is_active_widget( false, false, 'facebook-likebox', true ) ) {
			if ( $widget_enabled ) {
				$this->cookie_types      = $cookie_types;
				$this->script_loader_tag = $script_loader_tag;

				/**
				 * Manipulate script attribute
				 */
				$this->add_consent_attribute_to_facebook_embed_javascript();

				/**
				 * Display placeholder if allowed in the backend settings
				 */
				if ( $placeholder_enabled ) {
					$this->add_cookie_consent_div();
				}
			}
		}
	}

	/**
	 * Tag external Facebook javascript file with cookiebot consent.
	 *
	 * @since 1.2.0
	 */
	protected function add_consent_attribute_to_facebook_embed_javascript() {
		$this->script_loader_tag->add_tag( 'jetpack-facebook-embed', $this->cookie_types );
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
			if ( is_array( $this->cookie_types ) && count( $this->cookie_types ) > 0 ) {
				echo '<div class="cookieconsent-optout-' . cookiebot_get_one_cookie_type( $this->cookie_types ) . '">
						  ' . sprintf( __( 'Please <a href="javascript:Cookiebot.renew()">accept %s cookies</a> to watch this facebook page.', 'cookiebot_addons' ), cookiebot_output_cookie_types( $this->cookie_types ) ) . '
						</div>';
			}
		}
	}
}