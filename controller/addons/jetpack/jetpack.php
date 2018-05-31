<?php

namespace cookiebot_addons_framework\controller\addons\jetpack;

use cookiebot_addons_framework\controller\addons\jetpack\widget\Google_Maps_Widget;
use cookiebot_addons_framework\controller\addons\jetpack\widget\Facebook_Widget;
use cookiebot_addons_framework\controller\addons\jetpack\widget\Googleplus_Badge_Widget;
use cookiebot_addons_framework\controller\addons\jetpack\widget\Goodreads_Widget;
use cookiebot_addons_framework\controller\addons\jetpack\widget\Internet_Defense_league_Widget;
use cookiebot_addons_framework\controller\addons\jetpack\widget\Twitter_Timeline_Widget;
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
		new Google_Maps_Widget(
			$this->is_widget_enabled( 'google_maps' ),
			$this->get_widget_cookie_types( 'google_maps' ),
			$this->is_widget_placeholder_enabled( 'google_maps ' )
		);

		/**
		 * Load configuration for facebook page widget
		 *
		 * @since 1.2.0
		 */
		new Facebook_Widget(
			$this->is_widget_enabled( 'facebook' ),
			$this->get_widget_cookie_types( 'facebook' ),
			$this->is_widget_placeholder_enabled( 'facebook ' ),
			$this->script_loader_tag
		);

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
		new Googleplus_Badge_Widget(
			$this->is_widget_enabled( 'googleplus_badge' ),
			$this->get_widget_cookie_types( 'googleplus_badge' ),
			$this->is_widget_placeholder_enabled( 'googleplus_badge ' ),
			$this->script_loader_tag
		);

		/**
		 * Load configuration for internet defense league widget
		 *
		 * @since 1.2.0
		 */
		new Internet_Defense_league_Widget(
			$this->is_widget_enabled( 'internet_defense_league' ),
			$this->get_widget_cookie_types( 'internet_defense_league' ),
			$this->is_widget_placeholder_enabled( 'internet_defense_league ' ),
			$this->cookie_consent
		);

		/**
		 * Load configuration for twitter timeline widget
		 *
		 * @since 1.2.0
		 */
		new Twitter_Timeline_Widget(
			$this->is_widget_enabled( 'twitter_timeline' ),
			$this->get_widget_cookie_types( 'twitter_timeline' ),
			$this->is_widget_placeholder_enabled( 'twitter_timeline ' ),
			$this->script_loader_tag
		);

		/**
		 * Load configuration for goodreads widget
		 *
		 * @since 1.2.0
		 */
		new Goodreads_Widget(
			$this->is_widget_enabled( 'goodreads' ),
			$this->get_widget_cookie_types( 'goodreads' ),
			$this->is_widget_placeholder_enabled( 'goodreads ' ),
			$this->buffer_output
		);
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
		return 'jetpack';
	}

	/**
	 * Plugin file name
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_plugin_file() {
		return 'jetpack/jetpack.php';
	}

	/**
	 * Returns checked cookie types
	 * @return mixed
	 *
	 * @since 1.3.0
	 */
	public function get_cookie_types() {
		return $this->settings->get_cookie_types( $this->get_option_name() );
	}

	/**
	 * Check if plugin is activated and checked in the backend
	 *
	 * @since 1.3.0
	 */
	public function is_addon_enabled() {
		return $this->settings->is_addon_enabled( $this->get_option_name() );
	}

	/**
	 * Checks if addon is installed
	 *
	 * @since 1.3.0
	 */
	public function is_addon_installed() {
		return $this->settings->is_addon_installed( $this->get_plugin_file() );
	}

	/**
	 * Checks if addon is activated
	 *
	 * @since 1.3.0
	 */
	public function is_addon_activated() {
		return $this->settings->is_addon_activated( $this->get_plugin_file() );
	}

	/**
	 * Returns all supported widgets
	 *
	 * @return array
	 *
	 * @since 1.3.0
	 */
	public function get_widgets() {
		return array(
			'google_maps'             => 'Google maps',
			'facebook'                => 'Facebook',
			'googleplus_badge'        => 'Google Plus Badge',
			'internet_defense_league' => 'Internet defense league',
			'twitter_timeline'        => 'Twitter timeline',
			'goodreads'               => 'Goodreads'
		);
	}

	/**
	 * Returns widget option key for in the database
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_widget_option() {
		return 'cookiebot_jetpack_addon';
	}

	/**
	 * Returns cookie types for a widget
	 *
	 * @param $option
	 *
	 * @return mixed
	 *
	 * @since 1.3.0
	 */
	public function get_widget_cookie_types( $option ) {
		return $this->settings->get_widget_cookie_types( $this->get_widget_option(), $option );
	}

	/**
	 * Checks if a widget is enabled
	 *
	 * @param $option
	 *
	 * @return mixed
	 *
	 * @since 1.3.0
	 */
	public function is_widget_enabled( $option ) {
		return $this->settings->is_widget_enabled( $this->get_widget_option(), $option );
	}

	/**
	 * Checks if a widget placeholder is enabled
	 *
	 * @param $option   string  widget option name
	 *
	 * @return boolean  true    If widget placeholder is checked
	 *                  false   If widget placeholder is not checked
	 *
	 * @since 1.3.0
	 */
	public function is_widget_placeholder_enabled( $option ) {
		return $this->settings->is_widget_placeholder_enabled( $this->get_widget_option(), $option );
	}
}