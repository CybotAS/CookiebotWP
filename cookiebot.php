<?php

namespace cybot\cookiebot;

/*
Plugin Name: Cookiebot | GDPR/CCPA Compliant Cookie Consent and Control
Plugin URI: https://cookiebot.com/
Description: Cookiebot is a cloud-driven solution that automatically controls cookies and trackers, enabling full GDPR/ePrivacy and CCPA compliance for websites.
Author: Cybot A/S
Version: 3.11.1
Author URI: http://cookiebot.com
Text Domain: cookiebot
Domain Path: /langs
*/

use cybot\cookiebot\addons\Cookiebot_Addons;
use cybot\cookiebot\admin_notices\Cookiebot_Recommendation_Notice;
use cybot\cookiebot\gutenberg\Cookiebot_Gutenberg_Declaration_Block;
use cybot\cookiebot\lib\Consent_API_Helper;
use cybot\cookiebot\lib\Cookiebot_Activated;
use cybot\cookiebot\lib\Cookiebot_Automatic_Updates;
use cybot\cookiebot\lib\Cookiebot_Deactivated;
use cybot\cookiebot\lib\Cookiebot_Javascript_Helper;
use cybot\cookiebot\settings\Menu_Settings;
use cybot\cookiebot\settings\Network_Menu_Settings;
use cybot\cookiebot\widgets\Cookiebot_Declaration_Widget;
use cybot\cookiebot\widgets\Dashboard_Widget_Cookiebot_Status;
use RuntimeException;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once 'vendor/autoload.php';
require_once 'src/lib/helper.php';

define( 'CYBOT_COOKIEBOT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CYBOT_COOKIEBOT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if ( ! class_exists( 'Cookiebot_WP' ) ) {
	final class Cookiebot_WP {
		const COOKIEBOT_PLUGIN_VERSION  = '3.11.1';
		const COOKIEBOT_MIN_PHP_VERSION = '5.6.0';

		/**
		 * @var   Cookiebot_WP The single instance of the class
		 * @since 1.0.0
		 */
		private static $instance = null;

		/**
		 * Main Cookiebot_WP Instance
		 *
		 * Ensures only one instance of Cookiebot_WP is loaded or can be loaded.
		 *
		 * @return  Cookiebot_WP - Main instance
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

		/**
		 * Cookiebot_WP Constructor.
		 *
		 * @throws RuntimeException
		 * @since   1.0.0
		 * @access  public
		 * @version 2.1.4
		 */
		public function __construct() {
			$this->throw_exception_if_php_version_is_incompatible();

			add_action( 'after_setup_theme', array( $this, 'cookiebot_init' ), 5 );
			register_activation_hook( __FILE__, array( new Cookiebot_Activated(), 'run' ) );
			register_deactivation_hook( __FILE__, array( new Cookiebot_Deactivated(), 'run' ) );
		}

		/**
		 * @throws RuntimeException
		 */
		private function throw_exception_if_php_version_is_incompatible() {
			if ( version_compare( PHP_VERSION, self::COOKIEBOT_MIN_PHP_VERSION, '<' ) ) {
				$message = sprintf(
				// translators: The placeholder is for the COOKIEBOT_MIN_PHP_VERSION constant
					__( 'The Cookiebot plugin requires PHP version %s or greater.', 'cookiebot' ),
					self::COOKIEBOT_MIN_PHP_VERSION
				);
				throw new RuntimeException( $message );
			}
		}

		public function cookiebot_init() {
			// cookiebot addon trigger
			Cookiebot_Addons::instance();

			if ( is_admin() ) {

				//Adding menu to WP admin
				( new Menu_Settings() )->add_menu();

				if ( is_multisite() ) {
					( new Network_Menu_Settings() )->add_menu();
				}

				//Adding dashboard widgets
				( new Dashboard_Widget_Cookiebot_Status() )->register_hooks();

				( new Cookiebot_Recommendation_Notice() )->register_hooks();
			}

			( new Consent_API_Helper() )->register_hooks();

			// Set up localisation
			load_plugin_textdomain( 'cookiebot', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );

			( new Cookiebot_Javascript_Helper() )->register_hooks();

			//Add filter if WP rocket is enabled
			if ( defined( 'WP_ROCKET_VERSION' ) ) {
				add_filter( 'rocket_minify_excluded_external_js', array( $this, 'wp_rocket_exclude_external_js' ) );
			}

			//Add filter
			add_filter( 'sgo_javascript_combine_excluded_external_paths', array( $this, 'sgo_exclude_external_js' ) );

			( new Cookiebot_Automatic_Updates() )->register_hooks();

			//Loading widgets
			add_action( 'widgets_init', array( $this, 'register_widgets' ) );

			//Add Gutenberg block
			( new Cookiebot_Gutenberg_Declaration_Block() )->register_hooks();
		}

		/**
		 * Cookiebot_WP Load text domain
		 *
		 * @version 2.0.0
		 * @since       2.0.0
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'cookiebot', false, basename( dirname( __FILE__ ) ) . '/langs' );
		}

		/**
		 * Cookiebot_WP Register widgets
		 *
		 * @version 2.5.0
		 * @since   2.5.0
		 */
		public function register_widgets() {
			register_widget( Cookiebot_Declaration_Widget::class );
		}

		/**
		 * Returns true if an user is logged in and has an edit_themes capability
		 *
		 * @return bool
		 *
		 * @since 3.3.1
		 * @version 3.4.1
		 */
		public static function can_current_user_edit_theme() {
			if ( is_user_logged_in() ) {
				if ( current_user_can( 'edit_themes' ) ) {
					return true;
				}

				if ( current_user_can( 'edit_pages' ) ) {
					return true;
				}

				if ( current_user_can( 'edit_posts' ) ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * @return string
		 */
		public static function get_cbid() {
			$network_setting = (string) get_site_option( 'cookiebot-cbid', '' );
			$setting         = (string) get_option( 'cookiebot-cbid', $network_setting );

			return empty( $setting ) ? $network_setting : $setting;
		}

		/**
		 * @return string
		 */
		public static function get_cookie_blocking_mode() {
			$allowed_modes   = array( 'auto', 'manual' );
			$network_setting = (string) get_site_option( 'cookiebot-cookie-blocking-mode', 'manual' );
			$setting         = (string) get_option( 'cookiebot-cookie-blocking-mode', $network_setting );

			return in_array( $setting, $allowed_modes, true ) ? $setting : 'manual';
		}

		/**
		 * Cookiebot_WP Check if Cookiebot is active in admin
		 *
		 * @version 3.1.0
		 * @since       3.1.0
		 */
		public static function cookiebot_disabled_in_admin() {
			if ( is_multisite() && get_site_option( 'cookiebot-nooutput-admin', false ) ) {
				return true;
			} elseif ( get_option( 'cookiebot-nooutput-admin', false ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Cookiebot_WP Adding Cookiebot domain(s) to exclude list for WP Rocket minification.
		 *
		 * @version 1.6.1
		 * @since   1.6.1
		 */
		public function wp_rocket_exclude_external_js( $external_js_hosts ) {
			$external_js_hosts[] = 'consent.cookiebot.com';      // Add cookiebot domains
			$external_js_hosts[] = 'consentcdn.cookiebot.com';

			return $external_js_hosts;
		}

		/**
		 * Cookiebot_WP Adding Cookiebot domain(s) to exclude list for SGO minification.
		 *
		 * @version 3.6.5
		 * @since   3.6.5
		 */
		public function sgo_exclude_external_js( $exclude_list ) {
			//Uses same format as WP Rocket - for now we just use WP Rocket function
			if ( function_exists( 'wp_rocket_exclude_external_js' ) ) {
				return wp_rocket_exclude_external_js( $exclude_list );
			}
			return $exclude_list;
		}

	}
}


/**
 * @param  string|string[]  $type
 *
 * @return string
 */
function cookiebot_assist( $type = 'statistics' ) {
	$type_array = array_filter(
		is_array( $type ) ? $type : array( $type ),
		function ( $type ) {
			return in_array( $type, array( 'marketing', 'statistics', 'preferences' ), true );
		}
	);

	if ( count( $type_array ) > 0 ) {
		return ' type="text/plain" data-cookieconsent="' . implode( ',', $type ) . '"';
	}

	return '';
}


/**
 * Helper function to check if cookiebot is active.
 * Useful for other plugins adding support for Cookiebot.
 *
 * @return  string
 * @since   1.2
 * @version 2.2.2
 */
function cookiebot_active() {
	$cbid = Cookiebot_WP::get_cbid();
	return ! empty( $cbid );
}


if ( ! function_exists( 'cookiebot' ) ) {
	/**
	 * Returns the main instance of Cookiebot_WO to prevent the need to use globals.
	 *
	 * @return  Cookiebot_WP
	 * @throws RuntimeException
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function cookiebot() {
		return Cookiebot_WP::instance();
	}
}

cookiebot();
