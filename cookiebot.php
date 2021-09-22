<?php
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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Cookiebot_WP' ) ) :

	final class Cookiebot_WP {
		/**
		 * Plugin version.
		 *
		 * @var   string
		 * @since 1.0.0
		 */
		public $version = '3.11.1';

		/**
		 * @var   Cookiebot_WP The single instance of the class
		 * @since 1.0.0
		 */
		protected static $instance = null;

		/**
		 * Main Cookiebot_WP Instance
		 *
		 * Ensures only one instance of Cookiebot_WP is loaded or can be loaded.
		 *
		 * @return  Cookiebot_WP - Main instance
		 * @since   1.0.0
		 * @static
		 * @version 1.0.0
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
		 * @version 2.1.4
		 * @since   1.0.0
		 * @access  public
		 */
		public function __construct() {
			add_action( 'after_setup_theme', array( $this, 'cookiebot_init' ), 5 );
			register_activation_hook( __FILE__, array( $this, 'activation' ) );
			register_deactivation_hook( __FILE__, 'cookiebot_addons_plugin_deactivated' );

			$this->cookiebot_fix_plugin_conflicts();
		}

		/**
		 * Cookiebot_WP Installation actions
		 *
		 * @version 2.1.4
		 * @since       2.1.4
		 * @accces  public
		 */
		public function activation() {
			//Delay display of recommendation notice in 3 days if not activated ealier
			if ( get_option( 'cookiebot_notice_recommend', false ) === false ) {
				//Not set yet - this must be first activation - delay in 3 days
				update_option( 'cookiebot_notice_recommend', strtotime( '+3 days' ) );
			}
			if ( $this->get_cbid() === '' ) {
				if ( is_multisite() ) {
					update_site_option( 'cookiebot-cookie-blocking-mode', 'auto' );
					update_site_option( 'cookiebot-nooutput-admin', true );
				} else {
					update_option( 'cookiebot-cookie-blocking-mode', 'auto' );
					update_option( 'cookiebot-nooutput-admin', true );
				}
			}

			/**
			 * Run through the addons and enable the default ones
			 */
			if ( ( ! defined( 'COOKIEBOT_ADDONS_STANDALONE' ) || COOKIEBOT_ADDONS_STANDALONE !== true || ! defined( 'COOKIE_ADDONS_LOADED' ) ) ) {
				//Make sure we got a PHP version that works
				if ( version_compare( PHP_VERSION, '5.4.0', '>=' ) ) {
					define( 'COOKIEBOT_URL', plugin_dir_url( __FILE__ ) );
					// activation hook doesn't have the addons loaded - so load it extra when the plugin is activated
					include_once dirname( __FILE__ ) . '/addons/cookiebot-addons-init.php';
					// run activated hook on the addons
					cookiebot_addons_plugin_activated();
				}
			}
		}

		/**
		 * Cookiebot_WP Init Cookiebot.
		 *
		 * @version 3.8.1
		 * @since   1.6.2
		 * @access  public
		 */
		public function cookiebot_init() {
			/* Load Cookiebot Addons Framework */
			$dismiss_addons = false;
			if ( defined( 'CAF_DIR' ) ) {
				$dismiss_addons = true;
				/*add_action('admin_notices', function() {
					?>
					<div class="notice notice-warning">
						<p>
							<?php esc_html_e( 'You have Cookiebot Addons installed.', 'cookiebot' ); ?><br />
							<?php esc_html_e( 'In this and future releases of Cookiebot all available Addons are bundled directly with the Cookiebot plugin.', 'cookiebot' ); ?><br />
							<?php esc_html_e( 'To ensure up-to-date addons - please disable and remove your Cookiebot Addons plugin and configure you addons under "Prior consent" in the Cookiebot menu.', 'cookiebot' ); ?>
						</p>
					</div>
					<?php
				});*/
			} //elseif( $this->get_cookie_blocking_mode() !== 'auto' ) {
			else {
				if ( ( ! defined( 'COOKIEBOT_ADDONS_STANDALONE' ) || COOKIEBOT_ADDONS_STANDALONE !== true || ! defined( 'COOKIE_ADDONS_LOADED' ) ) && $dismiss_addons !== true ) {
					//Make sure we got a PHP version that works
					if ( version_compare( PHP_VERSION, '5.4.0', '>=' ) ) {
						define( 'COOKIEBOT_URL', plugin_dir_url( __FILE__ ) );
						include_once dirname( __FILE__ ) . '/addons/cookiebot-addons-init.php';
					} else {
						define( 'COOKIEBOT_ADDONS_UNSUPPORTED_PHPVERSION', true );
					}
				} else {
					add_action(
						'admin_notices',
						function () {
							?>
							<div class="notice notice-warning">
								<p>
									<?php esc_html_e( 'You are using Cookiebot Addons Standalone.', 'cookiebot' ); ?>
								</p>
							</div>
							<?php
						}
					);
				}
			}
			if ( is_admin() ) {

				//Adding menu to WP admin
				add_action( 'admin_menu', array( $this, 'add_menu' ), 1 );
				add_action( 'admin_menu', array( $this, 'add_menu_legislations' ), 40 );
				add_action( 'admin_menu', array( $this, 'add_menu_debug' ), 50 );

				if ( is_multisite() ) {
					add_action( 'network_admin_menu', array( $this, 'add_network_menu' ), 1 );
					add_action( 'network_admin_edit_cookiebot_network_settings', array( $this, 'network_settings_save' ) );
				}

				//Register settings
				add_action( 'admin_init', array( $this, 'register_cookiebot_settings' ) );

				//Adding dashboard widgets
				add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widgets' ) );

				add_action( 'admin_notices', array( $this, 'cookiebot_admin_notices' ) );
				add_action( 'admin_init', array( $this, 'save_notice_link' ) );

				//Check if we should show cookie consent banner on admin pages
				if ( ! $this->cookiebot_disabled_in_admin() ) {
					//adding cookie banner in admin area too
					add_action( 'admin_head', array( $this, 'add_js' ), - 9999 );
				}
			}

			//Include integration to WP Consent Level API if available
			if ( $this->is_wp_consent_api_active() ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'cookiebot_enqueue_consent_api_scripts' ) );
			}

			// Set up localisation
			load_plugin_textdomain( 'cookiebot', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );

			//add JS
			add_action( 'wp_head', array( $this, 'add_js' ), - 9997 );
			add_action( 'wp_head', array( $this, 'add_GTM' ), - 9998 );
			add_action( 'wp_head', array( $this, 'add_GCM' ), - 9999 );
			add_shortcode( 'cookie_declaration', array( $this, 'show_declaration' ) );

			//Add filter if WP rocket is enabled
			if ( defined( 'WP_ROCKET_VERSION' ) ) {
				add_filter( 'rocket_minify_excluded_external_js', array( $this, 'wp_rocket_exclude_external_js' ) );
			}

			//Add filter
			add_filter( 'sgo_javascript_combine_excluded_external_paths', array( $this, 'sgo_exclude_external_js' ) );

			//Automatic update plugin
			if ( is_admin() || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
				add_filter( 'auto_update_plugin', array( $this, 'automatic_updates' ), 10, 2 );
			}

			//Loading widgets
			include_once dirname( __FILE__ ) . '/widgets/cookiebot-declaration-widget.php';
			add_action( 'widgets_init', array( $this, 'register_widgets' ) );

			//Add Gutenberg block
			add_action( 'init', array( $this, 'gutenberg_block_setup' ) );
			add_action( 'enqueue_block_editor_assets', array( $this, 'gutenberg_block_admin_assets' ) );
		}


		/**
		 * Cookiebot_WP Setup Gutenberg block
		 *
		 * @version 3.7.0
		 * @since       3.7.0
		 */
		public function gutenberg_block_setup() {
			if ( ! function_exists( 'register_block_type' ) ) {
				return; //gutenberg not active
			}

			register_block_type(
				'cookiebot/cookie-declaration',
				array(
					'render_callback' => array( $this, 'block_cookie_declaration' ),
				)
			);
		}

		/**
		 * Cookiebot_WP Add block JS
		 *
		 * @version 3.7.1
		 * @since       3.7.1
		 */
		public function gutenberg_block_admin_assets() {
			//Add Gutenberg Widget
			wp_enqueue_script(
				'cookiebot-declaration',
				plugin_dir_url( __FILE__ ) . 'js/block.js',
				array( 'wp-blocks', 'wp-i18n', 'wp-element' ), // Required scripts for the block
				$this->version
			);
		}

		/**
		 * Cookiebot_WP Render Cookiebot Declaration as Gutenberg block
		 *
		 * @version 3.7.0
		 * @since       3.7.0
		 */
		public function block_cookie_declaration() {
			return $this->show_declaration();
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
			register_widget( 'Cookiebot_Declaration_Widget' );
		}

		/**
		 * Cookiebot_WP Add dashboard widgets to admin
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */

		public function add_dashboard_widgets() {
			wp_add_dashboard_widget( 'cookiebot_status', esc_html__( 'Cookiebot Status', 'cookiebot' ), array( $this, 'dashboard_widget_status' ) );
		}

		/**
		 * Cookiebot_WP Output Dashboard Status Widget
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function dashboard_widget_status() {
			$cbid = $this->get_cbid();
			if ( empty( $cbid ) ) {
				echo '<p>' . esc_html__( 'You need to enter your Cookiebot ID.', 'cookiebot' ) . '</p>';
				echo '<p><a href="options-general.php?page=cookiebot">';
				echo esc_html__( 'Update your Cookiebot ID', 'cookiebot' );
				echo '</a></p>';
			} else {
				echo '<p>' . esc_html_e( 'Your Cookiebot is working!', 'cookiebot' ) . '</p>';
			}
		}

		/**
		 * Cookiebot_WP Add option menu page for Cookiebot
		 *
		 * @version 2.2.0
		 * @since   1.0.0
		 */
		public function add_menu() {
			//Cookiebot Icon SVG base64 encoded
			$icon = 'data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgNzIgNTQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0iI0ZGRkZGRiIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNNDYuODcyNTkwMyA4Ljc3MzU4MzM0QzQxLjk0MzkwMzkgMy4zODI5NTAxMSAzNC44NDI0OTQ2IDAgMjYuOTQ4MjgxOSAwIDEyLjA2NTE1NjggMCAwIDEyLjAyNDQ3NzQgMCAyNi44NTc0MjE5YzAgMTQuODMyOTQ0NSAxMi4wNjUxNTY4IDI2Ljg1NzQyMTkgMjYuOTQ4MjgxOSAyNi44NTc0MjE5IDcuODk0MjEyNyAwIDE0Ljk5NTYyMi0zLjM4Mjk1MDIgMTkuOTI0MzA4NC04Ljc3MzU4MzQtMi44ODk2OTY3LTEuMzY4ODY2My01LjM5OTMxMS0zLjQwNTQzOS03LjMyODA4MzgtNS45MDk2MzU4LTMuMTIxNDMwNiAzLjIwOTQxMDQtNy40OTI5OTQ0IDUuMjA0MTI5MS0xMi4zMzIwMjU4IDUuMjA0MTI5MS05LjQ4NDM0NDQgMC0xNy4xNzI5MjQ3LTcuNjYyNjU3Mi0xNy4xNzI5MjQ3LTE3LjExNTAyMzhzNy42ODg1ODAzLTE3LjExNTAyMzcgMTcuMTcyOTI0Ny0xNy4xMTUwMjM3YzQuNzIzNDgyMiAwIDkuMDAxNTU1MiAxLjkwMDU5MzkgMTIuMTA2MjkyIDQuOTc2MzA5IDEuOTU2OTIzNy0yLjY0MTEzMSA0LjU1MDAyNjMtNC43ODU1MTgzIDcuNTUzODE3Ni02LjIwODQzMTg2eiIvPjxwYXRoIGQ9Ik01NS4zODAzMjgyIDQyLjY1MDE5OTFDNDYuMzMzNzIyNyA0Mi42NTAxOTkxIDM5IDM1LjM0MTIwMzEgMzkgMjYuMzI1MDk5NiAzOSAxNy4zMDg5OTYgNDYuMzMzNzIyNyAxMCA1NS4zODAzMjgyIDEwYzkuMDQ2NjA1NSAwIDE2LjM4MDMyODIgNy4zMDg5OTYgMTYuMzgwMzI4MiAxNi4zMjUwOTk2IDAgOS4wMTYxMDM1LTcuMzMzNzIyNyAxNi4zMjUwOTk1LTE2LjM4MDMyODIgMTYuMzI1MDk5NXptLjAyMTMwOTItNy43NTU2MzQyYzQuNzM3MDI3NiAwIDguNTc3MTQ3MS0zLjgyNzE3MiA4LjU3NzE0NzEtOC41NDgyMjc5IDAtNC43MjEwNTYtMy44NDAxMTk1LTguNTQ4MjI4LTguNTc3MTQ3MS04LjU0ODIyOC00LjczNzAyNzUgMC04LjU3NzE0NyAzLjgyNzE3Mi04LjU3NzE0NyA4LjU0ODIyOCAwIDQuNzIxMDU1OSAzLjg0MDExOTUgOC41NDgyMjc5IDguNTc3MTQ3IDguNTQ4MjI3OXoiLz48L2c+PC9zdmc+';
			add_menu_page( 'Cookiebot', __( 'Cookiebot', 'cookiebot' ), 'manage_options', 'cookiebot', array( $this, 'settings_page' ), $icon );

			add_submenu_page(
				'cookiebot',
				__( 'Cookiebot Settings', 'cookiebot' ),
				__( 'Settings', 'cookiebot' ),
				'manage_options',
				'cookiebot',
				array( $this, 'settings_page' ),
				10
			);
			add_submenu_page(
				'cookiebot',
				__( 'Cookiebot Support', 'cookiebot' ),
				__( 'Support', 'cookiebot' ),
				'manage_options',
				'cookiebot_support',
				array( $this, 'support_page' ),
				20
			);
			add_submenu_page(
				'cookiebot',
				__( 'Google Tag Manager', 'cookiebot' ),
				__( 'Google Tag Manager', 'cookiebot' ),
				'manage_options',
				'cookiebot_GTM',
				array( $this, 'GTM_page' )
			);
			add_submenu_page(
				'cookiebot',
				__( 'IAB', 'cookiebot' ),
				__( 'IAB', 'cookiebot' ),
				'manage_options',
				'cookiebot_iab',
				array( $this, 'iab_page' ),
				30
			);

			if ( defined( 'COOKIEBOT_ADDONS_UNSUPPORTED_PHPVERSION' ) ) {
				//Load prior consent page anyway - but from Cookiebot WP Core plugin.
				add_submenu_page(
					'cookiebot',
					__( 'Prior Consent', 'cookiebot' ),
					__( 'Prior Consent', 'cookiebot' ),
					'manage_options',
					'cookiebot-addons',
					array( $this, 'setting_page_placeholder' ),
					40
				);
			}
		}

		public function add_menu_legislations() {
			add_submenu_page(
				'cookiebot',
				__( 'Legislations', 'cookiebot' ),
				__( 'Legislations', 'cookiebot' ),
				'manage_options',
				'cookiebot-legislations',
				array( $this, 'legislations_page' ),
				50
			);
		}

		/**
		 * Cookiebot_WP Add debug menu - we need to add this seperate to ensure it is placed last (after menu items from Addons).
		 *
		 * @version 3.6.0
		 * @since   3.6.0
		 */
		public function add_menu_debug() {
			add_submenu_page(
				'cookiebot',
				__( 'Debug info', 'cookiebot' ),
				__( 'Debug info', 'cookiebot' ),
				'manage_options',
				'cookiebot_debug',
				array( $this, 'debug_page' )
			);
		}

		/**
		 * Cookiebot_WP Add menu for network sites
		 *
		 * @version 2.2.0
		 * @since       2.2.0
		 */
		public function add_network_menu() {
			$icon = 'data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgNzIgNTQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0iI0ZGRkZGRiIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNNDYuODcyNTkwMyA4Ljc3MzU4MzM0QzQxLjk0MzkwMzkgMy4zODI5NTAxMSAzNC44NDI0OTQ2IDAgMjYuOTQ4MjgxOSAwIDEyLjA2NTE1NjggMCAwIDEyLjAyNDQ3NzQgMCAyNi44NTc0MjE5YzAgMTQuODMyOTQ0NSAxMi4wNjUxNTY4IDI2Ljg1NzQyMTkgMjYuOTQ4MjgxOSAyNi44NTc0MjE5IDcuODk0MjEyNyAwIDE0Ljk5NTYyMi0zLjM4Mjk1MDIgMTkuOTI0MzA4NC04Ljc3MzU4MzQtMi44ODk2OTY3LTEuMzY4ODY2My01LjM5OTMxMS0zLjQwNTQzOS03LjMyODA4MzgtNS45MDk2MzU4LTMuMTIxNDMwNiAzLjIwOTQxMDQtNy40OTI5OTQ0IDUuMjA0MTI5MS0xMi4zMzIwMjU4IDUuMjA0MTI5MS05LjQ4NDM0NDQgMC0xNy4xNzI5MjQ3LTcuNjYyNjU3Mi0xNy4xNzI5MjQ3LTE3LjExNTAyMzhzNy42ODg1ODAzLTE3LjExNTAyMzcgMTcuMTcyOTI0Ny0xNy4xMTUwMjM3YzQuNzIzNDgyMiAwIDkuMDAxNTU1MiAxLjkwMDU5MzkgMTIuMTA2MjkyIDQuOTc2MzA5IDEuOTU2OTIzNy0yLjY0MTEzMSA0LjU1MDAyNjMtNC43ODU1MTgzIDcuNTUzODE3Ni02LjIwODQzMTg2eiIvPjxwYXRoIGQ9Ik01NS4zODAzMjgyIDQyLjY1MDE5OTFDNDYuMzMzNzIyNyA0Mi42NTAxOTkxIDM5IDM1LjM0MTIwMzEgMzkgMjYuMzI1MDk5NiAzOSAxNy4zMDg5OTYgNDYuMzMzNzIyNyAxMCA1NS4zODAzMjgyIDEwYzkuMDQ2NjA1NSAwIDE2LjM4MDMyODIgNy4zMDg5OTYgMTYuMzgwMzI4MiAxNi4zMjUwOTk2IDAgOS4wMTYxMDM1LTcuMzMzNzIyNyAxNi4zMjUwOTk1LTE2LjM4MDMyODIgMTYuMzI1MDk5NXptLjAyMTMwOTItNy43NTU2MzQyYzQuNzM3MDI3NiAwIDguNTc3MTQ3MS0zLjgyNzE3MiA4LjU3NzE0NzEtOC41NDgyMjc5IDAtNC43MjEwNTYtMy44NDAxMTk1LTguNTQ4MjI4LTguNTc3MTQ3MS04LjU0ODIyOC00LjczNzAyNzUgMC04LjU3NzE0NyAzLjgyNzE3Mi04LjU3NzE0NyA4LjU0ODIyOCAwIDQuNzIxMDU1OSAzLjg0MDExOTUgOC41NDgyMjc5IDguNTc3MTQ3IDguNTQ4MjI3OXoiLz48L2c+PC9zdmc+';
			add_menu_page(
				'Cookiebot',
				__( 'Cookiebot', 'cookiebot' ),
				'manage_network_options',
				'cookiebot_network',
				array( $this, 'network_settings_page' ),
				$icon
			);

			add_submenu_page(
				'cookiebot_network',
				__( 'Cookiebot Settings', 'cookiebot' ),
				__( 'Settings', 'cookiebot' ),
				'network_settings_page',
				'cookiebot_network',
				array( $this, 'network_settings_page' )
			);
			add_submenu_page(
				'cookiebot_network',
				__( 'Cookiebot Support', 'cookiebot' ),
				__( 'Support', 'cookiebot' ),
				'network_settings_page',
				'cookiebot_support',
				array( $this, 'support_page' )
			);

		}

		/**
		 * Cookiebot_WP Cookiebot prior consent placeholder page
		 *
		 * @version 1.4.0
		 * @since   1.0.0
		 */
		public function setting_page_placeholder() {
			include __DIR__ . DIRECTORY_SEPARATOR . 'addons' . DIRECTORY_SEPARATOR . 'view/admin/settings/setting-page.php';
		}

		/**
		 * Cookiebot_WP Register Cookiebot settings
		 *
		 * @version 3.9.0
		 * @since   1.0.0
		 */
		public function register_cookiebot_settings() {
			register_setting( 'cookiebot', 'cookiebot-cbid' );
			register_setting( 'cookiebot', 'cookiebot-language' );
			register_setting( 'cookiebot', 'cookiebot-nooutput' );
			register_setting( 'cookiebot', 'cookiebot-nooutput-admin' );
			register_setting( 'cookiebot', 'cookiebot-output-logged-in' );
			register_setting( 'cookiebot', 'cookiebot-autoupdate' );
			register_setting( 'cookiebot', 'cookiebot-script-tag-uc-attribute' );
			register_setting( 'cookiebot', 'cookiebot-script-tag-cd-attribute' );
			register_setting( 'cookiebot', 'cookiebot-cookie-blocking-mode' );
			register_setting( 'cookiebot', 'cookiebot-consent-mapping' );
			register_setting( 'cookiebot-iab', 'cookiebot-iab' );
			register_setting( 'cookiebot-legislations', 'cookiebot-ccpa' );
			register_setting( 'cookiebot-legislations', 'cookiebot-ccpa-domain-group-id' );
			register_setting( 'cookiebot-gtm', 'cookiebot-gtm' );
			register_setting( 'cookiebot-gtm', 'cookiebot-gtm-id' );
			register_setting( 'cookiebot-gtm', 'cookiebot-data-layer' );
			register_setting( 'cookiebot-gtm', 'cookiebot-gcm' );
		}

		/**
		 * Cookiebot_WP Automatic update plugin if activated
		 *
		 * @version 2.2.0
		 * @since       1.5.0
		 */
		public function automatic_updates( $update, $item ) {
			//Do not update from subsite on a multisite installation
			if ( is_multisite() && ! is_main_site() ) {
				return $update;
			}

			//Check if we have everything we need
			$item = (array) $item;
			if ( ! isset( $item['new_version'] ) || ! isset( $item['slug'] ) ) {
				return $update;
			}

			//It is not Cookiebot
			if ( $item['slug'] !== 'cookiebot' ) {
				return $update;
			}

			// Check if cookiebot autoupdate is disabled
			if ( ! get_option( 'cookiebot-autoupdate', false ) ) {
				return $update;
			}

			// Check if multisite autoupdate is disabled
			if ( is_multisite() && ! get_site_option( 'cookiebot-autoupdate', false ) ) {
				return $update;
			}

			return true;
		}


		/**
		 * Cookiebot_WP Get list of supported languages
		 *
		 * @version 1.4.0
		 * @since       1.4.0
		 */
		public static function get_supported_languages() {
			$supported_languages       = array();
			$supported_languages['nb'] = __( 'Norwegian Bokmål', 'cookiebot' );
			$supported_languages['tr'] = __( 'Turkish', 'cookiebot' );
			$supported_languages['de'] = __( 'German', 'cookiebot' );
			$supported_languages['cs'] = __( 'Czech', 'cookiebot' );
			$supported_languages['da'] = __( 'Danish', 'cookiebot' );
			$supported_languages['sq'] = __( 'Albanian', 'cookiebot' );
			$supported_languages['he'] = __( 'Hebrew', 'cookiebot' );
			$supported_languages['ko'] = __( 'Korean', 'cookiebot' );
			$supported_languages['it'] = __( 'Italian', 'cookiebot' );
			$supported_languages['nl'] = __( 'Dutch', 'cookiebot' );
			$supported_languages['vi'] = __( 'Vietnamese', 'cookiebot' );
			$supported_languages['ta'] = __( 'Tamil', 'cookiebot' );
			$supported_languages['is'] = __( 'Icelandic', 'cookiebot' );
			$supported_languages['ro'] = __( 'Romanian', 'cookiebot' );
			$supported_languages['si'] = __( 'Sinhala', 'cookiebot' );
			$supported_languages['ca'] = __( 'Catalan', 'cookiebot' );
			$supported_languages['bg'] = __( 'Bulgarian', 'cookiebot' );
			$supported_languages['uk'] = __( 'Ukrainian', 'cookiebot' );
			$supported_languages['zh'] = __( 'Chinese', 'cookiebot' );
			$supported_languages['en'] = __( 'English', 'cookiebot' );
			$supported_languages['ar'] = __( 'Arabic', 'cookiebot' );
			$supported_languages['hr'] = __( 'Croatian', 'cookiebot' );
			$supported_languages['th'] = __( 'Thai', 'cookiebot' );
			$supported_languages['el'] = __( 'Greek', 'cookiebot' );
			$supported_languages['lt'] = __( 'Lithuanian', 'cookiebot' );
			$supported_languages['pl'] = __( 'Polish', 'cookiebot' );
			$supported_languages['lv'] = __( 'Latvian', 'cookiebot' );
			$supported_languages['fr'] = __( 'French', 'cookiebot' );
			$supported_languages['id'] = __( 'Indonesian', 'cookiebot' );
			$supported_languages['mk'] = __( 'Macedonian', 'cookiebot' );
			$supported_languages['et'] = __( 'Estonian', 'cookiebot' );
			$supported_languages['pt'] = __( 'Portuguese', 'cookiebot' );
			$supported_languages['ga'] = __( 'Irish', 'cookiebot' );
			$supported_languages['ms'] = __( 'Malay', 'cookiebot' );
			$supported_languages['sl'] = __( 'Slovenian', 'cookiebot' );
			$supported_languages['ru'] = __( 'Russian', 'cookiebot' );
			$supported_languages['ja'] = __( 'Japanese', 'cookiebot' );
			$supported_languages['hi'] = __( 'Hindi', 'cookiebot' );
			$supported_languages['sk'] = __( 'Slovak', 'cookiebot' );
			$supported_languages['es'] = __( 'Spanish', 'cookiebot' );
			$supported_languages['sv'] = __( 'Swedish', 'cookiebot' );
			$supported_languages['sr'] = __( 'Serbian', 'cookiebot' );
			$supported_languages['fi'] = __( 'Finnish', 'cookiebot' );
			$supported_languages['eu'] = __( 'Basque', 'cookiebot' );
			$supported_languages['hu'] = __( 'Hungarian', 'cookiebot' );
			asort( $supported_languages, SORT_LOCALE_STRING );

			return $supported_languages;
		}

		/**
		 * Cookiebot_WP Output settings page
		 *
		 * @version 3.9.0
		 * @since   1.0.0
		 */
		public function settings_page() {
			wp_enqueue_style( 'cookiebot-consent-mapping-table', plugins_url( 'css/consent_mapping_table.css', __FILE__ ), array(), '3.5.0' );

			/* Check if multisite */
			if ( $is_ms = is_multisite() ) {
				//Receive settings from multisite - this might change the way we render the form
				$network_cbid                 = get_site_option( 'cookiebot-cbid', '' );
				$network_scrip_tag_uc_attr    = get_site_option( 'cookiebot-script-tag-uc-attribute', 'custom' );
				$network_scrip_tag_cd_attr    = get_site_option( 'cookiebot-script-tag-cd-attribute', 'custom' );
				$network_cookie_blocking_mode = get_site_option( 'cookiebot-cookie-blocking-mode', 'manual' );
			}
			?>
			<div class="wrap">
				<h1><?php esc_html_e( 'Cookiebot Settings', 'cookiebot' ); ?></h1>
				<a href="https://www.cookiebot.com">
					<img src="<?php echo esc_url( plugins_url( 'cookiebot-logo.png', __FILE__ ) ); ?>" style="float:right;margin-left:1em;">
				</a>
				<p>
					<?php
					$cookiebot_gdpr_url = 'https://www.cookiebot.com/goto/gdpr';
					printf(
						esc_html__(
							'Cookiebot enables your website to comply with current legislation in the EU on the use of cookies for user tracking and profiling. The EU ePrivacy Directive requires prior, informed consent of your site users, while the  %1$s %2$s.',
							'cookiebot'
						),
						sprintf(
							'<a href="%s" target="_blank">%s</a>',
							esc_url( $cookiebot_gdpr_url ),
							esc_html__( 'General Data Protection Regulation (GDPR)', 'cookiebot' )
						),
						esc_html__(
							' requires you to document each consent. At the same time you must be able to account for what user data you share with embedded third-party services on your website and where in the world the user data is sent.',
							'cookiebot'
						)
					);
					?>
				</p>
				<form method="post" action="options.php">
					<?php settings_fields( 'cookiebot' ); ?>
					<?php do_settings_sections( 'cookiebot' ); ?>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Cookiebot ID', 'cookiebot' ); ?></th>
							<td>
								<input
										type="text"
										name="cookiebot-cbid"
										value="<?php echo esc_attr( get_option( 'cookiebot-cbid' ) ); ?>"
										<?php if ( $is_ms ) : ?>
										placeholder="<?php echo esc_attr( $network_cbid ); ?>"
										<?php endif; ?>
										style="width:300px"
								/>
								<p class="description">
									<?php esc_html_e( 'Need an ID?', 'cookiebot' ); ?>
									<a href="https://www.cookiebot.com/goto/signup" target="_blank">
									<?php
									esc_html_e(
										'Sign up for free on cookiebot.com',
										'cookiebot'
									);
									?>
																									</a>
								</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<?php esc_html_e( 'Cookie-blocking mode', 'cookiebot' ); ?>
							</th>
							<td>
								<?php
								$cbm = get_option( 'cookiebot-cookie-blocking-mode', 'manual' );
								if ( $is_ms && $network_cookie_blocking_mode != 'custom' ) {
									$cbm = $network_cookie_blocking_mode;
								}
								?>
								<label>
									<input type="radio" name="cookiebot-cookie-blocking-mode" value="auto" <?php checked( 'auto', $cbm, true ); ?> />
									<?php esc_html_e( 'Automatic', 'cookiebot' ); ?>
								</label>
								&nbsp; &nbsp;
								<label>
									<input type="radio" name="cookiebot-cookie-blocking-mode" value="manual" <?php checked( 'manual', $cbm, true ); ?> />
									<?php esc_html_e( 'Manual', 'cookiebot' ); ?>
								</label>
								<p class="description">
									<?php esc_html_e( 'Automatic block cookies (except necessary) until the user has given their consent.', 'cookiebot' ); ?>
									<a href="https://support.cookiebot.com/hc/en-us/articles/360009063100-Automatic-Cookie-Blocking-How-does-it-work-"
									   target="_blank">
										<?php esc_html_e( 'Learn more', 'cookiebot' ); ?>
									</a>
								</p>
								<script>
									jQuery( document ).ready( function ( $ ) {
										var cookieBlockingMode = '<?php echo esc_js( $cbm ); ?>';
										$( 'input[type=radio][name=cookiebot-cookie-blocking-mode]' ).on( 'change', function () {
											if ( this.value == 'auto' && cookieBlockingMode != this.value ) {
												$( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 0.4 );
												$( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', true );
											}
											if ( this.value == 'manual' && cookieBlockingMode != this.value ) {
												$( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 1 );
												$( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', false );
											}
											cookieBlockingMode = this.value;
										} );
										if ( cookieBlockingMode == 'auto' ) {
											$( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 0.4 );
											$( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', true );
										}
									} );
								</script>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Cookiebot Language', 'cookiebot' ); ?></th>
							<td>
								<div>
									<select name="cookiebot-language" id="cookiebot-language">
										<?php
										$current_lang = $this->get_language( true );
										?>
										<option value=""><?php esc_html_e( 'Default (Autodetect)', 'cookiebot' ); ?></option>
										<option value="_wp"<?php echo ( $current_lang == '_wp' ) ? ' selected' : ''; ?>>
																	  <?php
																		esc_html_e(
																			'Use WordPress Language',
																			'cookiebot'
																		);
																		?>
																														</option>
										<?php
										$supported_languages = $this->get_supported_languages();
										foreach ( $supported_languages as $lang_code => $lang_name ) :
											?>
											<option value="<?php echo esc_attr( $lang_code ); ?>"
											<?php if ( $current_lang === $lang_code ) : ?>
											selected
											<?php endif; ?>
											><?php echo esc_html( $lang_name ); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="notice inline notice-warning notice-alt cookiebot-notice" style="padding:12px;font-size:13px;display:inline-block;">
									<div style="<?php echo ( $current_lang === '' ) ? 'display:none;' : ''; ?>" id="info_lang_specified">
										<?php esc_html_e( 'You need to add the language in the Cookiebot administration tool.', 'cookiebot' ); ?>
									</div>
									<div style="<?php echo ( $current_lang === '' ) ? '' : 'display:none;'; ?>" id="info_lang_autodetect">
										<?php
										esc_html_e(
											'You need to add all languages that you want auto-detected in the Cookiebot administration tool.',
											'cookiebot'
										);
										?>
										 <br/>
										<?php
										esc_html_e(
											'The auto-detect checkbox needs to be enabled in the Cookiebot administration tool.',
											'cookiebot'
										);
										?>
										<br/>
										<?php
										esc_html_e(
											'If the auto-detected language is not supported, Cookiebot will use the default language.',
											'cookiebot'
										);
										?>
									</div>
									<br/>

									<a href="#" id="show_add_language_guide"><?php esc_html_e( 'Show guide to add languages', 'cookiebot' ); ?></a>
									&nbsp;
									<a href="https://support.cookiebot.com/hc/en-us/articles/360003793394-How-do-I-set-the-language-of-the-consent-banner-dialog-"
									   target="_blank">
										<?php esc_html_e( 'Read more here', 'cookiebot' ); ?>
									</a>

									<div id="add_language_guide" style="display:none;">
										<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '/assets/guide_add_language.gif' ); ?>"
											 alt="Add language in Cookiebot administration tool"/>
										<br/>
										<a href="#" id="hide_add_language_guide"><?php esc_html_e( 'Hide guide', 'cookiebot' ); ?></a>
									</div>
								</div>
								<script>
									jQuery( document ).ready( function ( $ ) {
										$( '#show_add_language_guide' ).on( 'click', function ( e ) {
											e.preventDefault();
											$( '#add_language_guide' ).slideDown();
											$( this ).hide();
										} );
										$( '#hide_add_language_guide' ).on( 'click', function ( e ) {
											e.preventDefault();
											$( '#add_language_guide' ).slideUp();
											$( '#show_add_language_guide' ).show();
										} );

										$( '#cookiebot-language' ).on( 'change', function () {
											if ( this.value === '' ) {
												$( '#info_lang_autodetect' ).show();
												$( '#info_lang_specified' ).hide();
											} else {
												$( '#info_lang_autodetect' ).hide();
												$( '#info_lang_specified' ).show();
											}
										} );
									} );
								</script>

							</td>
						</tr>
					</table>
					<script>
						jQuery( document ).ready( function ( $ ) {
							$( '.cookiebot_fieldset_header' ).on( 'click', function ( e ) {
								e.preventDefault();
								$( this ).next().slideToggle();
								$( this ).toggleClass( 'active' );
							} );
						} );
					</script>
					<style type="text/css">
						.cookiebot_fieldset_header {
							cursor: pointer;
						}

						.cookiebot_fieldset_header::after {
							content: "\f140";
							font: normal 24px/1 dashicons;
							position: relative;
							top: 5px;
						}

						.cookiebot_fieldset_header.active::after {
							content: "\f142";
						}
					</style>
					<h3 id="advanced_settings_link" class="cookiebot_fieldset_header"><?php esc_html_e( 'Advanced settings', 'cookiebot' ); ?></h3>
					<div id="advanced_settings" style="display:none;">
						<table class="form-table">
							<tr valign="top" id="cookiebot-setting-async">
								<th scope="row">
									<?php esc_html_e( 'Add async or defer attribute', 'cookiebot' ); ?>
									<br/><?php esc_html_e( 'Consent banner script tag', 'cookiebot' ); ?>
								</th>
								<td>
									<?php
									$cv       = get_option( 'cookiebot-script-tag-uc-attribute', 'async' );
									$disabled = false;
									if ( $is_ms && $network_scrip_tag_uc_attr !== 'custom' ) {
										$disabled = true;
										$cv       = $network_scrip_tag_uc_attr;
									}
									?>
									<label>
										<input type="radio" name="cookiebot-script-tag-uc-attribute"<?php echo ( $disabled ) ? ' disabled' : ''; ?>
											   value="" <?php checked( '', $cv, true ); ?> />
										<i><?php esc_html_e( 'None', 'cookiebot' ); ?></i>
									</label>
									&nbsp; &nbsp;
									<label>
										<input type="radio" name="cookiebot-script-tag-uc-attribute"<?php echo ( $disabled ) ? ' disabled' : ''; ?>
											   value="async" <?php checked( 'async', $cv, true ); ?> />
										async
									</label>
									&nbsp; &nbsp;
									<label>
										<input type="radio" name="cookiebot-script-tag-uc-attribute"<?php echo ( $disabled ) ? ' disabled' : ''; ?>
											   value="defer" <?php checked( 'defer', $cv, true ); ?> />
										defer
									</label>
									<p class="description">
										<?php
										if ( $disabled ) {
											echo '<b>' . esc_html__(
												'Network setting applied. Please contact website administrator to change this setting.',
												'cookiebot'
											) . '</b><br />';
										}
										?>
										<?php esc_html_e( 'Add async or defer attribute to Cookiebot script tag. Default: async', 'cookiebot' ); ?>
									</p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<?php esc_html_e( 'Add async or defer attribute', 'cookiebot' ); ?>
									<br/><?php esc_html_e( 'Cookie declaration script tag', 'cookiebot' ); ?>
								</th>
								<td>
									<?php
									$cv       = get_option( 'cookiebot-script-tag-cd-attribute', 'async' );
									$disabled = false;
									if ( $is_ms && $network_scrip_tag_cd_attr !== 'custom' ) {
										$disabled = true;
										$cv       = $network_scrip_tag_cd_attr;
									}
									?>
									<label>
										<input type="radio" name="cookiebot-script-tag-cd-attribute"<?php echo ( $disabled ) ? ' disabled' : ''; ?>
											   value="" <?php checked( '', $cv, true ); ?> />
										<i><?php esc_html_e( 'None', 'cookiebot' ); ?></i>
									</label>
									&nbsp; &nbsp;
									<label>
										<input type="radio" name="cookiebot-script-tag-cd-attribute"<?php echo ( $disabled ) ? ' disabled' : ''; ?>
											   value="async" <?php checked( 'async', $cv, true ); ?> />
										async
									</label>
									&nbsp; &nbsp;
									<label>
										<input type="radio" name="cookiebot-script-tag-cd-attribute"<?php echo ( $disabled ) ? ' disabled' : ''; ?>
											   value="defer" <?php checked( 'defer', $cv, true ); ?> />
										defer
									</label>
									<p class="description">
										<?php
										if ( $disabled ) {
											echo '<b>' . esc_html__(
												'Network setting applied. Please contact website administrator to change this setting.',
												'cookiebot'
											) . '</b><br />';
										}
										?>
										<?php esc_html_e( 'Add async or defer attribute to Cookiebot script tag. Default: async', 'cookiebot' ); ?>
									</p>
								</td>
							</tr>
							<?php
							if ( ! is_multisite() ) {
								?>
								<tr valign="top">
									<th scope="row"><?php esc_html_e( 'Auto-update Cookiebot', 'cookiebot' ); ?></th>
									<td>
										<input type="checkbox" name="cookiebot-autoupdate" value="1"
										<?php
										checked(
											1,
											get_option( 'cookiebot-autoupdate', false ),
											true
										);
										?>
																										 />
										<p class="description">
											<?php esc_html_e( 'Automatic update your Cookiebot plugin when new releases becomes available.', 'cookiebot' ); ?>
										</p>
									</td>
								</tr>
								<?php
							}
							?>
							<tr valign="top" id="cookiebot-setting-hide-popup">
								<th scope="row"><?php esc_html_e( 'Hide Cookie Popup', 'cookiebot' ); ?></th>
								<td>
									<?php
									$disabled = false;
									if ( $is_ms && get_site_option( 'cookiebot-nooutput', false ) ) {
										$disabled = true;
										echo '<input type="checkbox" checked disabled />';
									} else {
										?>
										<input type="checkbox" name="cookiebot-nooutput" value="1"
										<?php
										checked(
											1,
											get_option( 'cookiebot-nooutput', false ),
											true
										);
										?>
																									 />
										<?php
									}
									?>
									<p class="description">
										<?php
										if ( $disabled ) {
											echo '<b>' . esc_html__(
												'Network setting applied. Please contact website administrator to change this setting.',
												'cookiebot'
											) . '</b><br />';
										}
										?>
										<b>
										<?php
										esc_html_e(
											'This checkbox will remove the cookie consent banner from your website. The <i>[cookie_declaration]</i> shortcode will still be available.',
											'cookiebot'
										);
										?>
											</b><br/>
										<?php
										esc_html_e(
											'If you are using Google Tag Manager (or equal), you need to add the Cookiebot script in your Tag Manager.',
											'cookiebot'
										);
										?>
										<br/>
										<a href="https://support.cookiebot.com/hc/en-us/articles/360003793854-Google-Tag-Manager-deployment" target="_blank">
											<?php esc_html_e( 'See a detailed guide here', 'cookiebot' ); ?>
										</a>
									</p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php esc_html_e( 'Disable Cookiebot in WP Admin', 'cookiebot' ); ?></th>
								<td>
									<?php
									$disabled = false;
									if ( $is_ms && get_site_option( 'cookiebot-nooutput-admin', false ) ) {
										echo '<input type="checkbox" checked disabled />';
										$disabled = true;
									} else {
										?>
										<input type="checkbox" name="cookiebot-nooutput-admin" value="1"
										<?php
										checked(
											1,
											get_option( 'cookiebot-nooutput-admin', false ),
											true
										);
										?>
																											 />
										<?php
									}
									?>
									<p class="description">
										<?php
										if ( $disabled ) {
											echo '<b>' . esc_html__( 'Network setting applied. Please contact website administrator to change this setting.' ) . '</b><br />';
										}
										?>
										<b><?php esc_html_e( 'This checkbox will disable Cookiebot in the WordPress Admin area.', 'cookiebot' ); ?></b>
									</p>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php esc_html_e( 'Enable Cookiebot on front end while logged in', 'cookiebot' ); ?></th>
								<td>
									<?php
									$disabled = false;
									if ( $is_ms && get_site_option( 'cookiebot-output-logged-in', false ) ) {
										echo '<input type="checkbox" checked disabled />';
										$disabled = true;
									} else {
										?>
										<input type="checkbox" name="cookiebot-output-logged-in" value="1"
										<?php
										checked(
											1,
											get_option( 'cookiebot-output-logged-in', false ),
											true
										);
										?>
																											 />
										<?php
									}
									?>
									<p class="description">
										<?php
										if ( $disabled ) {
											echo '<b>' . esc_html__( 'Network setting applied. Please contact website administrator to change this setting.' ) . '</b><br />';
										}
										?>
										<b><?php esc_html_e( 'This checkbox will enable Cookiebot on front end while you\'re logged in', 'cookiebot' ); ?></b>
									</p>
								</td>
							</tr>
						</table>
					</div>
					<?php if ( $this->is_wp_consent_api_active() ) { ?>
						<h3 id="consent_level_api_settings" class="cookiebot_fieldset_header">
						<?php
						esc_html_e(
							'Consent Level API Settings',
							'cookiebot'
						);
						?>
																								</h3>
						<div id="consent_level_api_settings" style="display:none;">
							<p>
							<?php
							esc_html_e(
								'WP Consent Level API and Cookiebot categorise cookies a bit different. The default settings should fit mosts needs - but if you need to change the mapping you are able to do it below.',
								'cookiebot'
							);
							?>
								</p>

							<?php
							$mDefault = $this->get_default_wp_consent_api_mapping();

							$m = $this->get_wp_consent_api_mapping();

							$consentTypes = array( 'preferences', 'statistics', 'marketing' );
							$states       = array_reduce(
								$consentTypes,
								function ( $t, $v ) {
									$newt = array();
									if ( empty( $t ) ) {
										$newt = array(
											array( $v => true ),
											array( $v => false ),
										);
									} else {
										foreach ( $t as $item ) {
											$newt[] = array_merge( $item, array( $v => true ) );
											$newt[] = array_merge( $item, array( $v => false ) );
										}
									}

									return $newt;
								},
								array()
							);

							?>


							<table class="widefat striped consent_mapping_table">
								<thead>
								<tr>
									<th><?php esc_html_e( 'Cookiebot categories', 'cookiebot' ); ?></th>
									<th class="consent_mapping"><?php esc_html_e( 'WP Consent Level categories', 'cookiebot' ); ?></th>
								</tr>
								</thead>
								<?php
								foreach ( $states as $state ) {

									$key   = array();
									$key[] = 'n=1';
									$key[] = 'p=' . ( $state['preferences'] ? '1' : '0' );
									$key[] = 's=' . ( $state['statistics'] ? '1' : '0' );
									$key[] = 'm=' . ( $state['marketing'] ? '1' : '0' );
									$key   = implode( ';', $key );
									?>
									<tr valign="top">
										<td>
											<div class="cb_consent">
											<span class="forceconsent">
												<?php esc_html_e( 'Necessary', 'cookiebot' ); ?>
											</span>
												<span class="<?php echo( $state['preferences'] ? 'consent' : 'noconsent' ); ?>">
												<?php esc_html_e( 'Preferences', 'cookiebot' ); ?>
											</span>
												<span class="<?php echo( $state['statistics'] ? 'consent' : 'noconsent' ); ?>">
												<?php esc_html_e( 'Statistics', 'cookiebot' ); ?>
											</span>
												<span class="<?php echo( $state['marketing'] ? 'consent' : 'noconsent' ); ?>">
												<?php esc_html_e( 'Marketing', 'cookiebot' ); ?>
											</span>
											</div>
										</td>
										<td>
											<div class="consent_mapping">
												<label><input type="checkbox" name="cookiebot-consent-mapping[<?php echo esc_attr( $key ); ?>][functional]"
															  data-default-value="1" value="1" checked disabled
													> <?php esc_html_e( 'Functional', 'cookiebot' ); ?> </label>
												<label><input type="checkbox" name="cookiebot-consent-mapping[<?php echo esc_attr( $key ); ?>][preferences]"
															  data-default-value="<?php echo esc_attr( $mDefault[ $key ]['preferences'] ); ?>" value="1"
														<?php
														if ( $m[ $key ]['preferences'] ) {
															echo 'checked';
														}
														?>
													> <?php esc_html_e( 'Preferences', 'cookiebot' ); ?> </label>
												<label><input type="checkbox" name="cookiebot-consent-mapping[<?php echo esc_attr( $key ); ?>][statistics]"
															  data-default-value="<?php echo esc_attr( $mDefault[ $key ]['statistics'] ); ?>" value="1"
														<?php
														if ( $m[ $key ]['statistics'] ) {
															echo 'checked';
														}
														?>
													> <?php esc_html_e( 'Statistics', 'cookiebot' ); ?> </label>
												<label><input type="checkbox" name="cookiebot-consent-mapping[<?php echo esc_attr( $key ); ?>][statistics-anonymous]"
															  data-default-value="<?php echo esc_attr( $mDefault[ $key ]['statistics-anonymous'] ); ?>" value="1"
														<?php
														if ( $m[ $key ]['statistics-anonymous'] ) {
															echo 'checked';
														}
														?>
													> <?php esc_html_e( 'Statistics Anonymous', 'cookiebot' ); ?></label>
												<label><input type="checkbox" name="cookiebot-consent-mapping[<?php echo esc_attr( $key ); ?>][marketing]"
															  data-default-value="<?php echo esc_attr( $mDefault[ $key ]['marketing'] ); ?>" value="1"
														<?php
														if ( $m[ $key ]['marketing'] ) {
															echo 'checked';
														}
														?>
													> <?php esc_html_e( 'Marketing', 'cookiebot' ); ?></label>
											</div>
										</td>
									</tr>
									<?php
								}
								?>
								<tfoot>
								<tr>
									<td colspan="2" style="text-align:right;">
										<button class="button" onclick="return resetConsentMapping();">
										<?php
										esc_html_e(
											'Reset to default mapping',
											'cookiebot'
										);
										?>
																										</button>
									</td>
								</tr>
								</tfoot>
							</table>
							<script>
								function resetConsentMapping() {
									if ( confirm( 'Are you sure you want to reset to default consent mapping?' ) ) {
										jQuery( '.consent_mapping_table input[type=checkbox]' ).each( function () {
											if ( !this.disabled ) {
												this.checked = ( jQuery( this ).data( 'default-value' ) == '1' ) ? true : false;
											}
										} );
									}
									return false;
								}
							</script>
						</div>
					<?php } ?>
					<?php submit_button(); ?>
				</form>
			</div>
			<?php
		}

		/**
		 * Cookiebot_WP Cookiebot network setting page
		 *
		 * @version 2.2.0
		 * @since   2.2.0
		 */
		public function network_settings_page() {
			?>
			<div class="wrap">
				<h1><?php esc_html_e( 'Cookiebot Network Settings', 'cookiebot' ); ?></h1>
				<a href="https://www.cookiebot.com">
					<img src="<?php echo esc_url( plugins_url( 'cookiebot-logo.png', __FILE__ ) ); ?>" style="float:right;margin-left:1em;">
				</a>
				<p>
					<?php
					$cookiebot_gdpr_url = 'https://www.cookiebot.com/goto/gdpr';
					printf(
						esc_html__(
							'Cookiebot enables your website to comply with current legislation in the EU on the use of cookies for user tracking and profiling. The EU ePrivacy Directive requires prior, informed consent of your site users, while the  %1$s %2$s.',
							'cookiebot'
						),
						sprintf(
							'<a href="%s" target="_blank">%s</a>',
							esc_url( $cookiebot_gdpr_url ),
							esc_html__( 'General Data Protection Regulation (GDPR)', 'cookiebot' )
						),
						esc_html__(
							' requires you to document each consent. At the same time you must be able to account for what user data you share with embedded third-party services on your website and where in the world the user data is sent.',
							'cookiebot'
						)
					);
					?>
				</p>
				<p>
					<b><big style="color:red;">
					<?php
					esc_html_e(
						'The settings below is network wide settings. See notes below each field.',
						'cookiebot'
					);
					?>
												</big></b>
				</p>
				<form method="post" action="edit.php?action=cookiebot_network_settings">
					<?php wp_nonce_field( 'cookiebot-network-settings' ); ?>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Network Cookiebot ID', 'cookiebot' ); ?></th>
							<td>
								<input type="text" name="cookiebot-cbid" value="<?php echo esc_attr( get_site_option( 'cookiebot-cbid', '' ) ); ?>"
									   style="width:300px"/>
								<p class="description">
									<b>
									<?php
									esc_html_e(
										'If added this will be the default Cookiebot ID for all subsites. Subsites are able to override the Cookiebot ID.',
										'cookiebot'
									);
									?>
										</b>
									<br/>
									<?php esc_html_e( 'Need an ID?', 'cookiebot' ); ?>
									<a href="https://www.cookiebot.com/goto/signup" target="_blank">
									<?php
									esc_html_e(
										'Sign up for free on cookiebot.com',
										'cookiebot'
									);
									?>
																									</a>
								</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<?php esc_html_e( 'Cookie-blocking mode', 'cookiebot' ); ?>
							</th>
							<td>
								<?php
								$cbm = get_site_option( 'cookiebot-cookie-blocking-mode', 'manual' );
								?>
								<label>
									<input type="radio" name="cookiebot-cookie-blocking-mode" value="auto" <?php checked( 'auto', $cbm, true ); ?> />
									<?php esc_html_e( 'Automatic', 'cookiebot' ); ?>
								</label>
								&nbsp; &nbsp;
								<label>
									<input type="radio" name="cookiebot-cookie-blocking-mode" value="manual" <?php checked( 'manual', $cbm, true ); ?> />
									<?php esc_html_e( 'Manual', 'cookiebot' ); ?>
								</label>
								<p class="description">
									<?php esc_html_e( 'Should Cookiebot automatic block cookies by tagging known tags.', 'cookiebot' ); ?>
								</p>
							</td>
						</tr>
						<script>
							jQuery( document ).ready( function ( $ ) {
								var cookieBlockingMode = '<?php echo esc_js( $cbm ); ?>';
								$( 'input[type=radio][name=cookiebot-cookie-blocking-mode]' ).on( 'change', function () {
									if ( this.value == 'auto' && cookieBlockingMode != this.value ) {
										$( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 0.4 );
										$( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', true );
									}
									if ( this.value == 'manual' && cookieBlockingMode != this.value ) {
										$( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 1 );
										$( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', false );
									}
									cookieBlockingMode = this.value;
								} );
								if ( cookieBlockingMode == 'auto' ) {
									$( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 0.4 );
									$( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', true );
								}
							} );
						</script>
						<tr valign="top" id="cookiebot-setting-async">
							<th scope="row">
								<?php esc_html_e( 'Add async or defer attribute', 'cookiebot' ); ?>
								<br/><?php esc_html_e( 'Consent banner script tag', 'cookiebot' ); ?>
							</th>
							<td>
								<?php
								$cv = get_site_option( 'cookiebot-script-tag-uc-attribute', 'custom' );
								?>
								<label>
									<input type="radio" name="cookiebot-script-tag-uc-attribute" value="" <?php checked( '', $cv, true ); ?> />
									<i><?php esc_html_e( 'None', 'cookiebot' ); ?></i>
								</label>
								&nbsp; &nbsp;
								<label>
									<input type="radio" name="cookiebot-script-tag-uc-attribute" value="async" <?php checked( 'async', $cv, true ); ?> />
									async
								</label>
								&nbsp; &nbsp;
								<label>
									<input type="radio" name="cookiebot-script-tag-uc-attribute" value="defer" <?php checked( 'defer', $cv, true ); ?> />
									defer
								</label>
								&nbsp; &nbsp;
								<label>
									<input type="radio" name="cookiebot-script-tag-uc-attribute" value="custom" <?php checked( 'custom', $cv, true ); ?> />
									<i><?php esc_html_e( 'Choose per subsite', 'cookiebot' ); ?></i>
								</label>
								<p class="description">
									<b>
									<?php
									esc_html_e(
										'Setting will apply for all subsites. Subsites will not be able to override.',
										'cookiebot'
									);
									?>
										</b><br/>
									<?php esc_html_e( 'Add async or defer attribute to Cookiebot script tag. Default: Choose per subsite', 'cookiebot' ); ?>
								</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<?php esc_html_e( 'Add async or defer attribute', 'cookiebot' ); ?>
								<br/><?php esc_html_e( 'Cookie declaration script tag', 'cookiebot' ); ?>
							</th>
							<td>
								<?php
								$cv = get_site_option( 'cookiebot-script-tag-cd-attribute', 'custom' );
								?>
								<label>
									<input type="radio" name="cookiebot-script-tag-cd-attribute" value="" <?php checked( '', $cv, true ); ?> />
									<i><?php esc_html_e( 'None', 'cookiebot' ); ?></i>
								</label>
								&nbsp; &nbsp;
								<label>
									<input type="radio" name="cookiebot-script-tag-cd-attribute" value="async" <?php checked( 'async', $cv, true ); ?> />
									async
								</label>
								&nbsp; &nbsp;
								<label>
									<input type="radio" name="cookiebot-script-tag-cd-attribute" value="defer" <?php checked( 'defer', $cv, true ); ?> />
									defer
								</label>
								&nbsp; &nbsp;
								<label>
									<input type="radio" name="cookiebot-script-tag-cd-attribute" value="custom" <?php checked( 'custom', $cv, true ); ?> />
									<i><?php esc_html_e( 'Choose per subsite', 'cookiebot' ); ?></i>
								</label>
								<p class="description">
									<b>
									<?php
									esc_html_e(
										'Setting will apply for all subsites. Subsites will not be able to override.',
										'cookiebot'
									);
									?>
										</b><br/>
									<?php esc_html_e( 'Add async or defer attribute to Cookiebot script tag. Default: Choose per subsite', 'cookiebot' ); ?>
								</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Auto-update Cookiebot', 'cookiebot' ); ?></th>
							<td>
								<input type="checkbox" name="cookiebot-autoupdate" value="1"
								<?php
								checked(
									1,
									get_site_option( 'cookiebot-autoupdate', false ),
									true
								);
								?>
																								 />
								<p class="description">
									<?php esc_html_e( 'Automatic update your Cookiebot plugin when new releases becomes available.', 'cookiebot' ); ?>
								</p>
							</td>
						</tr>
						<tr valign="top" id="cookiebot-setting-hide-popup">
							<th scope="row"><?php esc_html_e( 'Hide Cookie Popup', 'cookiebot' ); ?></th>
							<td>
								<input type="checkbox" name="cookiebot-nooutput" value="1"
								<?php
								checked(
									1,
									get_site_option( 'cookiebot-nooutput', false ),
									true
								);
								?>
																							 />
								<p class="description">
									<b>
									<?php
									esc_html_e(
										'Remove the cookie consent banner from all subsites. This cannot be changed by subsites. The <i>[cookie_declaration]</i> shortcode will still be available.',
										'cookiebot'
									);
									?>
										</b><br/>
									<?php
									esc_html_e(
										'If you are using Google Tag Manager (or equal), you need to add the Cookiebot script in your Tag Manager.',
										'cookiebot'
									);
									?>
									<br/>
									<?php
									esc_html_e(
										'<a href="https://support.cookiebot.com/hc/en-us/articles/360003793854-Google-Tag-Manager-deployment" target="_blank">See a detailed guide here</a>',
										'cookiebot'
									);
									?>
								</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Hide Cookie Popup in WP Admin', 'cookiebot' ); ?></th>
							<td>
								<input type="checkbox" name="cookiebot-nooutput-admin" value="1"
								<?php
								checked(
									1,
									get_site_option( 'cookiebot-nooutput-admin', false ),
									true
								);
								?>
																									 />
								<p class="description">
									<b>
									<?php
									esc_html_e(
										'Remove the cookie consent banner the WordPress Admin area for all subsites. This cannot be changed by subsites.',
										'cookiebot'
									);
									?>
										</b>
								</p>
							</td>
						</tr>
					</table>
					<?php submit_button(); ?>
				</form>
			</div>
			<?php
		}


		/**
		 * Cookiebot_WP Cookiebot save network settings
		 *
		 * @version 2.2.0
		 * @since   2.2.0
		 */
		public function network_settings_save() {
			check_admin_referer( 'cookiebot-network-settings' );

			update_site_option( 'cookiebot-cbid', $_POST['cookiebot-cbid'] );
			update_site_option( 'cookiebot-script-tag-uc-attribute', $_POST['cookiebot-script-tag-uc-attribute'] );
			update_site_option( 'cookiebot-script-tag-cd-attribute', $_POST['cookiebot-script-tag-cd-attribute'] );
			update_site_option( 'cookiebot-autoupdate', $_POST['cookiebot-autoupdate'] );
			update_site_option( 'cookiebot-nooutput', $_POST['cookiebot-nooutput'] );
			update_site_option( 'cookiebot-nooutput-admin', $_POST['cookiebot-nooutput-admin'] );
			update_site_option( 'cookiebot-cookie-blocking-mode', $_POST['cookiebot-cookie-blocking-mode'] );

			wp_redirect(
				add_query_arg(
					array(
						'page'    => 'cookiebot_network',
						'updated' => true,
					),
					network_admin_url( 'admin.php' )
				)
			);
			exit;
		}

		/**
		 * Cookiebot_WP Cookiebot support page
		 *
		 * @version 2.2.0
		 * @since   2.0.0
		 */
		public function support_page() {
			?>
			<div class="wrap">
				<h1><?php esc_html_e( 'Support', 'cookiebot' ); ?></h1>
				<h2><?php esc_html_e( 'How to find my Cookiebot ID', 'cookiebot' ); ?></h2>
				<div>
				<ol>
					<li>
					<?php
					echo sprintf(
						// translators: the first placeholder string will be replaced with a html anchor open tag and the second placeholder string will be replaced by the html anchor closing tag
						esc_html__( 'Log in to your %1$sCookiebot account%2$s.', 'cookiebot' ),
						'<a href="https://www.cookiebot.com/goto/account" target="_blank">',
                        '</a>'
					);
					?>
					</li>
					<li>
					<?php
					echo sprintf(
						// translators: the placeholder strings denote the positions of <b>, </b>, <b> and </b> HTML tags
						esc_html__( 'Go to %1$sManage%2$s > %3$sSettings%4$s and add setup your Cookiebot', 'cookiebot' ),
						'<b>',
                        '</b>',
                        '<b>',
                        '</b>'
					);
					?>
					</li>
					<li>
					<?php
					echo sprintf(
						// translators: the placeholder strings denote the positions of <b> and </b> HTML tags
						esc_html__( 'Go to the %1$s"Your scripts"%2$s tab', 'cookiebot' ),
						'<b>',
                        '</b>'
					);
					?>
					</li>
					<li><?php esc_html_e( 'Copy the value inside the data-cid parameter - eg.: abcdef12-3456-7890-abcd-ef1234567890', 'cookiebot' ); ?></li>
					<li>
					<?php
					echo sprintf(
						// translators: the placeholder strings denote the positions of <b> and </b> HTML tags
						esc_html__( 'Add %1$s[cookie_declaration]%2$s shortcode to a page to show the declation', 'cookiebot' ),
						'<b>',
                        '</b>'
					);
					?>
					</li>
					<li><?php esc_html_e( 'Remember to change your scripts as descripted below', 'cookiebot' ); ?></li>
				</ol>
				</div>
				<h2><?php esc_html_e( 'Add the Cookie Declaration to your website', 'cookiebot' ); ?></h2>
				<p>
					<?php
					echo sprintf(
					// translators: the placeholder strings denote the positions of <b> and </b> HTML tags
						esc_html__( 'Use the shortcode %1$s[cookie_declaration]%2$s to add the cookie declaration a page or post. The cookie declaration will always show the latest version from Cookiebot.', 'cookiebot' ),
						'<b>',
                        '</b>'
					);
					?>
					<br/>
					<?php
					echo sprintf(
					// translators: the placeholder strings denote the positions of <i>, </i>, <b> and </b> HTML tags
						esc_html__( 'If you need to force language of the cookie declaration, you can add the %1$slang%2$s attribute. Eg. %3$s[cookie_declaration lang="de"]%4$s.', 'cookiebot' ),
						'<i>',
                        '</i>',
                        '<b>',
                        '</b>'
					);
					?>
				</p>
				<p>
					<a href="https://www.youtube.com/watch?v=OCXz2bt4H_w" target="_blank" class="button">
					<?php
					esc_html_e(
						'Watch video demonstration',
						'cookiebot'
					);
					?>
																											</a>
				</p>
				<h2><?php esc_html_e( 'Update your script tags', 'cookiebot' ); ?></h2>
				<p>
					<?php
					esc_html_e(
						'To enable prior consent, apply the attribute "data-cookieconsent" to cookie-setting script tags on your website. Set the comma-separated value to one or more of the cookie categories "preferences", "statistics" and "marketing" in accordance with the types of cookies being set by each script. Finally change the attribute "type" from "text/javascript" to "text/plain". Example on modifying an existing Google Analytics Universal script tag.',
						'cookiebot'
					);
					?>
				</p>
				<code>
					<?php
					$output = <<<HTML
<script type="text/plain" data-cookieconsent="statistics">
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-00000000-0', 'auto');
ga('send', 'pageview');
</script>
HTML;
					echo nl2br( esc_html( $output ) );
					?>
				</code>
				<p>
					<a href="https://www.youtube.com/watch?v=MeHycvV2QCQ" target="_blank" class="button">
					<?php
					esc_html_e(
						'Watch video demonstration',
						'cookiebot'
					);
					?>
																											</a>
				</p>

				<h2><?php esc_html_e( 'Helper function to update your scripts', 'cookiebot' ); ?></h2>
				<p>
					<?php
					esc_html_e(
						'You are able to update your scripts yourself. However, Cookiebot also offers a small helper function that makes the work easier.',
						'cookiebot'
					);
					?>
					<br/>
					<?php esc_html_e( 'Update your script tags this way:', 'cookiebot' ); ?>
				</p>
				<?php
				printf(
					esc_html__( '%1$s to %2$s', 'cookiebot' ),
					'<code>' . esc_html( '<script type="text/javascript">' ) . '</code>',
					'<code>' . esc_html( '<script<?php echo cookiebot_assist(\'marketing\') ?>>' ) . '</code>'
				);
				?>
			</div>
			<?php
		}

		/**
		 * Cookiebot_WP Google Tag Manager page
		 *
		 * @version 3.8.1
		 * @since   3.8.1
		 */

		public function GTM_page() {
			?>
			<div class="wrap">
				<h1><?php esc_html_e( 'Google Tag Manager', 'cookiebot' ); ?></h1>

				<form method="post" action="options.php"
					  style="display: grid; grid-template-columns: 35% 65%; grid-row-gap: 20px; width: 700px; align-items: center;">
					<?php settings_fields( 'cookiebot-gtm' ); ?>
					<?php do_settings_sections( 'cookiebot-gtm' ); ?>

					<p><?php esc_html_e( 'Enable GTM', 'cookiebot' ); ?></p>
					<div class="GTM_check">
						<input type="checkbox" name="cookiebot-gtm" id="cookiebot-gtm" value="1" <?php checked( 1, get_option( 'cookiebot-gtm' ), true ); ?>
							   style="float: left; margin: 2px 4px 0 0">
						<p style="margin: 0; font-style: italic;">
						<?php
						esc_html_e(
							'For more details about Cookiebot and Google Tag Manager click',
							'cookiebot'
						);
						?>
																	<a target="_blank" href="https://www.cookiebot.com/en/google-tag-manager-and-gdpr-compliance-with-cookiebot/"
													style="margin: 0; font-style: italic;">&nbsp;<?php esc_html_e( 'here', 'cookiebot' ); ?></a></p>
					</div>

					<p><?php esc_html_e( 'GTM ID', 'cookiebot' ); ?></p>
					<input type="text" name="cookiebot-gtm-id" id="cookiebot-gtm-id" value="<?php echo esc_attr( get_option( 'cookiebot-gtm-id' ) ); ?>"
						   style="height: 30px;">

					<p><?php esc_html_e( 'DataLayer name', 'cookiebot' ); ?></p>
					<div>
						<input type="text" name="cookiebot-data-layer" id="data_layer" placeholder="dataLayer"
							   value="<?php echo esc_attr( get_option( 'cookiebot-data-layer' ) ); ?>" style="height: 30px;">
						<p style="margin: 0;"><?php esc_html_e( 'Optional, only change if necessary', 'cookiebot' ); ?></p>
					</div>

					<p><?php esc_html_e( 'Google Consent Mode', 'cookiebot' ); ?></p>
					<div class="GTM_check">
						<input type="checkbox" name="cookiebot-gcm" id="gcm" value="1" <?php checked( 1, get_option( 'cookiebot-gcm' ), true ); ?>
							   style="float: left; margin: 2px 4px 0 0">
						<p style="margin: 0; font-style: italic;">
						<?php
						esc_html_e(
							'For more details about Cookiebot and Google Consent Mode click',
							'cookiebot'
						);
						?>
																	<a target="_blank"
													href="https://support.cookiebot.com/hc/en-us/articles/360016047000-Cookiebot-and-Google-Consent-Mode"
													style="margin: 0; font-style: italic;">&nbsp;<?php esc_html_e( 'here', 'cookiebot' ); ?></a></p>
					</div>
					<input type="submit" value="Save" name="gtm_save"
						   style="background-color: rgb(0, 124, 186); color: white; padding: 5px 10px; border: none; border-radius: 5px; justify-self: start;">
				</form>
			</div>
			<?php
		}

		/**
		 * Cookiebot_WP Cookiebot IAB page
		 *
		 * @version 2.0.0
		 * @since   2.0.0
		 */
		public function iab_page() {
			?>
			<div class="wrap">
				<h1><?php esc_html_e( 'IAB', 'cookiebot' ); ?></h1>

				<p>
					<?php
					echo sprintf(
						esc_html__(
							'For more details about Cookiebot\'s IAB integration, see %1$sarticle about cookiebot and the IAB consent framework%2$s',
							'cookiebot'
						),
						'<a href="https://support.cookiebot.com/hc/en-us/articles/360007652694-Cookiebot-and-the-IAB-Consent-Framework" target="_blank">',
						'</a>'
					);
					?>
				</p>

				<form method="post" action="options.php">
					<?php settings_fields( 'cookiebot-iab' ); ?>
					<?php do_settings_sections( 'cookiebot-iab' ); ?>

					<label><?php esc_html_e( 'Enable IAB integration', 'cookiebot' ); ?></label>
					<input type="checkbox" name="cookiebot-iab" value="1" <?php checked( 1, get_option( 'cookiebot-iab' ), true ); ?>>

					<?php submit_button(); ?>
				</form>
			</div>
			<?php
		}

		/**
		 * Cookiebot_WP Cookiebot legislations page
		 *
		 * @version 3.6.6
		 * @since   3.6.6
		 */
		public function legislations_page() {
			?>
			<div class="wrap">
				<h1><?php esc_html_e( 'Legislations', 'cookiebot' ); ?></h1>

				<p>
					<?php
					echo sprintf(
						esc_html__(
							'For more details about Cookiebot\'s CCPA Legislation integration, see %1$sarticle about cookiebot and the CCPA compliance%2$s',
							'cookiebot'
						),
						'<a href="https://support.cookiebot.com/hc/en-us/articles/360010932419-Use-multiple-banners-on-the-same-website-support-both-CCPA-GDPR-compliance-" target="_blank">',
						'</a>'
					);
					?>
				</p>

				<form method="post" action="options.php">
					<?php settings_fields( 'cookiebot-legislations' ); ?>
					<?php do_settings_sections( 'cookiebot-legislations' ); ?>


					<table class="form-table">
						<tbody>
						<tr valign="top">
							<th scope="row"><label><?php esc_html_e( 'Enable CCPA configuration for visitors from California', 'cookiebot' ); ?></label></th>
							<td>
								<input type="checkbox" name="cookiebot-ccpa" value="1" <?php checked( 1, get_option( 'cookiebot-ccpa' ), true ); ?>>
							</td>
						</tr>
						<tr>
							<th valign="top"><label><?php esc_html_e( 'Domain Group ID', 'cookiebot' ); ?></label></th>
							<td>
								<input type="text" style="width: 300px;" name="cookiebot-ccpa-domain-group-id"
									   value="<?php echo esc_attr( get_option( 'cookiebot-ccpa-domain-group-id' ) ); ?>">
							</td>
						</tr>
						</tbody>
					</table>

					<?php submit_button(); ?>
				</form>
			</div>
			<?php
		}

		/**
		 * Cookiebot_WP Debug Page
		 *
		 * @version   3.9.
		 * @since     3.6.0
		 */

		public function debug_page() {
			global $wpdb;

			include_once ABSPATH . 'wp-admin/includes/plugin.php';
			$plugins        = get_plugins();
			$active_plugins = get_option( 'active_plugins' );

			//$foo = new cookiebot_addons\lib\Settings_Service;
			//$addons = $foo->get_active_addons();

			$debugStr  = '';
			$debugStr .= '##### Debug Information for ' . get_site_url() . ' generated at ' . date( 'c' ) . " #####\n\n";
			$debugStr .= 'WordPress Version: ' . get_bloginfo( 'version' ) . "\n";
			$debugStr .= 'WordPress Language: ' . get_bloginfo( 'language' ) . "\n";
			$debugStr .= 'PHP Version: ' . phpversion() . "\n";
			$debugStr .= 'MySQL Version: ' . $wpdb->db_version() . "\n";
			$debugStr .= "\n--- Cookiebot Information ---\n";
			$debugStr .= 'Plugin Version: ' . $this->version . "\n";
			$debugStr .= 'Cookiebot ID: ' . $this->get_cbid() . "\n";
			$debugStr .= 'Blocking mode: ' . get_option( 'cookiebot-cookie-blocking-mode' ) . "\n";
			$debugStr .= 'Language: ' . get_option( 'cookiebot-language' ) . "\n";
			$debugStr .= 'IAB: ' . ( get_option( 'cookiebot-iab' ) == '1' ? 'Enabled' : 'Not enabled' ) . "\n";
			$debugStr .= 'CCPA banner for visitors from California: ' . ( get_option( 'cookiebot-ccpa' ) == '1' ? 'Enabled' : 'Not enabled' ) . "\n";
			$debugStr .= 'CCPA domain group id: ' . get_option( 'cookiebot-ccpa-domain-group-id' ) . "\n";
			$debugStr .= 'Add async/defer to banner tag: ' . ( get_option( 'cookiebot-script-tag-uc-attribute' ) != '' ? get_option( 'cookiebot-script-tag-uc-attribute' ) : 'None' ) . "\n";
			$debugStr .= 'Add async/defer to declaration tag: ' . ( get_option( 'cookiebot-script-tag-cd-attribute' ) != '' ? get_option( 'cookiebot-script-tag-cd-attribute' ) : 'None' ) . "\n";
			$debugStr .= 'Auto update: ' . ( get_option( 'cookiebot-autoupdate' ) == '1' ? 'Enabled' : 'Not enabled' ) . "\n";
			$debugStr .= 'Hide Cookie Popup: ' . ( get_option( 'cookiebot-nooutput' ) == '1' ? 'Yes' : 'No' ) . "\n";
			$debugStr .= 'Disable Cookiebot in WP Admin: ' . ( get_option( 'cookiebot-nooutput-admin' ) == '1' ? 'Yes' : 'No' ) . "\n";
			$debugStr .= 'Enable Cookiebot on front end while logged in: ' . ( get_option( 'cookiebot-output-logged-in' ) == '1' ? 'Yes' : 'No' ) . "\n";
			$debugStr .= 'Banner tag: ' . $this->add_js( false ) . "\n";
			$debugStr .= 'Declaration tag: ' . $this->show_declaration() . "\n";

			if ( get_option( 'cookiebot-gtm' ) != false ) {
				$debugStr .= 'GTM tag: ' . $this->add_GTM( false ) . "\n";
			}

			if ( get_option( 'cookiebot-gcm' ) != false ) {
				$debugStr .= 'GCM tag: ' . $this->add_GCM( false ) . "\n";
			}

			if ( $this->is_wp_consent_api_active() ) {
				$debugStr .= "\n--- WP Consent Level API Mapping ---\n";
				$debugStr .= 'F = Functional, N = Necessary, P = Preferences, M = Marketing, S = Statistics, SA = Statistics Anonymous' . "\n";
				$m         = $this->get_wp_consent_api_mapping();
				foreach ( $m as $k => $v ) {
					$cb = array();

					$debugStr .= strtoupper( str_replace( ';', ', ', $k ) ) . '   =>   ';

					$debugStr .= 'F=1, ';
					$debugStr .= 'P=' . $v['preferences'] . ', ';
					$debugStr .= 'M=' . $v['marketing'] . ', ';
					$debugStr .= 'S=' . $v['statistics'] . ', ';
					$debugStr .= 'SA=' . $v['statistics-anonymous'] . "\n";

				}
			}

			if ( class_exists( 'cookiebot_addons\Cookiebot_Addons' ) ) {
				$ca             = new cookiebot_addons\Cookiebot_Addons();
				$settingservice = $ca->container->get( 'Settings_Service_Interface' );
				$addons         = $settingservice->get_active_addons();
				$debugStr      .= "\n--- Activated Cookiebot Addons ---\n";
				foreach ( $addons as $addon ) {
					$debugStr .= $addon->get_addon_name() . ' (' . implode( ', ', $addon->get_cookie_types() ) . ")\n";
				}
			}

			$debugStr .= "\n--- Activated Plugins ---\n";
			foreach ( $active_plugins as $p ) {
				if ( $p != 'cookiebot/cookiebot.php' ) {
					$debugStr .= $plugins[ $p ]['Name'] . ' (Version: ' . $plugins[ $p ]['Version'] . ")\n";
				}
			}

			$debugStr .= "\n##### Debug Information END #####";

			?>
			<div class="wrap">
				<h1><?php esc_html_e( 'Debug information', 'cookiebot' ); ?></h1>
				<p>
				<?php
				esc_html_e(
					'The information below is for debugging purpose. If you have any issues with your Cookiebot integration, the information below is usefull for a supporter to help you the best way.',
					'cookiebot'
				);
				?>
					</p>
				<p>
					<button class="button button-primary" onclick="copyDebugInfo();">
					<?php
					esc_html_e(
						'Copy debug information to clipboard',
						'cookiebot'
					);
					?>
																						</button>
				</p>
				<textarea
						cols="100"
						rows="40"
						style="width:800px;max-width:100%;"
						id="cookiebot-debug-info"
						readonly><?php echo esc_html( $debugStr ); ?></textarea>
				<script>
					function copyDebugInfo() {
						var t = document.getElementById( "cookiebot-debug-info" );
						t.select();
						t.setSelectionRange( 0, 99999 );
						document.execCommand( "copy" );
					}
				</script>
			</div>
			<?php
		}

		/**
		 * Cookiebot_WP Add Cookiebot JS to <head>
		 *
		 * @version 3.9.0
		 * @since   1.0.0
		 */
		public function add_js( $print_tag = true ) {
			$cbid = $this->get_cbid();
			if ( ! empty( $cbid ) && ! defined( 'COOKIEBOT_DISABLE_ON_PAGE' ) ) {
				if ( is_multisite() && get_site_option( 'cookiebot-nooutput', false ) ) {
					return; //Is multisite - and disabled output is checked as network setting
				}

				if ( get_option( 'cookiebot-nooutput', false ) ) {
					return; //Do not show JS - output disabled
				}

				if ( $this->get_cookie_blocking_mode() == 'auto' && $this->can_current_user_edit_theme() && $print_tag !== false && get_site_option( 'cookiebot-output-logged-in' ) == false ) {
					return;
				}

				$lang = $this->get_language();

				if ( ! is_multisite() || get_site_option( 'cookiebot-script-tag-uc-attribute', 'custom' ) == 'custom' ) {
					$tagAttr = get_option( 'cookiebot-script-tag-uc-attribute', 'async' );
				} else {
					$tagAttr = get_site_option( 'cookiebot-script-tag-uc-attribute' );
				}

				if ( $print_tag === false ) {
					ob_start();
				}
				?>
				<script type="text/javascript"
						id="Cookiebot"
						src="https://consent.cookiebot.com/uc.js"
						data-cbid="<?php echo esc_attr( $cbid ); ?>"
						<?php if ( (bool) get_option( 'cookiebot-iab' ) !== false ) : ?>
							data-framework="IAB"
						<?php endif; ?>
						<?php if ( (bool) get_option( 'cookiebot-ccpa' ) !== false ) : ?>
							data-georegions="{'region':'US-06','cbid':'<?php echo esc_attr( get_option( 'cookiebot-ccpa-domain-group-id' ) ); ?>'}"
						<?php endif; ?>
						<?php if ( (bool) get_option( 'cookiebot-gtm' ) !== false ) : ?>
							<?php if ( empty( get_option( 'cookiebot-data-layer' ) ) ) : ?>
							data-layer-name="dataLayer"
							<?php else : ?>
							data-layer-name="<?php echo esc_attr( get_option( 'cookiebot-data-layer' ) ); ?>"
							<?php endif; ?>
						<?php endif; ?>
						<?php if ( ! empty( $lang ) ) : ?>
							data-culture="<?php echo esc_attr( strtoupper( $lang ) ); ?>"
						<?php endif; ?>
						<?php if ( $this->get_cookie_blocking_mode() === 'auto' ) : ?>
							data-blockingmode="auto"
						<?php else : ?>
							<?php echo esc_attr( $tagAttr ); ?>
						<?php endif; ?>
				></script>
				<?php
				if ( $print_tag === false ) {
					return ob_get_clean();
				}
			}
		}

		/**
		 * Cookiebot_WP Add Google Tag Manager JS to <head>
		 *
		 * @version 3.8.1
		 * @since   3.8.1
		 */

		public function add_GTM( $print_tag = true ) {
			if ( (bool) get_option( 'cookiebot-gtm' ) !== false ) {
				if ( empty( get_option( 'cookiebot-data-layer' ) ) ) {
					$data_layer = 'dataLayer';
				} else {
					$data_layer = get_option( 'cookiebot-data-layer' );
				}

				if ( $print_tag === false ) {
					ob_start();
				}
				?>
				<script>
					<?php if ( get_option( 'cookiebot-iab' ) ) : ?>
					window ["gtag_enable_tcf_support"] = true;
					<?php endif; ?>
					(function (w, d, s, l, i) {
					  w[l] = w[l] || []; w[l].push({'gtm.start':new Date().getTime(), event: 'gtm.js'});
					  var f = d.getElementsByTagName(s)[0],  j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
					  j.async = true; j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
					  f.parentNode.insertBefore(j, f);})
					(window, document, 'script', '<?php echo esc_js( $data_layer ); ?>', '<?php echo esc_js( get_option( 'cookiebot-gtm-id' ) ); ?>');
				</script>
				<?php
				if ( $print_tag === false ) {
					return ob_get_clean();
				}
			}
		}

		/**
		 * Cookiebot_WP Add Google Consent Mode JS to <head>
		 *
		 * @version 3.8.1
		 * @since   3.8.1
		 */

		public function add_GCM( $print_tag = true ) {
			if ( (bool) get_option( 'cookiebot-gcm' ) !== false ) {
				if ( empty( get_option( 'cookiebot-data-layer' ) ) ) {
					$data_layer = 'dataLayer';
				} else {
					$data_layer = get_option( 'cookiebot-data-layer' );
				}

				if ( $print_tag === false ) {
					ob_start();
				}
				?>
				<script data-cookieconsent="ignore">
				  (function(w,d,l){w[l]=w[l]||[];function gtag(){w[l].push(arguments)}
					gtag("consent","default",{ad_storage:d,analytics_storage:d,wait_for_update:500,});
					gtag("set", "ads_data_redaction", true);})(window,"denied","<?php echo esc_js( $data_layer ); ?>");
				</script>
				<?php
				if ( $print_tag === false ) {
					return ob_get_clean();
				}
			}
		}

		/**
		 * Returns true if an user is logged in and has an edit_themes capability
		 *
		 * @return bool
		 *
		 * @since 3.3.1
		 * @version 3.4.1
		 */
		public function can_current_user_edit_theme() {
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
		 * Cookiebot_WP Output declation shortcode [cookie_declaration]
		 * Support attribute lang="LANGUAGE_CODE". Eg. lang="en".
		 *
		 * @version 2.2.0
		 * @since   1.0.0
		 */
		public function show_declaration( $atts = array() ) {
			$cbid = $this->get_cbid();
			$lang = '';
			if ( ! empty( $cbid ) ) {

				$atts = shortcode_atts(
					array(
						'lang' => $this->get_language(),
					),
					$atts,
					'cookie_declaration'
				);

				if ( ! empty( $atts['lang'] ) ) {
					$lang = ' data-culture="' . strtoupper( $atts['lang'] ) . '"'; //Use data-culture to define language
				}

				if ( ! is_multisite() || get_site_option( 'cookiebot-script-tag-cd-attribute', 'custom' ) == 'custom' ) {
					$tagAttr = get_option( 'cookiebot-script-tag-cd-attribute', 'async' );
				} else {
					$tagAttr = get_site_option( 'cookiebot-script-tag-cd-attribute' );
				}

				return '<script id="CookieDeclaration" src="https://consent.cookiebot.com/' . $cbid . '/cd.js"' . $lang . ' type="text/javascript" ' . $tagAttr . '></script>';
			} else {
				return esc_html__( 'Please add your Cookiebot ID to show Cookie Declarations', 'cookiebot' );
			}
		}

		/**
		 * Cookiebot_WP Get cookiebot cbid
		 *
		 * @version 2.2.0
		 * @since       1.0.0
		 */
		public static function get_cbid() {
			$cbid = get_option( 'cookiebot-cbid' );
			if ( is_multisite() && ( $network_cbid = get_site_option( 'cookiebot-cbid' ) ) ) {
				if ( empty( $cbid ) ) {
					return $network_cbid;
				}
			}

			return $cbid;
		}

		/**
		 * Cookiebot_WP Get cookie blocking mode (auto | manual)
		 *
		 * @version 2.2.0
		 * @since       1.0.0
		 */
		public static function get_cookie_blocking_mode() {
			$cbm = get_option( 'cookiebot-cookie-blocking-mode' );
			if ( is_multisite() && ( $network_cbm = get_site_option( 'cookiebot-cookie-blocking-mode' ) ) ) {
				if ( empty( $cbm ) ) {
					return $network_cbm;
				}
			}
			if ( empty( $cbm ) ) {
				$cbm = 'manual';
			}

			return $cbm;
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
		 * Cookiebot_WP Get the language code for Cookiebot
		 *
		 * @version 1.4.0
		 * @since   1.4.0
		 */
		public function get_language( $onlyFromSetting = false ) {
			// Get language set in setting page - if empty use WP language info
			$lang = get_option( 'cookiebot-language' );
			if ( ! empty( $lang ) ) {
				if ( $lang != '_wp' ) {
					return $lang;
				}
			}

			if ( $onlyFromSetting ) {
				return $lang; //We want only to get if already set
			}

			//Language not set - use WP language
			if ( $lang == '_wp' ) {
				$lang = get_bloginfo( 'language' ); //Gets language in en-US format
				if ( ! empty( $lang ) ) {
					list( $lang ) = explode( '-', $lang ); //Changes format from eg. en-US to en.
				}
			}

			return $lang;
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
			return wp_rocket_exclude_external_js( $exclude_list );
		}


		/**
		 * Cookiebot_WP Check if WP Cookie Consent API is active
		 *
		 * @version 3.5.0
		 * @since       3.5.0
		 */
		public function is_wp_consent_api_active() {
			if ( class_exists( 'WP_CONSENT_API' ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Cookiebot_WP Default consent level mappings
		 *
		 * @version 3.5.0
		 * @since   3.5.0
		 */
		public function get_default_wp_consent_api_mapping() {
			return array(
				'n=1;p=1;s=1;m=1' =>
					array(
						'preferences'          => 1,
						'statistics'           => 1,
						'statistics-anonymous' => 0,
						'marketing'            => 1,
					),
				'n=1;p=1;s=1;m=0' =>
					array(
						'preferences'          => 1,
						'statistics'           => 1,
						'statistics-anonymous' => 1,
						'marketing'            => 0,
					),
				'n=1;p=1;s=0;m=1' =>
					array(
						'preferences'          => 1,
						'statistics'           => 0,
						'statistics-anonymous' => 0,
						'marketing'            => 1,
					),
				'n=1;p=1;s=0;m=0' =>
					array(
						'preferences'          => 1,
						'statistics'           => 0,
						'statistics-anonymous' => 0,
						'marketing'            => 0,
					),
				'n=1;p=0;s=1;m=1' =>
					array(
						'preferences'          => 0,
						'statistics'           => 1,
						'statistics-anonymous' => 0,
						'marketing'            => 1,
					),
				'n=1;p=0;s=1;m=0' =>
					array(
						'preferences'          => 0,
						'statistics'           => 1,
						'statistics-anonymous' => 0,
						'marketing'            => 0,
					),
				'n=1;p=0;s=0;m=1' =>
					array(
						'preferences'          => 0,
						'statistics'           => 0,
						'statistics-anonymous' => 0,
						'marketing'            => 1,
					),
				'n=1;p=0;s=0;m=0' =>
					array(
						'preferences'          => 0,
						'statistics'           => 0,
						'statistics-anonymous' => 0,
						'marketing'            => 0,
					),
			);

		}

		/**
		 * Cookiebot_WP Get the mapping between Consent Level API and Cookiebot
		 * Returns array where key is the consent level api category and value
		 * is the mapped Cookiebot category.
		 *
		 * @version 3.5.0
		 * @since   3.5.0
		 */
		public function get_wp_consent_api_mapping() {
			$mDefault = $this->get_default_wp_consent_api_mapping();
			$mapping  = get_option( 'cookiebot-consent-mapping', $mDefault );

			$mapping = ( '' === $mapping ) ? $mDefault : $mapping;

			foreach ( $mDefault as $k => $v ) {
				if ( ! isset( $mapping[ $k ] ) ) {
					$mapping[ $k ] = $v;
				} else {
					foreach ( $v as $vck => $vcv ) {
						if ( ! isset( $mapping[ $k ][ $vck ] ) ) {
							$mapping[ $k ][ $vck ] = $vcv;
						}
					}
				}
			}

			return $mapping;
		}

		/**
		 * Cookiebot_WP Enqueue JS for integration with WP Consent Level API
		 *
		 * @version 3.5.0
		 * @since   3.5.0
		 */
		public function cookiebot_enqueue_consent_api_scripts() {
			wp_register_script(
				'cookiebot-wp-consent-level-api-integration',
				plugins_url( 'cookiebot/js/cookiebot-wp-consent-level-api-integration.js', 'cookiebot' )
			);
			wp_enqueue_script( 'cookiebot-wp-consent-level-api-integration' );
			wp_localize_script( 'cookiebot-wp-consent-level-api-integration', 'cookiebot_category_mapping', $this->get_wp_consent_api_mapping() );
		}


		/**
		 * Display admin notice for recommending cookiebot
		 *
		 * @version 2.0.5
		 * @since 2.0.5
		 */
		public function cookiebot_admin_notices() {
			if ( ! $this->cookiebot_valid_admin_recommendation() ) {
				return false;
			}
			$two_week_review_ignore = add_query_arg( array( 'cookiebot_admin_notice' => 'hide' ) );
			$two_week_review_temp   = add_query_arg( array( 'cookiebot_admin_notice' => 'two_week' ) );

			?>
			<div class="update-nag cookiebot-admin-notice">
				<div class="cookiebot-notice-logo"></div>
				<p class="cookiebot-notice-title"><?php echo esc_html__( 'Leave A Review?', 'cookiebot' ); ?></p>
				<p class="cookiebot-notice-body">
				<?php
				echo esc_html__(
					'We hope you enjoy using WordPress Cookiebot! Would you consider leaving us a review on WordPress.org?',
					'cookiebot'
				);
				?>
					</p>
				<ul class="cookiebot-notice-body wd-blue">
					<li>
						<span class="dashicons dashicons-external"></span>
						<a href="https://wordpress.org/support/plugin/cookiebot/reviews?filter=5&rate=5#new-post" target="_blank">
						<?php
						echo esc_html__(
							'Sure! I\'d love to!',
							'cookiebot'
						);
						?>
							</a>
					</li>
					<li>
						<span class="dashicons dashicons-smiley"></span>
						<a href="<?php echo esc_url( $two_week_review_ignore ); ?>">
											<?php
											echo esc_html__(
												'I\'ve already left a review',
												'cookiebot'
											);
											?>
							</a>
					</li>
					<li>
						<span class="dashicons dashicons-calendar-alt"></span>
						<a href="<?php echo esc_url( $two_week_review_temp ); ?>">
											<?php
											echo esc_html__(
												'Maybe Later',
												'cookiebot'
											);
											?>
							</a>
					</li>
					<li>
						<span class="dashicons dashicons-dismiss"></span>
						<a href="<?php echo esc_url( $two_week_review_ignore ); ?>">
											<?php
											echo esc_html__(
												'Never show again',
												'cookiebot'
											);
											?>
							</a>
					</li>
				</ul>
				<a href="<?php esc_url( $two_week_review_temp ); ?>" class="dashicons dashicons-dismiss"></a>
			</div>
			<?php

			wp_enqueue_style( 'cookiebot-admin-notices', plugins_url( 'css/notice.css', __FILE__ ), array(), '2.0.4' );
		}


		/**
		 * Validate if the last user action is valid for plugin recommendation
		 *
		 * @return bool
		 *
		 * @version 2.0.5
		 * @since 2.0.5
		 */
		public function cookiebot_valid_admin_recommendation() {
			//Default - the recommendation is allowed to be visible
			$return = true;

			$option = get_option( 'cookiebot_notice_recommend' );

			if ( $option != false ) {
				//Never show again is clicked
				if ( $option == 'hide' ) {
					$return = false;
				} elseif ( is_numeric( $option ) && strtotime( 'now' ) < $option ) {
					//Show me after 2 weeks is clicked and the time is not valid yet
					$return = false;
				}
			}

			return $return;
		}

		/**
		 * Save the user action on cookiebot recommendation link
		 *
		 * @version 2.0.5
		 * @since 2.0.5
		 */
		public function save_notice_link() {
			if ( isset( $_GET['cookiebot_admin_notice'] ) ) {
				if ( $_GET['cookiebot_admin_notice'] === 'hide' ) {
					update_option( 'cookiebot_notice_recommend', 'hide' );
				} else {
					update_option( 'cookiebot_notice_recommend', strtotime( '+2 weeks' ) );
				}
			}
		}


		/**
		 * Cookiebot_WP Fix plugin conflicts related to Cookiebot
		 *
		 * @version 3.2.0
		 * @since   3.3.0
		 */
		public function cookiebot_fix_plugin_conflicts() {
			//Fix for Divi Page Builder
			//Disabled - using another method now (can_current_user_edit_theme())
			//add_action( 'wp', array( $this, '_cookiebot_plugin_conflict_divi' ), 100 );

			//Fix for Elementor and WPBakery Page Builder Builder
			//Disabled - using another method now (can_current_user_edit_theme())
			//add_filter( 'script_loader_tag', array( $this, '_cookiebot_plugin_conflict_scripttags' ), 10, 2 );
		}

		/**
		 * Cookiebot_WP Fix Divi builder conflict when blocking mode is in auto.
		 *
		 * @version 3.2.0
		 * @since   3.2.0
		 */
		public function _cookiebot_plugin_conflict_divi() {
			if ( defined( 'ET_FB_ENABLED' ) ) {
				if ( ET_FB_ENABLED &&
					 $this->cookiebot_disabled_in_admin() &&
					 $this->get_cookie_blocking_mode() == 'auto' ) {

					define( 'COOKIEBOT_DISABLE_ON_PAGE', true ); //Disable Cookiebot on the current page

				}
			}
		}

		/**
		 * Cookiebot_WP Fix plugin conflicts with page builders - whitelist JS files in automode
		 *
		 * @version 3.2.0
		 * @since   3.3.0
		 */
		public function _cookiebot_plugin_conflict_scripttags( $tag, $handle ) {

			//Check if Elementor Page Builder active
			if ( defined( 'ELEMENTOR_VERSION' ) ) {
				if ( in_array(
					$handle,
					array(
						'jquery-core',
						'elementor-frontend-modules',
						'elementor-frontend',
						'wp-tinymce',
						'underscore',
						'backbone',
						'backbone-marionette',
						'backbone-radio',
						'elementor-common-modules',
						'elementor-dialog',
						'elementor-common',
					)
				) ) {
					$tag = str_replace( '<script ', '<script data-cookieconsent="ignore" ', $tag );
				}
			}

			//Check if WPBakery Page Builder active
			if ( defined( 'WPB_VC_VERSION' ) ) {
				if ( in_array(
					$handle,
					array(
						'jquery-core',
						'jquery-ui-core',
						'jquery-ui-sortable',
						'jquery-ui-mouse',
						'jquery-ui-widget',
						'vc_editors-templates-preview-js',
						'vc-frontend-editor-min-js',
						'vc_inline_iframe_js',
						'wpb_composer_front_js',
					)
				) ) {
					$tag = str_replace( '<script ', '<script data-cookieconsent="ignore" ', $tag );
				}
			}

			return $tag;
		}

	}
endif;


/**
 * Helper function to manipulate script tags
 *
 * @return  string
 * @since   1.0
 * @version 1.6
 */
function cookiebot_assist( $type = 'statistics' ) {
	//change to array
	if ( ! is_array( $type ) ) {
		$type = array( $type );
	}

	foreach ( $type as $tk => $tv ) {
		if ( ! in_array( $tv, array( 'marketing', 'statistics', 'preferences' ) ) ) {
			unset( $type[ $tk ] );
		}
	}
	if ( sizeof( $type ) > 0 ) {
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
	if ( ! empty( $cbid ) ) {
		return true;
	}

	return false;
}


if ( ! function_exists( 'cookiebot' ) ) {
	/**
	 * Returns the main instance of Cookiebot_WO to prevent the need to use globals.
	 *
	 * @return  Cookiebot_WP
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	function cookiebot() {
		return Cookiebot_WP::instance();
	}
}

cookiebot();
