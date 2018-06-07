<?php

namespace cookiebot_addons_framework\controller\addons\pixel_caffeine;

use cookiebot_addons_framework\controller\addons\Cookiebot_Addons_Interface;
use cookiebot_addons_framework\lib\Cookie_Consent_Interface;
use cookiebot_addons_framework\lib\Settings_Service_Interface;
use cookiebot_addons_framework\lib\script_loader_tag\Script_Loader_Tag_Interface;
use cookiebot_addons_framework\lib\buffer\Buffer_Output_Interface;

class Pixel_Caffeine implements Cookiebot_Addons_Interface {

	/**
	 * @var Settings_Service_Interface
	 *
	 * @since 1.4.0
	 */
	protected $settings;

	/**
	 * @var Script_Loader_Tag_Interface
	 *
	 * @since 1.4.0
	 */
	protected $script_loader_tag;

	/**
	 * @var Cookie_Consent_Interface
	 *
	 * @since 1.4.0
	 */
	protected $cookie_consent;

	/**
	 * @var Buffer_Output_Interface
	 *
	 * @since 1.4.0
	 */
	protected $buffer_output;

	/**
	 * Jetpack constructor.
	 *
	 * @param $settings Settings_Service_Interface
	 * @param $script_loader_tag Script_Loader_Tag_Interface
	 * @param $cookie_consent Cookie_Consent_Interface
	 * @param $buffer_output Buffer_Output_Interface
	 *
	 * @since 1.4.0
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
	 * @since 1.4.0
	 */
	public function load_configuration() {
		add_action( 'wp_loaded', array( $this, 'cookiebot_addon_pixel_caffeine' ), 5 );
	}

	/**
	 * Disable scripts if state not accepted
	 *
	 * @since 1.4.0
	 */
	public function cookiebot_addon_pixel_caffeine() {
		// Check if Pixel Caffeine is loaded.
		if ( ! defined( 'AEPC_PLUGIN_FILE' ) ) {
			return;
		}

		// Check if Cookiebot is activated and active.
		if ( ! function_exists( 'cookiebot_active' ) || ! cookiebot_active() ) {
			return;
		}

		$this->script_loader_tag->add_tag( 'aepc-pixel-events', array( 'facebook' => $this->get_cookie_types() ) );

		if ( ! $this->cookie_consent->are_cookie_states_accepted( $this->get_cookie_types() ) ) {
			cookiebot_remove_class_action( 'wp_head', 'AEPC_Pixel_Scripts', 'pixel_init', 99 );
			cookiebot_remove_class_action( 'wp_footer', 'AEPC_Pixel_Scripts', 'pixel_init', 1 );
		}

	}

	/**
	 * Return addon/plugin name
	 *
	 * @return string
	 *
	 * @since 1.4.0
	 */
	public function get_addon_name() {
		return 'Pixel Caffeine';
	}

	/**
	 * Option name in the database
	 *
	 * @return string
	 *
	 * @since 1.4.0
	 */
	public function get_option_name() {
		return 'pixel_caffeine';
	}

	/**
	 * Plugin file name
	 *
	 * @return string
	 *
	 * @since 1.4.0
	 */
	public function get_plugin_file() {
		return 'pixel-caffeine/pixel-caffeine.php';
	}

	/**
	 * Returns checked cookie types
	 * @return mixed
	 *
	 * @since 1.4.0
	 */
	public function get_cookie_types() {
		return $this->settings->get_cookie_types( $this->get_option_name() );
	}

	/**
	 * Check if plugin is activated and checked in the backend
	 *
	 * @since 1.4.0
	 */
	public function is_addon_enabled() {
		return $this->settings->is_addon_enabled( $this->get_option_name() );
	}

	/**
	 * Checks if addon is installed
	 *
	 * @since 1.4.0
	 */
	public function is_addon_installed() {
		return $this->settings->is_addon_installed( $this->get_plugin_file() );
	}

	/**
	 * Checks if addon is activated
	 *
	 * @since 1.4.0
	 */
	public function is_addon_activated() {
		return $this->settings->is_addon_activated( $this->get_plugin_file() );
	}
}