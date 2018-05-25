<?php

namespace cookiebot_addons_framework\controller\addons\add_to_any;

use cookiebot_addons_framework\controller\addons\Cookiebot_Addons_Interface;
use cookiebot_addons_framework\lib\Cookiebot_Cookie_Consent_Interface;
use cookiebot_addons_framework\lib\Cookiebot_Settings_Interface;
use cookiebot_addons_framework\lib\script_loader_tag\Cookiebot_Script_Loader_Tag_Interface;
use cookiebot_addons_framework\lib\buffer\Cookiebot_Buffer_Output_Interface;

class Add_To_Any implements Cookiebot_Addons_Interface {

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
		add_action( 'wp_loaded', array( $this, 'cookiebot_addon_add_to_any' ), 5 );
	}

	/**
	 * Disable scripts if state not accepted
	 *
	 * @since 1.3.0
	 */
	public function cookiebot_addon_add_to_any() {
		// Check if Add To Any is loaded.
		if ( ! function_exists( 'A2A_SHARE_SAVE_init' ) ) {
			return;
		}

		// Check if Cookiebot is activated and active.
		if ( ! function_exists( 'cookiebot_active' ) || ! cookiebot_active() ) {
			return;
		}

		// Disable Add To Any if marketing not allowed
		if ( ! $this->cookie_consent->is_cookie_state_accepted( 'marketing' ) ) {
			add_filter( 'addtoany_script_disabled', '__return_true' );
		}

		// External js, so manipulate attributes
		if ( has_action( 'wp_enqueue_scripts', 'A2A_SHARE_SAVE_enqueue_script' ) ) {
			$this->script_loader_tag->add_tag( 'addtoany', 'marketing' );
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
		return 'addToAny Share Buttons';
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
		return $this->settings->is_addon_enabled();
	}

	/**
	 * Checks if addon is installed
	 *
	 * @since 1.3.0
	 */
	public function is_plugin_installed() {
		// service get if plugin is installed
	}
}