<?php

namespace cookiebot_addons_framework\controller\addons\jetpack;

use cookiebot_addons_framework\controller\addons\Cookiebot_Addons_Interface;
use cookiebot_addons_framework\lib\buffer\Buffer_Output_Interface;
use cookiebot_addons_framework\lib\Cookie_Consent_Interface;
use cookiebot_addons_framework\lib\script_loader_tag\Script_Loader_Tag_Interface;
use cookiebot_addons_framework\lib\Settings_Service_Interface;

/**
 * This class is used to support jetpack in cookiebot
 *
 * Class Jetpack
 * @package cookiebot_addons_framework\controller\addons\jetpack
 *
 * @since 1.3.0
 */
class Jetpack implements Cookiebot_Addons_Interface {

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
	 * Jetpack constructor.
	 *
	 * @param $settings Settings_Service_Interface
	 * @param $script_loader_tag Script_Loader_Tag_Interface
	 * @param $cookie_consent Cookie_Consent_Interface
	 * @param $buffer_output Buffer_Output_Interface
	 *
	 * @since 1.2.0
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
	 * @since 1.3.0
	 */
	public function load_configuration() {
		/**
		 * Load configuration for google maps widget
		 *
		 * @since 1.2.0
		 */
		new Google_Maps_Widget();

		/**
		 * Load configuration for facebook page widget
		 *
		 * @since 1.2.0
		 */
		new Facebook_Widget( $this->script_loader_tag );

		/**
		 * Load configuration for visitor cookies
		 *
		 * @since 1.2.0
		 */
		new Visitor_Cookies( $this->script_loader_tag, $this->cookie_consent );

		/**
		 * Load configuration for googleplus badge widget
		 *
		 * @since 1.2.0
		 */
		new Googleplus_Badge_Widget( $this->script_loader_tag );

		/**
		 * Load configuration for internet defense league widget
		 *
		 * @since 1.2.0
		 */
		new Internet_Defense_league_Widget( $this->cookie_consent );

		/**
		 * Load configuration for twitter timeline widget
		 *
		 * @since 1.2.0
		 */
		new Twitter_Timeline_Widget( $this->script_loader_tag );

		/**
		 * Load configuration for goodreads widget
		 *
		 * @since 1.2.0
		 */
		new Goodreads_Widget( $this->buffer_output );
	}

	/**
	 * Return addon/plugin name
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_addon_name() {
		return 'Jetpack';
	}

	/**
	 * Option name in the database
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_option_name() {
		return 'hubspot_tracking_code';
	}

	/**
	 * Addon file name
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_addon_file() {
		return 'jetpack/jetpack.php';
	}

	/**
	 * The addon is checked in the backend, so update the status to 1.
	 *
	 * @since 1.3.0
	 */
	public function enable_addon() {
		$this->settings->activate_addon( $this->get_addon_file() );
	}

	/**
	 * The addon is unchecked in the backend, so update the status to 0.
	 *
	 * @since 1.3.0
	 */
	public function disable_addon() {
		$this->settings->disable_addon( $this->get_addon_file() );
	}

	/**
	 * Check if plugin is activated and checked in the backend
	 *
	 * @since 1.3.0
	 */
	public function is_addon_enabled() {
		return $this->settings->is_addon_enabled( $this->get_addon_file() );
	}

	/**
	 * Checks if addon is installed
	 *
	 * @since 1.3.0
	 */
	public function is_addon_installed() {
		return $this->settings->is_addon_installed( $this->get_addon_file() );
	}

	public function save_changes() {

	}
}