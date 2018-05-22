<?php

namespace cookiebot_addons_framework\controller\addons\jetpack;

/**
 * This class is used to support jetpack in cookiebot
 *
 * Class Jetpack
 * @package cookiebot_addons_framework\controller\addons\jetpack
 *
 * @since 1.2.0
 */
class Jetpack {

	/**
	 * Jetpack constructor.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {
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
		new Facebook_Widget();

		/**
		 * Load configuration for visitor cookies
		 */
		new Visitor_Cookies();

		/**
		 * Load configuration for googleplus badge widget
		 */
		new Googleplus_Badge_Widget();

		/**
		 * Load configuration for internet defense league widget
		 */
		new Internet_Defense_league_Widget();

		/**
		 * Load configuration for twitter timeline widget
		 */
		new Twitter_Timeline_Widget();

		/**
		 * Load configuration for goodreads widget
		 */
		new Goodreads_Widget();
	}
}