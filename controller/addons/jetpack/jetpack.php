<?php

namespace cookiebot_addons_framework\controller\addons\jetpack;

use cookiebot_addons_framework\controller\addons\Cookiebot_Addons_Interface;
use cookiebot_addons_framework\lib\buffer\Cookiebot_Buffer_Output_Interface;
use cookiebot_addons_framework\lib\Cookiebot_Cookie_Consent_Interface;
use cookiebot_addons_framework\lib\script_loader_tag\Cookiebot_Script_Loader_Tag_Interface;

/**
 * This class is used to support jetpack in cookiebot
 *
 * Class Jetpack
 * @package cookiebot_addons_framework\controller\addons\jetpack
 *
 * @since 1.2.0
 */
class Jetpack implements Cookiebot_Addons_Interface {

	/**
	 * @var Cookiebot_Script_Loader_Tag_Interface
	 */
	protected $script_loader_tag;

	/**
	 * @var Cookiebot_Cookie_Consent_Interface
	 */
	protected $cookie_consent;

	/**
	 * @var Cookiebot_Buffer_Output_Interface
	 */
	protected $buffer_output;

	/**
	 * Jetpack constructor.
	 *
	 * @param $script_loader_tag Cookiebot_Script_Loader_Tag_Interface
	 * @param $cookie_consent Cookiebot_Cookie_Consent_Interface
	 * @param $buffer_output Cookiebot_Buffer_Output_Interface
	 *
	 * @since 1.2.0
	 */
	public function __construct( Cookiebot_Script_Loader_Tag_Interface $script_loader_tag, Cookiebot_Cookie_Consent_Interface $cookie_consent, Cookiebot_Buffer_Output_Interface $buffer_output ) {
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
}