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

	const COOKIEBOT_PLUGIN_VERSION  = '4.5.10';
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
		register_activation_hook( CYBOT_COOKIEBOT_PLUGIN_DIR . 'cookiebot.php', array( new Cookiebot_Activated(), 'run' ) );
		register_deactivation_hook( CYBOT_COOKIEBOT_PLUGIN_DIR . 'cookiebot.php', array( new Cookiebot_Deactivated(), 'run' ) );

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
		return (bool) get_option( 'cookiebot-uc-onboarded-via-signup', false );
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

	public static function get_auto_blocking_mode() {
		$enabled = get_option( 'cookiebot-uc-auto-blocking-mode', 'default' );
		if ( $enabled === 'default' ) {
			$enabled = '1';
			update_option( 'cookiebot-uc-auto-blocking-mode', $enabled );
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
			self::debug_log( 'Raw data is empty' );
			return '';
		}

		// Use the data directly if it's already an array
		$data = is_array( $raw_data ) ? $raw_data : json_decode( $raw_data, true );

		// Check if we have the new subscription structure
		if ( ! isset( $data['subscriptions']['active'] ) ) {
			self::debug_log( 'No active subscription found' );
			return '';
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
			return 'Premium Trial';
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
		return '';
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
		$network_setting = (string) get_site_option( 'cookiebot-cookie-blocking-mode', 'auto' );
		$setting         = $network_setting === 'manual' ?
			(string) get_option( 'cookiebot-cookie-blocking-mode', $network_setting ) :
			$network_setting;

		return in_array( $setting, $allowed_modes, true ) ? $setting : 'auto';
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
			'cookiebot-banner-enabled' => '1',
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
		if ( $banner_enabled !== '1' ) {
			self::debug_log( 'get_banner_script: Banner disabled in settings' );
			return '';
		}

		// User verification and trial checks
		$user_data = self::get_user_data();

		if ( ! empty( $user_data ) && self::is_trial_expired() ) {
			self::debug_log( 'get_banner_script: Trial expired' );
			return '';
		}

		// Output conditions check
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
		$blocking_mode           = get_option( 'cookiebot-uc-auto-blocking-mode', '1' );
		$blocking_mode_cookiebot = get_option( 'cookiebot-cookie-blocking-mode', 'auto' );

		if ( $blocking_mode === '0' || $blocking_mode_cookiebot === 'manual' ) {
			$blocking_mode = '0';
		}

		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		self::debug_log( 'Blocking mode: ' . $blocking_mode );
		if ( $blocking_mode === '1' ) {
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

	/**
	 * Check if the trial has expired by checking various conditions
	 *
	 * @return bool True if trial has expired, false otherwise
	 */
	public static function is_trial_expired() {
		$user_data = self::get_user_data();

		if ( empty( $user_data ) ) {
			self::debug_log( 'is_trial_expired: No user data found' );
			return false;
		}

		// Check if there's an active subscription
		if ( isset( $user_data['subscriptions']['active'] ) ) {
			$active_subscription = $user_data['subscriptions']['active'];

			// Check subscription status
			if ( isset( $active_subscription['subscription_status'] ) ) {
				$status = $active_subscription['subscription_status'];
				self::debug_log( 'is_trial_expired: Subscription status: ' . $status );

				// For trial statuses, check the trial period
				if ( in_array( $status, array( 'trial_will_be_billed', 'trial_missing_payment' ), true ) ) {
					if ( isset( $active_subscription['trial_end_date'] ) && ! empty( $active_subscription['trial_end_date'] ) ) {
						$trial_end = \DateTime::createFromFormat( 'Y-m-d\TH:i:s.000\Z', $active_subscription['trial_end_date'] );

						if ( $trial_end === false ) {
							self::debug_log( 'is_trial_expired: Failed to parse trial end date' );
							return false;
						}

						$now      = new \DateTime();
						$interval = $now->diff( $trial_end );

						// If the interval is negative (past the end date) or zero, the trial has expired
						$is_expired = $interval->invert || $interval->days === 0;
						self::debug_log( 'is_trial_expired: Is expired? ' . ( $is_expired ? 'yes' : 'no' ) );
						return $is_expired;
					}
				}

				// For active subscriptions, check if it's a trial plan
				if ( in_array( $status, array( 'active_auto_renew', 'active_no_renew' ), true ) ) {
					// Not a trial subscription
					self::debug_log( 'is_trial_expired: Active subscription - not a trial' );
					return false;
				}

				// Cancelled subscriptions are considered expired
				if ( $status === 'cancelled' ) {
					self::debug_log( 'is_trial_expired: Cancelled subscription - expired' );
					return true;
				}
			}
		}

		// If we get here and subscription type is Free, consider trial as expired
		$subscription_type = self::get_subscription_type();
		if ( $subscription_type === 'Free' ) {
			self::debug_log( 'is_trial_expired: Free subscription - considered expired' );
			return true;
		}

		self::debug_log( 'is_trial_expired: Default case - not expired' );
		return false;
	}

	/**
	 * Check if the user has upgraded from the free plan
	 *
	 * @return bool
	 */
	public static function has_upgraded() {
		$user_data = get_option( 'cookiebot-user-data' );

		if ( ! isset( $user_data['subscriptions']['active'] ) ) {
			return false;
		}

		$active_subscription = $user_data['subscriptions']['active'];
		$status              = isset( $active_subscription['subscription_status'] ) ? $active_subscription['subscription_status'] : '';
		$price_plan          = isset( $active_subscription['price_plan'] ) ? $active_subscription['price_plan'] : '';

		if ( empty( $price_plan ) ) {
			return false;
		}

		// Check if the subscription is active (not trial or cancelled)
		if ( in_array( $status, array( 'active_auto_renew', 'active_no_renew' ), true ) ) {
			// Map extended plan names to their base names
			$plan_mapping = array(
				'FreeExtended'      => 'Free',
				'EssentialExtended' => 'Essential',
				'PlusExtended'      => 'Plus',
				'ProExtended'       => 'Pro',
				'BusinessExtended'  => 'Business',
			);

			$base_plan = isset( $plan_mapping[ $price_plan ] ) ? $plan_mapping[ $price_plan ] : $price_plan;
			return true;
		}

		return false;
	}

	/**
	 * Get the number of days left in the trial period
	 *
	 * @return int Number of days left in the trial, or 0 if trial has expired or no trial exists
	 */
	public static function get_trial_days_left() {
		$user_data = self::get_user_data();

		if ( empty( $user_data ) ) {
			self::debug_log( 'get_trial_days_left: No user data found' );
			return 0;
		}

		// Check if there's an active subscription
		if ( isset( $user_data['subscriptions']['active'] ) ) {
			$active_subscription = $user_data['subscriptions']['active'];

			// Check subscription status
			if ( isset( $active_subscription['subscription_status'] ) ) {
				$status = $active_subscription['subscription_status'];
				self::debug_log( 'get_trial_days_left: Subscription status: ' . $status );

				// For trial statuses, calculate days left until trial_end_date
				if ( in_array( $status, array( 'trial_will_be_billed', 'trial_missing_payment' ), true ) ) {
					if ( isset( $active_subscription['trial_end_date'] ) && ! empty( $active_subscription['trial_end_date'] ) ) {
						$trial_end = \DateTime::createFromFormat( 'Y-m-d\TH:i:s.000\Z', $active_subscription['trial_end_date'] );

						if ( $trial_end === false ) {
							self::debug_log( 'get_trial_days_left: Failed to parse trial end date' );
							return 0;
						}

						$now      = new \DateTime();
						$interval = $now->diff( $trial_end );

						// If the interval is negative (past the end date), return 0
						if ( $interval->invert ) {
							self::debug_log( 'get_trial_days_left: Trial has already ended' );
							return 0;
						}

						// Add 1 to include the current day
						$days_left = $interval->days + 1;
						self::debug_log( 'get_trial_days_left: Days left: ' . $days_left );
						return $days_left;
					}
				}

				// For active subscriptions, no trial days left
				if ( in_array( $status, array( 'active_auto_renew', 'active_no_renew' ), true ) ) {
					self::debug_log( 'get_trial_days_left: Active subscription - no trial days' );
					return 0;
				}
			}
		}

		return 0;
	}

	/**
	 * Check if the subscription is in trial status
	 *
	 * @return bool True if subscription is in trial status, false otherwise
	 */
	public static function is_in_trial() {
		$user_data = self::get_user_data();

		if ( empty( $user_data ) || ! isset( $user_data['subscriptions']['active']['subscription_status'] ) ) {
			self::debug_log( 'is_in_trial: No user data or subscription status found' );
			return false;
		}

		$status   = $user_data['subscriptions']['active']['subscription_status'];
		$is_trial = in_array( $status, array( 'trial_will_be_billed', 'trial_missing_payment' ), true );

		self::debug_log( 'is_in_trial: Subscription status: ' . $status );
		self::debug_log( 'is_in_trial: Is trial? ' . ( $is_trial ? 'yes' : 'no' ) );

		return $is_trial;
	}
}
