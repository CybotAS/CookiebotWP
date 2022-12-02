<?php

namespace cybot\cookiebot\addons\controller\addons\jetpack;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;
use cybot\cookiebot\addons\controller\addons\jetpack\widget\Base_Jetpack_Widget;
use cybot\cookiebot\addons\controller\addons\jetpack\widget\Google_Maps_Jetpack_Widget;
use cybot\cookiebot\addons\controller\addons\jetpack\widget\Facebook_Jetpack_Widget;
use cybot\cookiebot\addons\controller\addons\jetpack\widget\Googleplus_Badge_Jetpack_Widget;
use cybot\cookiebot\addons\controller\addons\jetpack\widget\Goodreads_Jetpack_Widget;
use cybot\cookiebot\addons\controller\addons\jetpack\widget\Internet_Defense_League_Jetpack_Widget;
use cybot\cookiebot\addons\controller\addons\jetpack\widget\Twitter_Timeline_Jetpack_Widget;
use cybot\cookiebot\addons\controller\addons\jetpack\widget\Visitor_Cookies_Jetpack_Widget;
use cybot\cookiebot\lib\buffer\Buffer_Output_Interface;
use cybot\cookiebot\lib\Cookie_Consent_Interface;
use cybot\cookiebot\lib\script_loader_tag\Script_Loader_Tag_Interface;
use cybot\cookiebot\lib\Settings_Service_Interface;
use Exception;

class Jetpack extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME                  = 'Jetpack';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable tracking.';
	const OPTION_NAME                 = 'jetpack';
	const PLUGIN_FILE_PATH            = 'jetpack/jetpack.php';
	const DEFAULT_COOKIE_TYPES        = array( 'statistics', 'marketing' );
	const ENABLE_ADDON_BY_DEFAULT     = false;
	const SVN_URL_BASE_PATH           = 'https://plugins.svn.wordpress.org/jetpack/trunk/';
	const SVN_URL_DEFAULT_SUB_PATH    = 'jetpack.php';

	private $widgets = array();

	/**
	 * Jetpack constructor.
	 *
	 * @param $settings Settings_Service_Interface
	 * @param $script_loader_tag Script_Loader_Tag_Interface
	 * @param $cookie_consent Cookie_Consent_Interface
	 * @param $buffer_output Buffer_Output_Interface
	 *
	 * @throws Exception
	 * @since 1.2.0
	 */
	public function __construct(
		Settings_Service_Interface $settings,
		Script_Loader_Tag_Interface $script_loader_tag,
		Cookie_Consent_Interface $cookie_consent,
		Buffer_Output_Interface $buffer_output
	) {
		parent::__construct( $settings, $script_loader_tag, $cookie_consent, $buffer_output );

		// set widgets
		if ( $this->is_addon_enabled() ) {
			$this->set_widgets();
		}
	}

	/**
	 * Loads addon configuration
	 *
	 * @since 1.3.0
	 */
	public function load_addon_configuration() {
		// load widgets
		$this->load_widgets();
	}

	/**
	 * Sets every widget into this class
	 *
	 * @throws Exception
	 * @since 1.8.0
	 */
	public function set_widgets() {
		/**
		 * Load configuration for Google Maps widget
		 *
		 * @since 1.2.0
		 */
		$this->widgets[] = new Google_Maps_Jetpack_Widget(
			$this->settings,
			$this->script_loader_tag,
			$this->cookie_consent,
			$this->buffer_output
		);

		/**
		 * Load configuration for internet defense league widget
		 *
		 * @since 1.2.0
		 */
		$this->widgets[] = new Internet_Defense_League_Jetpack_Widget(
			$this->settings,
			$this->script_loader_tag,
			$this->cookie_consent,
			$this->buffer_output
		);

		/**
		 * Load configuration for visitor cookies
		 *
		 * @since 1.2.0
		 */
		$this->widgets[] = new Visitor_Cookies_Jetpack_Widget(
			$this->settings,
			$this->script_loader_tag,
			$this->cookie_consent,
			$this->buffer_output
		);

		/**
		 * Load configuration for twitter timeline widget
		 *
		 * @since 1.2.0
		 */
		$this->widgets[] = new Twitter_Timeline_Jetpack_Widget(
			$this->settings,
			$this->script_loader_tag,
			$this->cookie_consent,
			$this->buffer_output
		);

		/**
		 * Load configuration for goodreads widget
		 *
		 * @since 1.2.0
		 */
		$this->widgets[] = new Goodreads_Jetpack_Widget(
			$this->settings,
			$this->script_loader_tag,
			$this->cookie_consent,
			$this->buffer_output
		);

		/**
		 * Load configuration for facebook widget
		 *
		 * @since 1.2.0
		 */
		$this->widgets[] = new Facebook_Jetpack_Widget(
			$this->settings,
			$this->script_loader_tag,
			$this->cookie_consent,
			$this->buffer_output
		);

		/**
		 * If jetpack version is lower than 7 then add googleplus badge widget
		 *
		 * @since 2.2.1
		 */
		if ( version_compare( $this->get_version(), '7', '<' ) ) {
			/**
			 * Load configuration for googleplus badge widget
			 *
			 * @since 1.2.0
			 */
			$this->widgets[] = new Googleplus_Badge_Jetpack_Widget(
				$this->settings,
				$this->script_loader_tag,
				$this->cookie_consent,
				$this->buffer_output
			);
		}
	}

	/**
	 * Load widgets configuration
	 *
	 * @since 1.8.0
	 */
	public function load_widgets() {
		foreach ( $this->get_widgets() as $widget ) {
			$widget->load_configuration();
		}
	}

	/**
	 * Returns default cookie types
	 *
	 * @return array
	 *
	 * @since 1.5.0
	 */
	public function get_default_cookie_types() {
		return array( 'statistics' );
	}

	/**
	 * Returns all supported widgets
	 *
	 * @return Base_Jetpack_Widget[]
	 *
	 * @since 1.3.0
	 */
	public function get_widgets() {
		return $this->widgets;
	}
}
