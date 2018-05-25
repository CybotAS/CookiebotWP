<?php

namespace cookiebot_addons_framework\controller\addons\google_analyticator;

use cookiebot_addons_framework\controller\addons\Cookiebot_Addons_Interface;
use cookiebot_addons_framework\lib\buffer\Cookiebot_Buffer_Output_Interface;
use cookiebot_addons_framework\lib\Cookiebot_Cookie_Consent_Interface;
use cookiebot_addons_framework\lib\script_loader_tag\Cookiebot_Script_Loader_Tag_Interface;
use cookiebot_addons_framework\lib\Cookiebot_Settings_Interface;

class Google_Analyticator implements Cookiebot_Addons_Interface {

	/**
	 * @var Cookiebot_Settings_Interface
	 *
	 * @since 1.3.0
	 */
	protected $settings;

	/**
	 * @var Cookiebot_Script_Loader_Tag_Interface
	 *
	 * @since 1.3.0
	 */
	protected $script_loader_tag;

	/**
	 * @var Cookiebot_Cookie_Consent_Interface
	 *
	 * @since 1.3.0
	 */
	protected $cookie_consent;

	/**
	 * @var Cookiebot_Buffer_Output_Interface
	 *
	 * @since 1.3.0
	 */
	protected $buffer_output;

	/**
	 * Jetpack constructor.
	 *
	 * @param $settings Cookiebot_Settings_Interface
	 * @param $script_loader_tag Cookiebot_Script_Loader_Tag_Interface
	 * @param $cookie_consent Cookiebot_Cookie_Consent_Interface
	 * @param $buffer_output Cookiebot_Buffer_Output_Interface
	 *
	 * @since 1.3.0
	 */
	public function __construct( Cookiebot_Settings_Interface $settings, Cookiebot_Script_Loader_Tag_Interface $script_loader_tag, Cookiebot_Cookie_Consent_Interface $cookie_consent, Cookiebot_Buffer_Output_Interface $buffer_output ) {
		$this->settings          = $settings;
		$this->script_loader_tag = $script_loader_tag;
		$this->cookie_consent    = $cookie_consent;
		$this->buffer_output     = $buffer_output;
	}

	/**
	 * Loads addon configuration
	 *
	 * @since 1.3.0
	 */
	public function load_configuration() {
		add_action( 'wp_loaded', array( $this, 'cookiebot_addon_google_analyticator' ), 5 );
	}

	/**
	 * Check for google analyticator action hooks
	 *
	 * @since 1.3.0
	 */
	public function cookiebot_addon_google_analyticator() {
		/**
		 * ga scripts are loaded in wp_head priority set to 99
		 */
		if ( has_action( 'wp_head', 'add_google_analytics' ) ) {
			$this->buffer_output->add_tag( 'wp_head', 99 );
		}

		/**
		 * ga scripts are loaded in login_head priority set to 99
		 */
		if ( has_action( 'login_head', 'add_google_analytics' ) ) {
			$this->buffer_output->add_tag( 'login_head', 99 );
		}

		/**
		 * External js, so manipulate attributes
		 */
		if ( has_action( 'wp_print_scripts', 'ga_external_tracking_js' ) ) {
			/**
			 * Catch external js file and add cookiebot attributes to it
			 *
			 * @since 1.1.0
			 */
			$this->script_loader_tag->add_tag( 'ga-external-tracking', 'statistics' );
		}
	}

	/**
	 * Return addon/plugin name
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_addon_name() {
		return 'Google Analyticator';
	}

	/**
	 * The addon is checked in the backend, so update the status to 1.
	 *
	 * @since 1.3.0
	 */
	public function enable_addon() {
		// enable in service interface
	}

	/**
	 * The addon is unchecked in the backend, so update the status to 0.
	 *
	 * @since 1.3.0
	 */
	public function disable_addon() {
		// disable in service interface
	}

	/**
	 * Check if plugin is activated and checked in the backend
	 *
	 * @since 1.3.0
	 */
	public function is_addon_enabled() {
		// get status in service interface
		return true;
	}

	/**
	 * Checks if addon is installed
	 *
	 * @since 1.3.0
	 */
	public function is_plugin_installed() {
		// service get if plugin is installed
		return true;
	}
}