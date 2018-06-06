<?php

namespace cookiebot_addons_framework\controller\addons\google_analytics_plus;

use cookiebot_addons_framework\controller\addons\Cookiebot_Addons_Interface;
use cookiebot_addons_framework\lib\Cookie_Consent_Interface;
use cookiebot_addons_framework\lib\Settings_Service_Interface;
use cookiebot_addons_framework\lib\script_loader_tag\Script_Loader_Tag_Interface;
use cookiebot_addons_framework\lib\buffer\Buffer_Output_Interface;

class Google_Analytics_Plus implements Cookiebot_Addons_Interface {

	/**
	 * @var Settings_Service_Interface
	 *
	 * @since 1.5.0
	 */
	protected $settings;

	/**
	 * @var Script_Loader_Tag_Interface
	 *
	 * @since 1.5.0
	 */
	protected $script_loader_tag;

	/**
	 * @var Cookie_Consent_Interface
	 *
	 * @since 1.5.0
	 */
	protected $cookie_consent;

	/**
	 * @var Buffer_Output_Interface
	 *
	 * @since 1.5.0
	 */
	protected $buffer_output;

	/**
	 * constructor.
	 *
	 * @param $settings Settings_Service_Interface
	 * @param $script_loader_tag Script_Loader_Tag_Interface
	 * @param $cookie_consent Cookie_Consent_Interface
	 * @param $buffer_output Buffer_Output_Interface
	 *
	 * @since 1.5.0
	 */
	public function __construct( Settings_Service_Interface $settings, Script_Loader_Tag_Interface $script_loader_tag, Cookie_Consent_Interface $cookie_consent, Buffer_Output_Interface $buffer_output ) {
		$this->settings          = $settings;
		$this->script_loader_tag = $script_loader_tag;
		$this->cookie_consent    = $cookie_consent;
		$this->buffer_output     = $buffer_output;
	}

	/**
	 * Loads addon configuration
	 *
	 * @since 1.5.0
	 */
	public function load_configuration() {
		add_action( 'wp_loaded', array( $this, 'cookiebot_addon_google_analytics_async' ), 5 );
	}

	/**
	 * Disable scripts if state not accepted
	 *
	 * @since 1.5.0
	 */
	public function cookiebot_addon_google_analytics_async() {
		// Check if Analytify is loaded.
		if ( ! class_exists( 'Google_Analytics_Async' ) ) {
			return;
		}

		// Check if Cookiebot is activated and active.
		if ( ! function_exists( 'cookiebot_active' ) || ! cookiebot_active() ) {
			return;
		}

		// Disable Analytify if cookie consent not allowed
		if ( ! $this->cookie_consent->are_cookie_states_accepted( $this->get_cookie_types() ) ) {
			cookiebot_remove_class_action( 'wp_head', 'Google_Analytics_Async', 'tracking_code_output' );
		}
	}

	/**
	 * Return addon/plugin name
	 *
	 * @return string
	 *
	 * @since 1.5.0
	 */
	public function get_addon_name() {
		return 'Google Analytics +';
	}

	/**
	 * Option name in the database
	 *
	 * @return string
	 *
	 * @since 1.5.0
	 */
	public function get_option_name() {
		return 'google_analytics_plus';
	}

	/**
	 * Plugin file name
	 *
	 * @return string
	 *
	 * @since 1.5.0
	 */
	public function get_plugin_file() {
		return 'google-analytics-async/google-analytics-async.php';
	}

	/**
	 * Returns checked cookie types
	 * @return mixed
	 *
	 * @since 1.5.0
	 */
	public function get_cookie_types() {
		return $this->settings->get_cookie_types( $this->get_option_name() );
	}

	/**
	 * Check if plugin is activated and checked in the backend
	 *
	 * @since 1.5.0
	 */
	public function is_addon_enabled() {
		return $this->settings->is_addon_enabled( $this->get_option_name() );
	}

	/**
	 * Checks if addon is installed
	 *
	 * @since 1.5.0
	 */
	public function is_addon_installed() {
		return $this->settings->is_addon_installed( $this->get_plugin_file() );
	}

	/**
	 * Checks if addon is activated
	 *
	 * @since 1.5.0
	 */
	public function is_addon_activated() {
		return $this->settings->is_addon_activated( $this->get_plugin_file() );
	}
}