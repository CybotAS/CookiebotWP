<?php

use function cybot\cookiebot\lib\deprecation_error;

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedClassFound

if ( ! class_exists( 'Cookiebot_WP' ) ) {
	/**
	 * @deprecated
	 */
	final class Cookiebot_WP extends cybot\cookiebot\lib\Cookiebot_WP {


		/**
		 * @var Cookiebot_WP The single instance of the class
		 * @since 1.0.0
		 */
		private static $instance = null;

		/**
		 * Cookiebot_WP Constructor.
		 *
		 * @throws RuntimeException
		 * @deprecated
		 * @since   1.0.0
		 * @access  public
		 * @version 2.1.4
		 */
		public function __construct() {
			deprecation_error(
				'Class',
				self::class,
				\cybot\cookiebot\lib\Cookiebot_WP::class
			);
			parent::__construct();
		}

		/**
		 * Main Cookiebot_WP Instance
		 *
		 * Ensures only one instance of Cookiebot_WP is loaded or can be loaded.
		 *
		 * @return Cookiebot_WP - Main instance
		 * @throws RuntimeException
		 * @deprecated
		 * @version 1.0.0
		 * @since   1.0.0
		 * @static
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}
}
