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
		 * @deprecated
		 * @throws RuntimeException
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
		 * @deprecated
		 * @return Cookiebot_WP - Main instance
		 * @throws RuntimeException
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

if ( ! function_exists( 'cookiebot_assist' ) ) {
	/**
	 * Helper function to update your scripts
	 *
	 * @param string|string[] $type
	 *
	 * @return string
	 * @deprecated
	 */
	function cookiebot_assist( $type = 'statistics' ) {
		deprecation_error(
			'Function',
			__METHOD__,
			'\cybot\cookiebot\lib\cookiebot_assist'
		);
		return \cybot\cookiebot\lib\cookiebot_assist( $type );
	}
}


if ( ! function_exists( 'cookiebot_active' ) ) {
	/**
	 * Helper function to check if cookiebot is active.
	 * Useful for other plugins adding support for Cookiebot.
	 *
	 * @return  bool
	 * @deprecated
	 * @since   1.2
	 * @version 2.2.2
	 */
	function cookiebot_active() {
		deprecation_error(
			'Function',
			__METHOD__,
			'\cybot\cookiebot\lib\cookiebot_active'
		);
		return \cybot\cookiebot\lib\cookiebot_active();
	}
}


if ( ! function_exists( 'cookiebot' ) ) {
	/**
	 * Returns the main instance of Cookiebot_WP to prevent the need to use globals.
	 *
	 * @return  Cookiebot_WP
	 * @throws RuntimeException
	 * @deprecated
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function cookiebot() {
		deprecation_error(
			'Function',
			__METHOD__,
			'\cybot\cookiebot\lib\cookiebot'
		);
		return Cookiebot_WP::instance();
	}
}
