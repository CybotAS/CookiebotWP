<?php

namespace cybot\cookiebot\lib;

use cybot\cookiebot\addons\Cookiebot_Addons;
use cybot\cookiebot\admin_notices\Cookiebot_Recommendation_Notice;
use cybot\cookiebot\gutenberg\Cookiebot_Gutenberg_Declaration_Block;
use cybot\cookiebot\settings\Menu_Settings;
use cybot\cookiebot\settings\Network_Menu_Settings;
use cybot\cookiebot\widgets\Dashboard_Widget_Cookiebot_Status;
use RuntimeException;

class Cookiebot_WP {
	const COOKIEBOT_PLUGIN_VERSION  = '4.1.1';
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
		Cookiebot_Addons::instance();
		load_plugin_textdomain( 'cookiebot', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );

		if ( is_admin() ) {
			( new Menu_Settings() )->add_menu();
			if ( is_multisite() ) {
				( new Network_Menu_Settings() )->add_menu();
			}
			( new Dashboard_Widget_Cookiebot_Status() )->register_hooks();
			( new Cookiebot_Recommendation_Notice() )->register_hooks();
		}

		( new Consent_API_Helper() )->register_hooks();
		( new Cookiebot_Javascript_Helper() )->register_hooks();
		( new Cookiebot_Automatic_Updates() )->register_hooks();
		( new Widgets() )->register_hooks();
		( new Cookiebot_Gutenberg_Declaration_Block() )->register_hooks();
		( new WP_Rocket_Helper() )->register_hooks();
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
}
