<?php

namespace cookiebot_addons\controller\addons\jetpack;

use cookiebot_addons\controller\addons\Base_Cookiebot_Addon;
use cookiebot_addons\controller\addons\jetpack\widget\Google_Maps_Widget;
use cookiebot_addons\controller\addons\jetpack\widget\Facebook_Widget;
use cookiebot_addons\controller\addons\jetpack\widget\Googleplus_Badge_Widget;
use cookiebot_addons\controller\addons\jetpack\widget\Goodreads_Widget;
use cookiebot_addons\controller\addons\jetpack\widget\Internet_Defense_league_Widget;
use cookiebot_addons\controller\addons\jetpack\widget\Twitter_Timeline_Widget;
use cookiebot_addons\lib\buffer\Buffer_Output_Interface;
use cookiebot_addons\lib\Cookie_Consent_Interface;
use cookiebot_addons\lib\script_loader_tag\Script_Loader_Tag_Interface;
use cookiebot_addons\lib\Settings_Service_Interface;

/**
 * This class is used to support jetpack in cookiebot
 *
 * Class Jetpack
 * @package cookiebot_addons\controller\addons\jetpack
 *
 * @since 1.3.0
 */
class Jetpack extends Base_Cookiebot_Addon {

	const ADDON_NAME = 'Jetpack';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable tracking.';
	const OPTION_NAME = 'jetpack';
	const PLUGIN_FILE_PATH = 'jetpack/jetpack.php';
	const DEFAULT_COOKIE_TYPES = array( 'statistics', 'marketing' );
	const ENABLE_ADDON_BY_DEFAULT = false;

	protected $widgets = array();

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
	 * @since 1.8.0
	 */
	public function set_widgets() {
		/**
		 * Load configuration for google maps widget
		 *
		 * @since 1.2.0
		 */
		$this->widgets[] = new Google_Maps_Widget( $this->settings, $this->script_loader_tag, $this->cookie_consent, $this->buffer_output,
			$this->get_widget_option() );

		/**
		 * Load configuration for internet defense league widget
		 *
		 * @since 1.2.0
		 */
		$this->widgets[] = new Internet_Defense_league_Widget( $this->settings, $this->script_loader_tag, $this->cookie_consent, $this->buffer_output,
			$this->get_widget_option() );

		/**
		 * Load configuration for visitor cookies
		 *
		 * @since 1.2.0
		 */
		$this->widgets[] = new Visitor_Cookies( $this->settings, $this->script_loader_tag, $this->cookie_consent, $this->buffer_output,
			$this->get_widget_option() );

		/**
		 * Load configuration for twitter timeline widget
		 *
		 * @since 1.2.0
		 */
		$this->widgets[] = new Twitter_Timeline_Widget( $this->settings, $this->script_loader_tag, $this->cookie_consent, $this->buffer_output,
			$this->get_widget_option() );

		/**
		 * Load configuration for goodreads widget
		 *
		 * @since 1.2.0
		 */
		$this->widgets[] = new Goodreads_Widget( $this->settings, $this->script_loader_tag, $this->cookie_consent, $this->buffer_output,
			$this->get_widget_option() );

		/**
		 * Load configuration for facebook widget
		 *
		 * @since 1.2.0
		 */
		$this->widgets[] = new Facebook_Widget( $this->settings, $this->script_loader_tag, $this->cookie_consent, $this->buffer_output,
			$this->get_widget_option() );

		/**
		 * If jetpack version is lower than 7 than add googleplus badge widget
		 *
		 * @since 2.2.1
		 */
		if ( version_compare( $this->get_addon_version(), '7', '<' ) ) {
			/**
			 * Load configuration for googleplus badge widget
			 *
			 * @since 1.2.0
			 */
			$this->widgets[] = new Googleplus_Badge_Widget( $this->settings, $this->script_loader_tag, $this->cookie_consent, $this->buffer_output,
				$this->get_widget_option() );
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
	 * Returns default cookie types
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
	 * @return array
	 *
	 * @since 1.3.0
	 */
	public function get_widgets() {
		return $this->widgets;
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
	 * Returns the url of WordPress SVN repository or another link where we can verify the plugin file.
	 *
	 * @return boolean
	 *
	 * @since 1.8.0
	 */
	public function get_svn_url() {
		return 'http://plugins.svn.wordpress.org/jetpack/trunk/jetpack.php';
	}
}
