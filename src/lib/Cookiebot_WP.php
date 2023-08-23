<?php

namespace cybot\cookiebot\lib;

use cybot\cookiebot\addons\Cookiebot_Addons;
use cybot\cookiebot\admin_notices\Cookiebot_Recommendation_Notice;
use cybot\cookiebot\gutenberg\Cookiebot_Gutenberg_Declaration_Block;
use cybot\cookiebot\settings\Menu_Settings;
use cybot\cookiebot\settings\Network_Menu_Settings;
use cybot\cookiebot\widgets\Dashboard_Widget_Cookiebot_Status;
use DomainException;
use RuntimeException;

class Cookiebot_WP {
	const COOKIEBOT_PLUGIN_VERSION  = '4.2.14';
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
			throw new DomainException( $message );
		}
	}

	public function cookiebot_init() {
		Cookiebot_Addons::instance();
		load_textdomain(
			'cookiebot',
			CYBOT_COOKIEBOT_PLUGIN_DIR . 'langs/cookiebot-' . get_locale() . '.mo'
		);
		load_plugin_textdomain( 'cookiebot', false, dirname( plugin_basename( __FILE__ ) ) . '/langs' );

		if ( is_admin() ) {
			( new Menu_Settings() )->add_menu();
			if ( is_multisite() && is_plugin_active_for_network( 'cookiebot/cookiebot.php' ) ) {
				( new Network_Menu_Settings() )->add_menu();
			}
			( new Dashboard_Widget_Cookiebot_Status() )->register_hooks();
			( new Cookiebot_Recommendation_Notice() )->register_hooks();
			( new Cookiebot_Review() )->register_hooks();
		}

		( new Consent_API_Helper() )->register_hooks();
		( new Cookiebot_Javascript_Helper() )->register_hooks();
		( new Cookiebot_Automatic_Updates() )->register_hooks();
		( new Widgets() )->register_hooks();
		( new Cookiebot_Gutenberg_Declaration_Block() )->register_hooks();
		( new WP_Rocket_Helper() )->register_hooks();

		$this->set_default_options();
		$this->delay_notice_recommendation_on_first_activation();
		add_filter( 'plugin_action_links_cookiebot/cookiebot.php', array( $this, 'set_settings_action_link' ) );
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
		if ( is_user_logged_in() &&
			(
				current_user_can( 'edit_themes' ) ||
				current_user_can( 'edit_pages' ) ||
				current_user_can( 'edit_posts' )
			)
		) {
			return true;
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
	 * @return bool
	 */
	public static function check_network_auto_blocking_mode() {
		$network_setting = (string) get_site_option( 'cookiebot-cookie-blocking-mode' );

		return $network_setting === 'auto' ? true : false;
	}

	/**
	 * @return string
	 */
	public static function get_cookie_categories_status() {
		return self::get_cookie_blocking_mode() === 'auto' ? 'disabled' : '';
	}

	/**
	 * @return bool
	 */
	public static function is_cookie_category_selected( $option, $category ) {
		$categories = get_option( $option );
		if ( ! $categories || ! is_array( $categories ) ) {
			return false;
		}

		return in_array( $category, $categories, true );
	}

	/**
	 * Cookiebot_WP Check if Cookiebot is active in admin
	 *
	 * @version 4.2.8
	 * @since       3.1.0
	 */
	public static function cookiebot_disabled_in_admin() {
		if ( ( is_network_admin() && get_site_option( 'cookiebot-nooutput-admin', false ) ) ||
			( ! is_network_admin() && get_site_option( 'cookiebot-nooutput-admin', false ) ) ||
			( ! is_network_admin() && get_option( 'cookiebot-nooutput-admin', false ) ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Cookiebot_WP Set default options
	 *
	 * @version 4.2.5
	 * @since       4.2.5
	 */
	private function set_default_options() {
		$options = array(
			'cookiebot-nooutput-admin' => '1',
			'cookiebot-gcm'            => '1',
		);

		foreach ( $options as $option => $default ) {
			if ( get_option( $option ) === false && ! get_option( $option . self::OPTION_FIRST_RUN_SUFFIX ) ) {
				update_option( $option, $default );
			}

			if ( ( get_option( $option ) || get_option( $option ) !== false ) && ! get_option( $option . self::OPTION_FIRST_RUN_SUFFIX ) ) {
				update_option( $option . self::OPTION_FIRST_RUN_SUFFIX, '1' );
			}
		}
	}

	/**
	 * Cookiebot_WP Delay recommendation notice 1 day after first activation
	 *
	 * @version 4.2.5
	 * @since       4.2.5
	 */
	private function delay_notice_recommendation_on_first_activation() {
		// Check if recommendation notice delay option exists
		if ( get_option( Cookiebot_Recommendation_Notice::COOKIEBOT_RECOMMENDATION_OPTION_KEY, false ) === false ) {
			// Delay in 1 day
			add_option( Cookiebot_Recommendation_Notice::COOKIEBOT_RECOMMENDATION_OPTION_KEY, strtotime( '+1 day' ) );
		}
	}

	public function set_settings_action_link( $actions ) {
		$cblinks = array(
			'<a href="' . esc_url( add_query_arg( 'page', 'cookiebot', admin_url( 'admin.php' ) ) ) . '">' . esc_html__( 'Dashboard', 'cookiebot' ) . '</a>',
		);
		$actions = array_merge( $actions, $cblinks );
		return $actions;
	}

	/**
	 * @return string
	 */
	public static function get_manager_language() {
		$locale          = get_locale();
		$supported_langs = array(
			'de_DE' => 'de',
			'da_DK' => 'da',
			'fr_FR' => 'fr',
			'it_IT' => 'it',
			'es_ES' => 'es',
		);

		return array_key_exists( $locale, $supported_langs ) ? $supported_langs[ $locale ] : esc_html( 'en' );
	}

	const OPTION_FIRST_RUN_SUFFIX = '-first-run';
}
