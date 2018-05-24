<?php

namespace cookiebot_addons_framework\controller\addons\jetpack;

use cookiebot_addons_framework\controller\addons\Cookiebot_Addons_Abstract;
use cookiebot_addons_framework\controller\addons\Cookiebot_Addons_Interface;
use cookiebot_addons_framework\lib\Cookiebot_Buffer_Output;
use cookiebot_addons_framework\lib\Cookiebot_Cookie_Consent;
use cookiebot_addons_framework\lib\Cookiebot_Script_Loader_Tag;

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
	 * @var Cookiebot_Script_Loader_Tag
	 */
	protected $script_loader_tag;

	/**
	 * @var Cookiebot_Cookie_Consent
	 */
	protected $cookie_consent;

	/**
	 * @var Cookiebot_Buffer_Output
	 */
	protected $buffer_output;

	/**
	 * Jetpack constructor.
	 *
	 * @param $script_loader_tag Cookiebot_Script_Loader_Tag
	 * @param $cookie_consent Cookiebot_Cookie_Consent
	 * @param $buffer_output Cookiebot_Buffer_Output
	 *
	 * @since 1.2.0
	 */
	public function __construct( Cookiebot_Script_Loader_Tag $script_loader_tag, Cookiebot_Cookie_Consent $cookie_consent, Cookiebot_Buffer_Output $buffer_output ) {
		$this->script_loader_tag = $script_loader_tag;
		$this->cookie_consent    = $cookie_consent;
		$this->buffer_output     = $buffer_output;

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
		new Facebook_Widget( $script_loader_tag );

		/**
		 * Load configuration for visitor cookies
		 *
		 * @since 1.2.0
		 */
		new Visitor_Cookies( $script_loader_tag, $cookie_consent );

		/**
		 * Load configuration for googleplus badge widget
		 *
		 * @since 1.2.0
		 */
		new Googleplus_Badge_Widget( $script_loader_tag );

		/**
		 * Load configuration for internet defense league widget
		 *
		 * @since 1.2.0
		 */
		new Internet_Defense_league_Widget( $cookie_consent );

		/**
		 * Load configuration for twitter timeline widget
		 *
		 * @since 1.2.0
		 */
		new Twitter_Timeline_Widget( $script_loader_tag );

		/**
		 * Load configuration for goodreads widget
		 *
		 * @since 1.2.0
		 */
		new Goodreads_Widget( $buffer_output );
	}
}