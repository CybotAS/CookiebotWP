<?php

namespace cookiebot_addons\controller\addons\jetpack\widget;

use cookiebot_addons\lib\Settings_Service_Interface;
use cookiebot_addons\lib\script_loader_tag\Script_Loader_Tag_Interface;
use cookiebot_addons\lib\Cookie_Consent_Interface;
use cookiebot_addons\lib\buffer\Buffer_Output_Interface;

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
	 * @var Settings_Service_Interface
	 *
	 * @since 1.3.0
	 */
	protected $settings;

	/**
	 * @var Script_Loader_Tag_Interface
	 *
	 * @since 1.3.0
	 */
	protected $script_loader_tag;

	/**
	 * @var Cookie_Consent_Interface
	 *
	 * @since 1.3.0
	 */
	protected $cookie_consent;

	/**
	 * @var Buffer_Output_Interface
	 *
	 * @since 1.3.0
	 */
	protected $buffer_output;

	/**
	 * Option name for jetpack addon
	 *
	 * @var string
	 */
	public $widget_option;

	/**
	 * Facebook_Widget constructor.
	 *
	 * @param Settings_Service_Interface $settings
	 * @param Script_Loader_Tag_Interface $script_loader_tag
	 * @param Cookie_Consent_Interface $cookie_consent
	 * @param Buffer_Output_Interface $buffer_output
	 * @param string $widget_option
	 *
	 * @version 1.8.0
	 * @since 1.2.0
	 */
	public function __construct( Settings_Service_Interface $settings, Script_Loader_Tag_Interface $script_loader_tag, Cookie_Consent_Interface $cookie_consent, Buffer_Output_Interface $buffer_output, $widget_option ) {
		$this->settings          = $settings;
		$this->script_loader_tag = $script_loader_tag;
		$this->cookie_consent    = $cookie_consent;
		$this->buffer_output     = $buffer_output;
		$this->widget_option     = $widget_option;
	}

	public function load_configuration() {
		/**
		 * The widget is active
		 */
		if ( is_active_widget( false, false, 'facebook-likebox', true ) ) {
			/**
			 * The widget is enabled in Prior consent
			 */
			if ( $this->is_widget_enabled() ) {
				/**
				 * The visitor didn't check the required cookie types
				 */
				if ( ! $this->cookie_consent->are_cookie_states_accepted( $this->get_widget_cookie_types() ) ) {
					/**
					 * Manipulate script attribute
					 */
					$this->add_consent_attribute_to_facebook_embed_javascript();

					/**
					 * Display placeholder if allowed in the backend settings
					 */
					if ( $this->is_widget_placeholder_enabled() ) {
						add_action( 'jetpack_stats_extra', array( $this, 'cookie_consent_div' ), 10, 2 );
					}
				}
			}
		}
	}

	public function get_label() {
		return 'Facebook';
	}

	/**
	 * Returns widget option name
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_widget_option_name() {
		return 'facebook';
	}

	/**
	 * Returns cookie types for a widget
	 *
	 * @return mixed
	 *
	 * @since 1.8.0
	 */
	public function get_widget_cookie_types() {
		return $this->settings->get_widget_cookie_types( $this->widget_option, $this->get_widget_option_name() );
	}

	/**
	 * Checks if a widget is enabled
	 *
	 * @return mixed
	 *
	 * @since 1.8.0
	 */
	public function is_widget_enabled() {
		return $this->settings->is_widget_enabled( $this->widget_option, $this->get_widget_option_name() );
	}

	/**
	 * @return string
	 */
	public function get_default_placeholder() {
		return 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to see facebook widget.';
	}

	/**
	 * Checks if a widget placeholder is enabled
	 *
	 * @return boolean  true    If widget placeholder is checked
	 *                  false   If widget placeholder is not checked
	 *
	 * @since 1.8.0
	 */
	public function is_widget_placeholder_enabled() {
		return $this->settings->is_widget_placeholder_enabled( $this->widget_option, $this->get_widget_option_name() );
	}

	/**
	 * Checks if widget has existing placeholders
	 *
	 * @return mixed
	 *
	 * @since 1.8.0
	 */
	public function widget_has_placeholder() {
		return $this->settings->widget_has_placeholder( $this->widget_option, $this->get_widget_option_name() );
	}

	/**
	 * Returns all widget placeholders
	 *
	 * @return mixed
	 *
	 * @since 1.8.0
	 */
	public function get_widget_placeholders() {
		return $this->settings->get_widget_placeholders( $this->widget_option, $this->get_widget_option_name() );
	}

	/**
	 * returns widget placeholder
	 *
	 * @return mixed
	 *
	 * @since 1.8.0
	 */
	public function get_widget_placeholder() {
		return $this->settings->get_widget_placeholder( $this->widget_option, $this->get_widget_option_name(), $this->get_default_placeholder(), cookiebot_addons_output_cookie_types( $this->get_widget_cookie_types() ) );
	}


	/**
	 * Tag external Facebook javascript file with cookiebot consent.
	 *
	 * @since 1.2.0
	 */
	protected function add_consent_attribute_to_facebook_embed_javascript() {
		$this->script_loader_tag->add_tag( 'jetpack-facebook-embed', $this->get_widget_cookie_types() );
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
		if ( $widget == 'facebook-likebox' && $view == 'widget_view' ) {
			if ( is_array( $this->get_widget_cookie_types() ) && count( $this->get_widget_cookie_types() ) > 0 ) {
				echo '<div class="' . cookiebot_addons_cookieconsent_optout( $this->get_widget_cookie_types() ) . '">
						  ' . $this->get_widget_placeholder() . '
						</div>';
			}
		}
	}

	/**
	 * Adds extra information under the label
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_extra_information() {
		return '<p>' . esc_html__( 'Facebook widget.', 'cookiebot-addons' ) . '</p>';
	}

	/**
	 * Placeholder helper overlay in the settings page.
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_placeholder_helper() {
		return '<p>Merge tags you can use in the placeholder text:</p><ul><li>%cookie_types - Lists required cookie types</li><li>[renew_consent]text[/renew_consent] - link to display cookie settings in frontend</li></ul>';
	}

}
