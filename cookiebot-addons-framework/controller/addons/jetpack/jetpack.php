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
		add_action( 'init', array( $this, 'cookiebot_addon_jetpack' ), 5 );
	}

	/**
	 * Check for google analyticator action hooks
	 *
	 * @since 1.2.0
	 */
	public function cookiebot_addon_jetpack() {
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
	}
}