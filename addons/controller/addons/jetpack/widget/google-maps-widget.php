<?php

namespace cookiebot_addons\controller\addons\jetpack\widget;

use cookiebot_addons\lib\buffer\Buffer_Output_Interface;
use cookiebot_addons\lib\Cookie_Consent_Interface;
use cookiebot_addons\lib\script_loader_tag\Script_Loader_Tag_Interface;
use cookiebot_addons\lib\Settings_Service_Interface;

class Google_Maps_Widget implements Jetpack_Widget_Interface {

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
	 * This class is used to support facebook page widget in jetpack
	 *
	 * Google_Maps_Widget constructor.
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
		if ( is_active_widget( false, false, 'widget_contact_info', true ) ) {
			/**
			 * The widget is enabled in Prior consent
			 */
			if ( $this->is_widget_enabled() ) {
				/**
				 * Cookie types are not selected
				 */
				if ( ! $this->cookie_consent->are_cookie_states_accepted( $this->get_widget_cookie_types() ) ) {
					$this->cookie_types = $this->get_widget_cookie_types();

					/**
					 * Replace attributes of the google maps widget iframe
					 */
					add_action( 'jetpack_contact_info_widget_start', array( $this, 'start_buffer' ) );
					add_action( 'jetpack_contact_info_widget_end', array( $this, 'stop_buffer' ) );

					if ( $this->is_widget_placeholder_enabled() ) {
						add_action( 'jetpack_stats_extra', array( $this, 'cookie_consent_div' ), 10, 2 );
					}
				}
			}
		}
	}

	public function get_label() {
		return 'Google Maps';
	}

	/**
	 * Returns widget option name
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_widget_option_name() {
		return 'google_maps';
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
		return 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable google maps.';
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
	 * Start catching the output
	 *
	 * @since 1.2.0
	 */
	public function start_buffer() {
		ob_start( array( $this, 'manipulate_iframe' ) );
	}

	/**
	 * Clear the buffer
	 *
	 * @since 1.2.0
	 */
	public function stop_buffer() {
		ob_end_flush();
	}

	/**
	 * Return manipulated output with cookieconsent attribute
	 *
	 * @param $buffer
	 *
	 * @return mixed|null|string|string[]
	 *
	 * @since 1.2.0
	 */
	public function manipulate_iframe( $buffer ) {
		/**
		 * Get wp head scripts from the cache
		 */
		$updated_scripts = get_transient( 'jetpack_google_maps_widget' );

		/**
		 * If cache is not set then build it
		 */
		if ( $updated_scripts === false ) {
			/**
			 * Pattern to get all iframes
			 */
			$pattern = "/\<iframe(.*?)?\>(.|\s)*?\<\/iframe\>/i";

			/**
			 * Get all scripts and add cookieconsent if it does match with the criterion
			 */
			$updated_scripts = preg_replace_callback( $pattern, function ( $matches ) {

				$data = ( isset( $matches[0] ) ) ? $matches[0] : '';

				$data = str_replace( 'src=', 'data-cookieconsent="' . cookiebot_addons_output_cookie_types( $this->cookie_types ) . '" data-src=', $data );

				/**
				 * Return updated iframe tag
				 */
				return $data;
			}, $buffer );

			/**
			 * Set cache for 15 minutes
			 */
			set_transient( 'jetpack_google_maps_widget', $updated_scripts, 60 * 15 );
		}

		return $updated_scripts;
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
		if ( $widget == 'contact_info' && $view == 'widget_view' ) {
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
		return false;
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
