<?php


namespace cybot\cookiebot\lib;

use cybot\cookiebot\addons\Cookiebot_Addons;
use cybot\cookiebot\admin_notices\Cookiebot_Notices;
use cybot\cookiebot\gutenberg\Cookiebot_Gutenberg_Declaration_Block;
use cybot\cookiebot\settings\Menu_Settings;
use cybot\cookiebot\settings\Network_Menu_Settings;
use cybot\cookiebot\shortcode\Cookiebot_Embedding_Shortcode;
use cybot\cookiebot\widgets\Dashboard_Widget_Cookiebot_Status;
use cybot\cookiebot\lib\Account_Service;
use DomainException;
use RuntimeException;

if ( ! defined( 'CYBOT_COOKIEBOT_VERSION' ) ) {
	define( 'CYBOT_COOKIEBOT_VERSION', '1.0.0' );
}

class Cookiebot_WP {


	public static function debug_log( $message ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			// phpcs:ignore
			error_log( '[Cookiebot] ' . $message );
		}
	}

	const COOKIEBOT_PLUGIN_VERSION  = '4.5.1';
	const COOKIEBOT_MIN_PHP_VERSION = '5.6.0';

	/**
	 * @var   Cookiebot_WP The single instance of the class
	 * @since 1.0.0
	 */
	private static $instance = null;

	private $account_service;

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
		$this->cookiebot_init();
		register_activation_hook( __FILE__, array( new Cookiebot_Activated(), 'run' ) );
		register_deactivation_hook( __FILE__, array( new Cookiebot_Deactivated(), 'run' ) );

		// Initialize services
		$this->account_service = new Account_Service();
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
		add_action( 'init', array( $this, 'cookiebot_load_textdomain' ) );

		if ( is_admin() ) {
			( new Menu_Settings() )->add_menu();
			if ( is_multisite() && is_plugin_active_for_network( 'cookiebot/cookiebot.php' ) ) {
				( new Network_Menu_Settings() )->add_menu();
			}
			( new Dashboard_Widget_Cookiebot_Status() )->register_hooks();
			( new Cookiebot_Notices() )->register_hooks();
			( new Cookiebot_Review() )->register_hooks();
		}

		( new Consent_API_Helper() )->register_hooks();
		( new Cookiebot_Javascript_Helper() )->register_hooks();
		( new Cookiebot_Embedding_Shortcode() )->register_hooks();
		( new Cookiebot_Automatic_Updates() )->register_hooks();
		( new Widgets() )->register_hooks();
		( new Cookiebot_Gutenberg_Declaration_Block() )->register_hooks();
		( new WP_Rocket_Helper() )->register_hooks();

		$this->set_default_options();
		( new Cookiebot_Admin_Links() )->register_hooks();
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
	 * Loads translations textdomain
	 *
	 * @return void
	 */
	public function cookiebot_load_textdomain() {
		load_textdomain(
			'cookiebot',
			CYBOT_COOKIEBOT_PLUGIN_DIR . 'langs/cookiebot-' . get_locale() . '.mo'
		);
		load_plugin_textdomain( 'cookiebot', false, dirname( plugin_basename( __FILE__ ) ) . '/langs' );
	}

	/**
	 * @return string
	 */
	public static function get_cbid() {
		$network_setting = (string) get_site_option( 'cookiebot-cbid', '' );
		$setting         = (string) get_option( 'cookiebot-cbid', $network_setting );

		return empty( $setting ) ? $network_setting : $setting;
	}

	public static function get_auth_token() {
		$token = (string) get_option( 'cookiebot-auth-token' );
		return $token;
	}

	public static function get_user_data() {
		$user_data = get_option( 'cookiebot-user-data', '' );
		return empty( $user_data ) ? '' : $user_data;
	}

	/**
	 * Check if the user was onboarded via signup
	 *
	 * @return bool
	 */
	public static function was_onboarded_via_signup() {
		$user_data = self::get_user_data();
		return ! empty( $user_data ) && isset( $user_data['onboarded_via_signup'] ) && $user_data['onboarded_via_signup'] === true;
	}

	public static function get_scan_status() {
		$scan_status = get_option( 'cookiebot-scan-status', '' );
		return empty( $scan_status ) ? '' : $scan_status;
	}

	/**
	 * @return string
	 */
	public static function get_network_cbid() {
		return get_site_option( 'cookiebot-cbid', '' );
	}

	/**
	 * @return string
	 */
	public static function get_banner_enabled() {
		$enabled = get_option( 'cookiebot-banner-enabled', 'default' );
		if ( $enabled === 'default' ) {
			$enabled = '1';
			update_option( 'cookiebot-banner-enabled', $enabled );
		}
		return $enabled;
	}

	/**
	 * @return string
	 */
	public static function get_gcm_enabled() {
		$enabled = get_option( 'cookiebot-gcm', 'default' );
		if ( $enabled === 'default' ) {
			$enabled = '1';
			update_option( 'cookiebot-gcm', $enabled );
		}
		return $enabled;
	}

	/**
	 * @return string
	 */
	public static function get_preview_link() {
		$url = get_site_option( 'siteurl', '#' );
		return $url;
	}

	/**
	 * @return string
	 */
	public static function get_subscription_type() {
		$raw_data = get_option( 'cookiebot-user-data', '' );

		if ( empty( $raw_data ) ) {
			self::debug_log( 'Raw data is empty, returning Free' );
			return 'Free';
		}

		// Use the data directly if it's already an array
		$data = is_array( $raw_data ) ? $raw_data : json_decode( $raw_data, true );

		// Check if we have the new subscription structure
		if ( ! isset( $data['subscriptions']['active'] ) ) {
			self::debug_log( 'No active subscription found, returning Free' );
			return 'Free';
		}

		$subscription = $data['subscriptions']['active'];
		$status       = isset( $subscription['subscription_status'] ) ? $subscription['subscription_status'] : '';
		$price_plan   = isset( $subscription['price_plan'] ) ? $subscription['price_plan'] : 'free';

		self::debug_log( 'Subscription details:' );
		self::debug_log( 'Status: ' . $status );
		self::debug_log( 'Price Plan: ' . $price_plan );

		// Check for trial status first
		if ( in_array( $status, array( 'trial_will_be_billed', 'trial_missing_payment' ), true ) ) {
			self::debug_log( 'Trial status detected, returning Premium trial' );
			return 'Premium trial';
		}

		// If not in trial, check active subscriptions
		if ( in_array( $status, array( 'active_auto_renew', 'active_no_renew' ), true ) ) {
			// Map extended plan names to their base names
			$plan_mapping = array(
				'FreeExtended'      => 'Free',
				'EssentialExtended' => 'Essential',
				'PlusExtended'      => 'Plus',
				'ProExtended'       => 'Pro',
				'BusinessExtended'  => 'Business',
			);

			$result = isset( $plan_mapping[ $price_plan ] ) ? $plan_mapping[ $price_plan ] : ucfirst( $price_plan );
			self::debug_log( 'Active subscription, returning: ' . $result );
			return $result;
		}

		self::debug_log( 'No conditions met, returning Free' );
		return 'Free';
	}

	/**
	 * @return string
	 */
	public static function get_legal_framwework() {
		$configuration = get_option( 'cookiebot-configuration', array() );
		return isset( $configuration['legal_framework_template'] ) ? strtoupper( $configuration['legal_framework_template'] ) : 'GDPR';
	}

	/**
	 * @return string
	 */
	public static function get_cookie_blocking_mode() {
		$allowed_modes   = array( 'auto', 'manual' );
		$network_setting = (string) get_site_option( 'cookiebot-cookie-blocking-mode', 'manual' );
		$setting         = $network_setting === 'manual' ?
			(string) get_option( 'cookiebot-cookie-blocking-mode', $network_setting ) :
			$network_setting;

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

		self::set_tcf_version();
	}

	private static function set_tcf_version() {
		$iab_version = get_option( 'cookiebot-tcf-version' );
		if ( empty( $iab_version ) || $iab_version === 'IAB' ) {
			update_option( 'cookiebot-tcf-version', 'TCFv2.2' );
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

	public function get_usercentrics_script() {
		$final_script = '';

		// Enqueue Usercentrics scripts
		wp_enqueue_script(
			'usercentrics-tcf-stub',
			'https://web.cmp.usercentrics.eu/tcf/stub.js',
			array(),
			CYBOT_COOKIEBOT_VERSION,
			true
		);

		wp_enqueue_script(
			'usercentrics-autoblocker',
			'https://web.cmp.usercentrics.eu/modules/autoblocker.js',
			array(),
			CYBOT_COOKIEBOT_VERSION,
			true
		);

		wp_enqueue_script(
			'usercentrics-cmp',
			'https://web.cmp.usercentrics.eu/ui/loader.js',
			array(),
			CYBOT_COOKIEBOT_VERSION,
			true
		);

		// Add data attributes to the CMP script
		wp_script_add_data(
			'usercentrics-cmp',
			'data-usercentrics',
			'Usercentrics Consent Management Platform'
		);
		wp_script_add_data(
			'usercentrics-cmp',
			'data-settings-id',
			'%s'
		);
		wp_script_add_data(
			'usercentrics-cmp',
			'async',
			true
		);

		return $final_script;
	}

	public function enqueue_scripts() {
		wp_enqueue_script(
			'cookiebot',
			'https://consent.cookiebot.com/uc.js',
			array(),
			CYBOT_COOKIEBOT_VERSION,
			true
		);

		wp_enqueue_script(
			'cookiebot-helper',
			plugins_url( 'js/cookiebot-helper.js', dirname( __FILE__ ) ),
			array( 'jquery' ),
			CYBOT_COOKIEBOT_VERSION,
			true
		);

		wp_enqueue_script(
			'cookiebot-admin',
			plugins_url( 'js/cookiebot-admin.js', dirname( __FILE__ ) ),
			array( 'jquery' ),
			CYBOT_COOKIEBOT_VERSION,
			true
		);
	}

	/**
	 * Clone object and return it
	 *
	 * @return object
	 *
	 * @since 1.0.0
	 */
	public function get_cloned_object() {
		$temp = clone $this;
		return $temp;
	}

	/**
	 * Get the banner script HTML
	 *
	 * @return string
	 *
	 * @phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript
	 */
	public static function get_banner_script() {
		$cbid = self::get_cbid();
		self::debug_log( '=== get_banner_script: Starting ===' );
		self::debug_log( 'CBID: ' . $cbid );

		// Basic validation checks
		if ( empty( $cbid ) || defined( 'COOKIEBOT_DISABLE_ON_PAGE' ) ) {
			self::debug_log( 'get_banner_script: Basic validation failed - CBID empty or disabled' );
			return '';
		}

		// Banner enabled check
		$banner_enabled = get_option( 'cookiebot-banner-enabled', '1' );
		self::debug_log( 'Banner enabled setting: ' . $banner_enabled );
		if ( $banner_enabled === '0' ) {
			self::debug_log( 'get_banner_script: Banner disabled in settings' );
			return '';
		}

		// User verification and trial checks
		$user_data = get_option( 'cookiebot-user-data', array() );

		if ( ! empty( $user_data ) ) {
			// Check unverified user trial
			if ( isset( $user_data['email_verification_status'] ) &&
				$user_data['email_verification_status'] === 'unverified' &&
				isset( $user_data['trial_start_date'] ) ) {
				self::debug_log( 'Checking unverified user trial...' );
				self::debug_log( 'Trial start date: ' . $user_data['trial_start_date'] );
				$trial_start = \DateTime::createFromFormat( 'd-m-Y H:i:s', str_replace( ' T', ' ', $user_data['trial_start_date'] ) );
				$trial_end   = clone $trial_start;
				$trial_end   = $trial_end->modify( '+14 days' );
				self::debug_log( 'Trial end date: ' . $trial_end->format( 'Y-m-d H:i:s' ) );
				if ( $trial_start && new \DateTime() > $trial_end ) {
					self::debug_log( 'get_banner_script: Unverified user trial expired' );
					return '';
				}
			}

			// Check trial status
			if ( isset( $user_data['subscription_status'] ) &&
				$user_data['subscription_status'] === 'in_trial_non_billable' &&
				isset( $user_data['trial_end_date'] ) ) {
				self::debug_log( 'Checking trial status...' );
				self::debug_log( 'Trial end date: ' . $user_data['trial_end_date'] );
				$trial_end = \DateTime::createFromFormat( 'd-m-Y H:i:s', str_replace( ' T', ' ', $user_data['trial_end_date'] ) );
				if ( $trial_end && new \DateTime() > $trial_end ) {
					self::debug_log( 'get_banner_script: Trial period ended' );
					return '';
				}
			}
		}

		// Output conditions check
		$is_multisite     = is_multisite();
		$network_nooutput = $is_multisite ? get_site_option( 'cookiebot-nooutput', false ) : false;
		$site_nooutput    = get_option( 'cookiebot-nooutput', false );
		$can_edit_theme   = self::can_current_user_edit_theme();
		$output_logged_in = get_option( 'cookiebot-output-logged-in' );

		self::debug_log( 'Output conditions:' );
		self::debug_log( 'Is multisite: ' . ( $is_multisite ? 'yes' : 'no' ) );
		self::debug_log( 'Network nooutput: ' . ( $network_nooutput ? 'yes' : 'no' ) );
		self::debug_log( 'Site nooutput: ' . ( $site_nooutput ? 'yes' : 'no' ) );
		self::debug_log( 'Can edit theme: ' . ( $can_edit_theme ? 'yes' : 'no' ) );
		self::debug_log( 'Output logged in: ' . ( $output_logged_in ? 'yes' : 'no' ) );

		if ( $is_multisite && $network_nooutput ||
			$site_nooutput ||
			( $can_edit_theme && ! $output_logged_in ) ) {
			self::debug_log( 'get_banner_script: Output conditions not met' );
			return '';
		}

		self::debug_log( 'get_banner_script: Generating dynamic script' );

		// Build the script HTML
		$script_html = '';

		// Add TCF stub if IAB is enabled
		$iab_enabled = ! empty( get_option( 'cookiebot-iab' ) );
		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		self::debug_log( 'IAB enabled: ' . ( $iab_enabled ? 'yes' : 'no' ) );
		if ( $iab_enabled ) {
			// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
			$script_html .= sprintf(
				'<script src="%s"></script>',
				esc_url( 'https://web.cmp.usercentrics.eu/tcf/stub.js' )
			);
		}

		// Add autoblocker if auto mode is enabled
		$blocking_mode = self::get_cookie_blocking_mode();
		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		self::debug_log( 'Blocking mode: ' . $blocking_mode );
		if ( $blocking_mode === 'auto' ) {
			// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
			$script_html .= sprintf(
				'<script src="%s"></script>',
				esc_url( 'https://web.cmp.usercentrics.eu/modules/autoblocker.js' )
			);
		}

		// Add main banner script
		// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
		$script_html .= sprintf(
			'<script id="usercentrics-cmp" data-settings-id="%s" data-usercentrics="%s" src="%s" async></script>',
			esc_attr( $cbid ),
			esc_attr( 'Usercentrics Consent Management Platform' ),
			esc_url( 'https://web.cmp.usercentrics.eu/ui/loader.js' )
		);

		self::debug_log( 'Final script HTML: ' . $script_html );
		return $script_html;
	}
}
