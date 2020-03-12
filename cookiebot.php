<?php
/*
Plugin Name: Cookiebot | GDPR/CCPA Compliant Cookie Consent and Control
Plugin URI: https://cookiebot.com/
Description: Cookiebot is a cloud-driven solution that automatically controls cookies and trackers, enabling full GDPR/ePrivacy and CCPA compliance for websites.
Author: Cybot A/S
Version: 3.6.1
Author URI: http://cookiebot.com
Text Domain: cookiebot
Domain Path: /langs
*/

if(!defined('ABSPATH')) exit; // Exit if accessed directly

if(!class_exists('Cookiebot_WP')):

final class Cookiebot_WP {
	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = '3.6.1';

	/**
	 * @var   Cookiebot_WP The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Cookiebot_WP Instance
	 *
	 * Ensures only one instance of Cookiebot_WP is loaded or can be loaded.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @static
	 * @return  Cookiebot_WP - Main instance
	 */
	public static function instance() {
		if(is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cookiebot_WP Constructor.
	 *
	 * @version 2.1.4
	 * @since   1.0.0
	 * @access  public
	 */
	function __construct() {
		add_action('plugins_loaded', array($this, 'cookiebot_init'), 5);
		register_activation_hook( __FILE__ , array($this, 'activation'));
		register_deactivation_hook( __FILE__, 'cookiebot_addons_plugin_deactivated' );

		$this->cookiebot_fix_plugin_conflicts();

	}

	/**
	 * Cookiebot_WP Installation actions
	 *
	 * @version 2.1.4
	 * @since		2.1.4
	 * @accces	public
	 */
	function activation() {
		//Delay display of recommendation notice in 3 days if not activated ealier
		if(get_option('cookiebot_notice_recommend',false) === false) {
			//Not set yet - this must be first activation - delay in 3 days
			update_option('cookiebot_notice_recommend', strtotime('+3 days'));
		}
		if($this->get_cbid() == '') {
			if(is_multisite()) {
				update_site_option('cookiebot-cookie-blocking-mode','auto');
				update_site_option('cookiebot-nooutput-admin',true);
			}
			else {
				update_option('cookiebot-cookie-blocking-mode','auto');
				update_option('cookiebot-nooutput-admin',true);
			}
		}
	}

	/**
	 * Cookiebot_WP Init Cookiebot.
	 *
	 * @version 3.2.0
	 * @since   1.6.2
	 * @access  public
	 */
	function cookiebot_init() {
		/* Load Cookiebot Addons Framework */
		$dismissAddons = false;
		if(defined('CAF_DIR')) {
			$dismissAddons = true;
			/*add_action('admin_notices', function() {
					?>
					<div class="notice notice-warning">
						<p>
							<?php _e( 'You have Cookiebot Addons installed.', 'cookiebot' ); ?><br />
							<?php _e( 'In this and future releases of Cookiebot all available Addons are bundled directly with the Cookiebot plugin.', 'cookiebot' ); ?><br />
							<?php _e( 'To ensure up-to-date addons - please disable and remove your Cookiebot Addons plugin and configure you addons under "Prior consent" in the Cookiebot menu.', 'cookiebot' ); ?>
						</p>
					</div>
					<?php
				});*/
		}
		//elseif( $this->get_cookie_blocking_mode() !== 'auto' ) {
		else {
			if( (!defined('COOKIEBOT_ADDONS_STANDALONE') || COOKIEBOT_ADDONS_STANDALONE != true || !defined('COOKIE_ADDONS_LOADED')) && $dismissAddons !== true ) {
				//Make sure we got a PHP version that works
				if(version_compare(PHP_VERSION, '5.4.0', '>=')) {
					define('COOKIEBOT_URL', plugin_dir_url( __FILE__ ));
					include_once( dirname( __FILE__ ) . '/addons/cookiebot-addons-init.php' );
				}
				else {
					define('COOKIEBOT_ADDONS_UNSUPPORTED_PHPVERSION',true);
				}
			}
			else {
				add_action('admin_notices', function() {
					?>
					<div class="notice notice-warning">
						<p>
							<?php _e( 'You are using Cookiebot Addons Standalone.', 'cookiebot' ); ?>
						</p>
					</div>
					<?php
				});
			}
		}
		if(is_admin()) {

			//Adding menu to WP admin
			add_action('admin_menu', array($this,'add_menu'),1);
			add_action('admin_menu', array($this,'add_menu_debug'),50);


			if(is_multisite()) {
				add_action('network_admin_menu', array($this,'add_network_menu'),1);
				add_action('network_admin_edit_cookiebot_network_settings', array($this,'network_settings_save'));
			}

			//Register settings
			add_action('admin_init', array($this,'register_cookiebot_settings'));

			//Adding dashboard widgets
			add_action('wp_dashboard_setup',  array($this,'add_dashboard_widgets'));

			add_action('admin_notices', array( $this, 'cookiebot_admin_notices' ) );
			add_action('admin_init', array($this,'save_notice_link'));

			//Check if we should show cookie consent banner on admin pages
			if(!$this->cookiebot_disabled_in_admin()) {
				//adding cookie banner in admin area too
				add_action('admin_head', array($this,'add_js'),-9999);
			}
		}

		//Include integration to WP Consent Level API if available
		if($this->is_wp_consent_api_active()) {
			add_action( 'wp_enqueue_scripts', array($this, 'cookiebot_enqueue_consent_api_scripts') );
		}

		// Set up localisation
		load_plugin_textdomain('cookiebot', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );

		//add JS
		add_action('wp_head', array($this,'add_js'), -9999);
		add_shortcode('cookie_declaration', array($this,'show_declaration'));

		//Add filter if WP rocket is enabled
		if(defined('WP_ROCKET_VERSION')) {
			add_filter('rocket_minify_excluded_external_js', array($this,'wp_rocket_exclude_external_js'));
		}

		//Automatic update plugin
		if(is_admin() || (defined('DOING_CRON') && DOING_CRON)) {
			add_filter('auto_update_plugin', array($this,'automatic_updates'), 10, 2);
		}

		//Loading widgets
		include_once( dirname( __FILE__ ) . '/widgets/cookiebot-declaration-widget.php' );
		add_action( 'widgets_init', array($this,'register_widgets') );

	}

	/**
	 * Cookiebot_WP Load text domain
	 *
	 * @version 2.0.0
	 * @since		2.0.0
	 */
	function load_textdomain() {
		load_plugin_textdomain( 'cookiebot', false, basename( dirname( __FILE__ ) ) . '/langs' );
	}

	/**
	 * Cookiebot_WP Register widgets
	 *
	 * @version 2.5.0
	 * @since 	2.5.0
	 */
	function register_widgets() {
		register_widget( 'Cookiebot_Declaration_Widget' );
	}

	/**
	 * Cookiebot_WP Add dashboard widgets to admin
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */

	function add_dashboard_widgets() {
		wp_add_dashboard_widget('cookiebot_status', __('Cookiebot Status','cookiebot'), array($this,'dashboard_widget_status'));
	}

	/**
	 * Cookiebot_WP Output Dashboard Status Widget
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function dashboard_widget_status() {
		$cbid = $this->get_cbid();
		if(empty($cbid)) {
			echo '<p>'.__('You need to enter your Cookiebot ID.','cookiebot').'</p>';
			echo '<p><a href="options-general.php?page=cookiebot">';
			echo __('Update your Cookiebot ID','cookiebot');
			echo '</a></p>';
		}
		else {
			echo '<p>'._e('Your Cookiebot is working!','cookiebot').'</p>';
		}
	}

	/**
	 * Cookiebot_WP Add option menu page for Cookiebot
	 *
	 * @version 2.2.0
	 * @since   1.0.0
	 */
	function add_menu() {
		//Cookiebot Icon SVG base64 encoded
		$icon = 'data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgNzIgNTQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0iI0ZGRkZGRiIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNNDYuODcyNTkwMyA4Ljc3MzU4MzM0QzQxLjk0MzkwMzkgMy4zODI5NTAxMSAzNC44NDI0OTQ2IDAgMjYuOTQ4MjgxOSAwIDEyLjA2NTE1NjggMCAwIDEyLjAyNDQ3NzQgMCAyNi44NTc0MjE5YzAgMTQuODMyOTQ0NSAxMi4wNjUxNTY4IDI2Ljg1NzQyMTkgMjYuOTQ4MjgxOSAyNi44NTc0MjE5IDcuODk0MjEyNyAwIDE0Ljk5NTYyMi0zLjM4Mjk1MDIgMTkuOTI0MzA4NC04Ljc3MzU4MzQtMi44ODk2OTY3LTEuMzY4ODY2My01LjM5OTMxMS0zLjQwNTQzOS03LjMyODA4MzgtNS45MDk2MzU4LTMuMTIxNDMwNiAzLjIwOTQxMDQtNy40OTI5OTQ0IDUuMjA0MTI5MS0xMi4zMzIwMjU4IDUuMjA0MTI5MS05LjQ4NDM0NDQgMC0xNy4xNzI5MjQ3LTcuNjYyNjU3Mi0xNy4xNzI5MjQ3LTE3LjExNTAyMzhzNy42ODg1ODAzLTE3LjExNTAyMzcgMTcuMTcyOTI0Ny0xNy4xMTUwMjM3YzQuNzIzNDgyMiAwIDkuMDAxNTU1MiAxLjkwMDU5MzkgMTIuMTA2MjkyIDQuOTc2MzA5IDEuOTU2OTIzNy0yLjY0MTEzMSA0LjU1MDAyNjMtNC43ODU1MTgzIDcuNTUzODE3Ni02LjIwODQzMTg2eiIvPjxwYXRoIGQ9Ik01NS4zODAzMjgyIDQyLjY1MDE5OTFDNDYuMzMzNzIyNyA0Mi42NTAxOTkxIDM5IDM1LjM0MTIwMzEgMzkgMjYuMzI1MDk5NiAzOSAxNy4zMDg5OTYgNDYuMzMzNzIyNyAxMCA1NS4zODAzMjgyIDEwYzkuMDQ2NjA1NSAwIDE2LjM4MDMyODIgNy4zMDg5OTYgMTYuMzgwMzI4MiAxNi4zMjUwOTk2IDAgOS4wMTYxMDM1LTcuMzMzNzIyNyAxNi4zMjUwOTk1LTE2LjM4MDMyODIgMTYuMzI1MDk5NXptLjAyMTMwOTItNy43NTU2MzQyYzQuNzM3MDI3NiAwIDguNTc3MTQ3MS0zLjgyNzE3MiA4LjU3NzE0NzEtOC41NDgyMjc5IDAtNC43MjEwNTYtMy44NDAxMTk1LTguNTQ4MjI4LTguNTc3MTQ3MS04LjU0ODIyOC00LjczNzAyNzUgMC04LjU3NzE0NyAzLjgyNzE3Mi04LjU3NzE0NyA4LjU0ODIyOCAwIDQuNzIxMDU1OSAzLjg0MDExOTUgOC41NDgyMjc5IDguNTc3MTQ3IDguNTQ4MjI3OXoiLz48L2c+PC9zdmc+';
		add_menu_page( 'Cookiebot', __('Cookiebot','cookiebot'), 'manage_options', 'cookiebot', array($this,'settings_page'),$icon);

		add_submenu_page('cookiebot',__('Cookiebot Settings','cookiebot'),__('Settings','cookiebot'), 'manage_options', 'cookiebot',array($this,'settings_page') );
		add_submenu_page('cookiebot',__('Cookiebot Support','cookiebot'),__('Support','cookiebot'), 'manage_options', 'cookiebot_support',array($this,'support_page') );
		add_submenu_page('cookiebot',__('IAB','cookiebot'),__('IAB','cookiebot'), 'manage_options', 'cookiebot_iab',array($this,'iab_page') );
		
		if(defined('COOKIEBOT_ADDONS_UNSUPPORTED_PHPVERSION')) {
			//Load prior consent page anyway - but from Cookiebot WP Core plugin.
			add_submenu_page( 'cookiebot', __( 'Prior Consent', 'cookiebot' ), __( 'Prior Consent', 'cookiebot' ), 'manage_options', 'cookiebot-addons', array($this,'setting_page_placeholder'	) );
		}
	}
	
	/**
	 * Cookiebot_WP Add debug menu - we need to add this seperate to ensure it is placed last (after menu items from Addons).
	 *
	 * @version 3.6.0
	 * @since   3.6.0
	 */
	function add_menu_debug() {
		add_submenu_page('cookiebot',__('Debug info','cookiebot'),__('Debug info','cookiebot'), 'manage_options', 'cookiebot_debug',array($this,'debug_page') );
	}

	/**
	 * Cookiebot_WP Add menu for network sites
	 *
	 * @version	2.2.0
	 * @since		2.2.0
	 */
	function add_network_menu() {
		$icon = 'data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgNzIgNTQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0iI0ZGRkZGRiIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNNDYuODcyNTkwMyA4Ljc3MzU4MzM0QzQxLjk0MzkwMzkgMy4zODI5NTAxMSAzNC44NDI0OTQ2IDAgMjYuOTQ4MjgxOSAwIDEyLjA2NTE1NjggMCAwIDEyLjAyNDQ3NzQgMCAyNi44NTc0MjE5YzAgMTQuODMyOTQ0NSAxMi4wNjUxNTY4IDI2Ljg1NzQyMTkgMjYuOTQ4MjgxOSAyNi44NTc0MjE5IDcuODk0MjEyNyAwIDE0Ljk5NTYyMi0zLjM4Mjk1MDIgMTkuOTI0MzA4NC04Ljc3MzU4MzQtMi44ODk2OTY3LTEuMzY4ODY2My01LjM5OTMxMS0zLjQwNTQzOS03LjMyODA4MzgtNS45MDk2MzU4LTMuMTIxNDMwNiAzLjIwOTQxMDQtNy40OTI5OTQ0IDUuMjA0MTI5MS0xMi4zMzIwMjU4IDUuMjA0MTI5MS05LjQ4NDM0NDQgMC0xNy4xNzI5MjQ3LTcuNjYyNjU3Mi0xNy4xNzI5MjQ3LTE3LjExNTAyMzhzNy42ODg1ODAzLTE3LjExNTAyMzcgMTcuMTcyOTI0Ny0xNy4xMTUwMjM3YzQuNzIzNDgyMiAwIDkuMDAxNTU1MiAxLjkwMDU5MzkgMTIuMTA2MjkyIDQuOTc2MzA5IDEuOTU2OTIzNy0yLjY0MTEzMSA0LjU1MDAyNjMtNC43ODU1MTgzIDcuNTUzODE3Ni02LjIwODQzMTg2eiIvPjxwYXRoIGQ9Ik01NS4zODAzMjgyIDQyLjY1MDE5OTFDNDYuMzMzNzIyNyA0Mi42NTAxOTkxIDM5IDM1LjM0MTIwMzEgMzkgMjYuMzI1MDk5NiAzOSAxNy4zMDg5OTYgNDYuMzMzNzIyNyAxMCA1NS4zODAzMjgyIDEwYzkuMDQ2NjA1NSAwIDE2LjM4MDMyODIgNy4zMDg5OTYgMTYuMzgwMzI4MiAxNi4zMjUwOTk2IDAgOS4wMTYxMDM1LTcuMzMzNzIyNyAxNi4zMjUwOTk1LTE2LjM4MDMyODIgMTYuMzI1MDk5NXptLjAyMTMwOTItNy43NTU2MzQyYzQuNzM3MDI3NiAwIDguNTc3MTQ3MS0zLjgyNzE3MiA4LjU3NzE0NzEtOC41NDgyMjc5IDAtNC43MjEwNTYtMy44NDAxMTk1LTguNTQ4MjI4LTguNTc3MTQ3MS04LjU0ODIyOC00LjczNzAyNzUgMC04LjU3NzE0NyAzLjgyNzE3Mi04LjU3NzE0NyA4LjU0ODIyOCAwIDQuNzIxMDU1OSAzLjg0MDExOTUgOC41NDgyMjc5IDguNTc3MTQ3IDguNTQ4MjI3OXoiLz48L2c+PC9zdmc+';
		add_menu_page( 'Cookiebot', __('Cookiebot','cookiebot'), 'manage_network_options', 'cookiebot_network', array($this,'network_settings_page'),$icon);

		add_submenu_page('cookiebot_network',__('Cookiebot Settings','cookiebot'),__('Settings','cookiebot'), 'network_settings_page', 'cookiebot_network',array($this,'network_settings_page'));
		add_submenu_page('cookiebot_network',__('Cookiebot Support','cookiebot'),__('Support','cookiebot'), 'network_settings_page', 'cookiebot_support',array($this,'support_page'));

	}

	/**
	 * Cookiebot_WP Cookiebot prior consent placeholder page
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 */
	function setting_page_placeholder() {
		include __DIR__ . DIRECTORY_SEPARATOR . 'addons' . DIRECTORY_SEPARATOR . 'view/admin/settings/setting-page.php';
	}

	/**
	 * Cookiebot_WP Register Cookiebot settings
	 *
	 * @version 2.1.5
	 * @since   1.0.0
	 */
	function register_cookiebot_settings() {
		register_setting('cookiebot', 'cookiebot-cbid');
		register_setting('cookiebot', 'cookiebot-language');
		register_setting('cookiebot', 'cookiebot-nooutput');
		register_setting('cookiebot', 'cookiebot-nooutput-admin');
		register_setting('cookiebot', 'cookiebot-autoupdate');
		register_setting('cookiebot', 'cookiebot-script-tag-uc-attribute');
		register_setting('cookiebot', 'cookiebot-script-tag-cd-attribute');
		register_setting('cookiebot', 'cookiebot-cookie-blocking-mode');
		register_setting('cookiebot', 'cookiebot-consent-mapping');
		register_setting('cookiebot-iab', 'cookiebot-iab');
	}

	/**
	 * Cookiebot_WP Automatic update plugin if activated
	 *
	 * @version 2.2.0
	 * @since		1.5.0
	 */
	function automatic_updates($update, $item) {
		//Do not update from subsite on a multisite installation
		if(is_multisite() && ! is_main_site()) {
			return $update;
		}

		//Check if we have everything we need
		$item = (array)$item;
		if(!isset($item['new_version']) || !isset($item['slug'])) {
			return $update;
		}

		//It is not Cookiebot
		if($item['slug'] !== 'cookiebot') {
			return $update;
    }

    // Check if cookiebot autoupdate is disabled
		if(!get_option('cookiebot-autoupdate',false)) {
			return $update;
		}

		// Check if multisite autoupdate is disabled
		if(is_multisite() && !get_site_option('cookiebot-autoupdate',false)) {
			return $update;
		}

		return true;
	}


	/**
	 * Cookiebot_WP Get list of supported languages
	 *
	 * @version	1.4.0
	 * @since		1.4.0
	 */
	public static function get_supported_languages() {
		$supportedLanguages = array();
		$supportedLanguages['nb'] = __('Norwegian Bokmål','cookiebot');
		$supportedLanguages['tr'] = __('Turkish','cookiebot');
		$supportedLanguages['de'] = __('German','cookiebot');
		$supportedLanguages['cs'] = __('Czech','cookiebot');
		$supportedLanguages['da'] = __('Danish','cookiebot');
		$supportedLanguages['sq'] = __('Albanian','cookiebot');
		$supportedLanguages['he'] = __('Hebrew','cookiebot');
		$supportedLanguages['ko'] = __('Korean','cookiebot');
		$supportedLanguages['it'] = __('Italian','cookiebot');
		$supportedLanguages['nl'] = __('Dutch','cookiebot');
		$supportedLanguages['vi'] = __('Vietnamese','cookiebot');
		$supportedLanguages['ta'] = __('Tamil','cookiebot');
		$supportedLanguages['is'] = __('Icelandic','cookiebot');
		$supportedLanguages['ro'] = __('Romanian','cookiebot');
		$supportedLanguages['si'] = __('Sinhala','cookiebot');
		$supportedLanguages['ca'] = __('Catalan','cookiebot');
		$supportedLanguages['bg'] = __('Bulgarian','cookiebot');
		$supportedLanguages['uk'] = __('Ukrainian','cookiebot');
		$supportedLanguages['zh'] = __('Chinese','cookiebot');
		$supportedLanguages['en'] = __('English','cookiebot');
		$supportedLanguages['ar'] = __('Arabic','cookiebot');
		$supportedLanguages['hr'] = __('Croatian','cookiebot');
		$supportedLanguages['th'] = __('Thai','cookiebot');
		$supportedLanguages['el'] = __('Greek','cookiebot');
		$supportedLanguages['lt'] = __('Lithuanian','cookiebot');
		$supportedLanguages['pl'] = __('Polish','cookiebot');
		$supportedLanguages['lv'] = __('Latvian','cookiebot');
		$supportedLanguages['fr'] = __('French','cookiebot');
		$supportedLanguages['id'] = __('Indonesian','cookiebot');
		$supportedLanguages['mk'] = __('Macedonian','cookiebot');
		$supportedLanguages['et'] = __('Estonian','cookiebot');
		$supportedLanguages['pt'] = __('Portuguese','cookiebot');
		$supportedLanguages['ga'] = __('Irish','cookiebot');
		$supportedLanguages['ms'] = __('Malay','cookiebot');
		$supportedLanguages['sl'] = __('Slovenian','cookiebot');
		$supportedLanguages['ru'] = __('Russian','cookiebot');
		$supportedLanguages['ja'] = __('Japanese','cookiebot');
		$supportedLanguages['hi'] = __('Hindi','cookiebot');
		$supportedLanguages['sk'] = __('Slovak','cookiebot');
		$supportedLanguages['es'] = __('Spanish','cookiebot');
		$supportedLanguages['sv'] = __('Swedish','cookiebot');
		$supportedLanguages['sr'] = __('Serbian','cookiebot');
		$supportedLanguages['fi'] = __('Finnish','cookiebot');
		$supportedLanguages['eu'] = __('Basque','cookiebot');
		$supportedLanguages['hu'] = __('Hungarian','cookiebot');
		asort($supportedLanguages,SORT_LOCALE_STRING);
		return $supportedLanguages;
	}

	/**
	 * Cookiebot_WP Output settings page
	 *
	 * @version 2.2.0
	 * @since   1.0.0
	 */
	function settings_page() {
		wp_enqueue_style( 'cookiebot-consent-mapping-table', plugins_url( 'css/consent_mapping_table.css', __FILE__ ), array(), '3.5.0' );

		/* Check if multisite */
		if($is_ms = is_multisite()) {
			//Receive settings from multisite - this might change the way we render the form
			$network_cbid = get_site_option('cookiebot-cbid','');
			$network_scrip_tag_uc_attr = get_site_option('cookiebot-script-tag-uc-attribute','custom');
			$network_scrip_tag_cd_attr = get_site_option('cookiebot-script-tag-cd-attribute','custom');
			$network_cookie_blocking_mode = get_site_option('cookiebot-cookie-blocking-mode','manual');
		}
		?>
		<div class="wrap">
			<h1><?php _e('Cookiebot Settings','cookiebot'); ?></h1>
			<a href="https://www.cookiebot.com">
				<img src="<?php echo plugins_url( 'cookiebot-logo.png', __FILE__ ); ?>" style="float:right;margin-left:1em;">
			</a>
			<p>
				<?php _e('Cookiebot enables your website to comply with current legislation in the EU on the use of cookies for user tracking and profiling. The EU ePrivacy Directive requires prior, informed consent of your site users, while the <a href="https://www.cookiebot.com/goto/gdpr" target="_blank">General Data Protection Regulation (GDPR)</a> requires you to document each consent. At the same time you must be able to account for what user data you share with embedded third-party services on your website and where in the world the user data is sent.','cookiebot'); ?>
			</p>
			<form method="post" action="options.php">
				<?php settings_fields( 'cookiebot' ); ?>
				<?php do_settings_sections( 'cookiebot' ); ?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php _e('Cookiebot ID','cookiebot'); ?></th>
						<td>
							<input type="text" name="cookiebot-cbid" value="<?php echo esc_attr( get_option('cookiebot-cbid') ); ?>"<?php echo ($is_ms) ? ' placeholder="'.$network_cbid.'"' : ''; ?> style="width:300px" />
							<p class="description">
								<?php _e('Need an ID?','cookiebot'); ?>
								<a href="https://www.cookiebot.com/goto/signup" target="_blank"><?php _e('Sign up for free on cookiebot.com','cookiebot'); ?></a>
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e('Cookie-blocking mode','cookiebot'); ?>
						</th>
						<td>
							<?php
							$cbm = get_option('cookiebot-cookie-blocking-mode','manual');
							if($is_ms && $network_cookie_blocking_mode != 'custom') {
								$cbm = $network_cookie_blocking_mode;
							}
							?>
							<label>
								<input type="radio" name="cookiebot-cookie-blocking-mode" value="auto" <?php checked('auto', $cbm, true); ?> />
								<?php _e('Automatic','cookiebot'); ?>
							</label>
							&nbsp; &nbsp;
							<label>
								<input type="radio" name="cookiebot-cookie-blocking-mode" value="manual" <?php checked('manual',$cbm, true); ?> />
								<?php _e('Manual','cookiebot'); ?>
							</label>
							<p class="description">
								<?php _e('Automatic block cookies (except necessary) untill the user has given their consent.','cookiebot') ?>
								<a href="https://support.cookiebot.com/hc/en-us/articles/360009063100-Automatic-Cookie-Blocking-How-does-it-work-" target="_blank">
									<?php _e('Learn more','cookiebot'); ?>
								</a>
							</p>
							<script>
								jQuery(document).ready(function($) {
									var cookieBlockingMode = '<?php echo $cbm; ?>';
									$( 'input[type=radio][name=cookiebot-cookie-blocking-mode]' ).on( 'change', function() {
										if(this.value == 'auto' && cookieBlockingMode != this.value ) {
											$( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 0.4 );
											$( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', true );
										}
										if( this.value == 'manual' && cookieBlockingMode != this.value ) {
											$( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 1 );
											$( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', false );
										}
										cookieBlockingMode = this.value;
									});
									if( cookieBlockingMode == 'auto' ) {
										$( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 0.4 );
										$( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', true );
									}
								});
							</script>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Cookiebot Language','cookiebot'); ?></th>
						<td>
							<div>
								<select name="cookiebot-language" id="cookiebot-language">
									<?php
									$currentLang = $this->get_language(true);
									?>
									<option value=""><?php _e('Default (Autodetect)','cookiebot'); ?></option>
									<option value="_wp"<?php echo ($currentLang == '_wp') ? ' selected' : ''; ?>><?php _e('Use Wordpress Language','cookiebot'); ?></option>
									<?php
									$supportedLanguages = $this->get_supported_languages();
									foreach($supportedLanguages as $langCode=>$langName) {
										echo '<option value="'.$langCode.'"'.(($currentLang==$langCode) ? ' selected' : '').'>'.$langName.'</option>';
									}
									?>
								</select>
							</div>
							<div class="notice inline notice-warning notice-alt cookiebot-notice" style="padding:12px;font-size:13px;display:inline-block;">
								<div style="<?php echo ($currentLang=='') ? 'display:none;' : '' ?>" id="info_lang_specified">
									<?php _e('You need to add the language in the Cookiebot administration tool.'); ?>
								</div>
								<div style="<?php echo ($currentLang=='') ? '' : 'display:none;' ?>" id="info_lang_autodetect">
									<?php _e('You need to add all languages that you want auto-detected in the Cookiebot administration tool.'); ?> <br/>
									<?php _e('The auto-detect checkbox needs to be enabled in the Cookiebot administration tool.'); ?><br/>
									<?php _e('If the auto-detected language is not supported, Cookiebot will use the default language.'); ?>
								</div>
								<br />

								<a href="#" id="show_add_language_guide"><?php _e('Show guide to add languages'); ?></a>
								&nbsp;
								<a href="https://support.cookiebot.com/hc/en-us/articles/360003793394-How-do-I-set-the-language-of-the-consent-banner-dialog-" target="_blank">
									<?php _e('Read more here'); ?>
								</a>

								<div id="add_language_guide" style="display:none;">
									<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/assets/guide_add_language.gif" alt="Add language in Cookiebot administration tool" />
									<br />
									<a href="#" id="hide_add_language_guide"><?php _e('Hide guide'); ?></a>
								</div>
							</div>
							<script>
								jQuery(document).ready(function($) {
									$('#show_add_language_guide').on('click',function(e) {
										e.preventDefault();
										$('#add_language_guide').slideDown();
										$(this).hide();
									});
									$('#hide_add_language_guide').on('click',function(e) {
										e.preventDefault();
										$('#add_language_guide').slideUp();
										$('#show_add_language_guide').show();
									});

									$('#cookiebot-language').on('change', function() {
										if(this.value == '') {
											$('#info_lang_autodetect').show();
											$('#info_lang_specified').hide();
										}
										else {
											$('#info_lang_autodetect').hide();
											$('#info_lang_specified').show();
										}
									});
								});
							</script>

						</td>
					</tr>
				</table>
				<script>
					jQuery(document).ready(function($) {
						$('.cookiebot_fieldset_header').on('click',function(e) {
							e.preventDefault();
							$(this).next().slideToggle();
							$(this).toggleClass('active');
						});
					});
				</script>
				<style type="text/css">
					.cookiebot_fieldset_header {
						cursor:pointer;
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
				<h3 id="advanced_settings_link" class="cookiebot_fieldset_header"><?php _e('Advanced settings', 'cookiebot'); ?></h3>
				<div  id="advanced_settings" style="display:none;">
					<table class="form-table">
						<tr valign="top" id="cookiebot-setting-async">
							<th scope="row">
								<?php _e('Add async or defer attribute','cookiebot'); ?>
								<br /><?php _e('Consent banner script tag'); ?>
							</th>
							<td>
								<?php
								$cv = get_option('cookiebot-script-tag-uc-attribute','async');
								$disabled = false;
								if($is_ms && $network_scrip_tag_uc_attr != 'custom') {
									$disabled = true;
									$cv = $network_scrip_tag_uc_attr;
								}
								?>
								<label>
									<input type="radio" name="cookiebot-script-tag-uc-attribute"<?php echo ($disabled) ? ' disabled' : ''; ?> value="" <?php checked('',		 $cv, true); ?> />
									<i>None</i>
								</label>
								&nbsp; &nbsp;
								<label>
									<input type="radio" name="cookiebot-script-tag-uc-attribute"<?php echo ($disabled) ? ' disabled' : ''; ?> value="async" <?php checked('async',$cv, true); ?> />
									async
								</label>
								&nbsp; &nbsp;
								<label>
									<input type="radio" name="cookiebot-script-tag-uc-attribute"<?php echo ($disabled) ? ' disabled' : ''; ?> value="defer" <?php checked('defer',$cv, true); ?> />
									defer
								</label>
								<p class="description">
									<?php if($disabled) { echo '<b>'._('Network setting applied. Please contact website administrator to change this setting.').'</b><br />';  } ?>
									<?php _e('Add async or defer attribute to Cookiebot script tag. Default: async','cookiebot') ?>
								</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<?php _e('Add async or defer attribute','cookiebot'); ?>
								<br /><?php _e('Cookie declaration script tag'); ?>
							</th>
							<td>
								<?php
								$cv = get_option('cookiebot-script-tag-cd-attribute','async');
								$disabled = false;
								if($is_ms && $network_scrip_tag_cd_attr != 'custom') {
									$disabled = true;
									$cv = $network_scrip_tag_cd_attr;
								}
								?>
								<label>
									<input type="radio" name="cookiebot-script-tag-cd-attribute"<?php echo ($disabled) ? ' disabled' : ''; ?> value="" 		 <?php checked('',		 $cv, true); ?> />
									<i>None</i>
								</label>
								&nbsp; &nbsp;
								<label>
									<input type="radio" name="cookiebot-script-tag-cd-attribute"<?php echo ($disabled) ? ' disabled' : ''; ?> value="async" <?php checked('async',$cv, true); ?> />
									async
								</label>
								&nbsp; &nbsp;
								<label>
									<input type="radio" name="cookiebot-script-tag-cd-attribute"<?php echo ($disabled) ? ' disabled' : ''; ?> value="defer" <?php checked('defer',$cv, true); ?> />
									defer
								</label>
								<p class="description">
									<?php if($disabled) { echo '<b>'._('Network setting applied. Please contact website administrator to change this setting.').'</b><br />';  } ?>
									<?php _e('Add async or defer attribute to Cookiebot script tag. Default: async','cookiebot') ?>
								</p>
							</td>
						</tr>
						<?php
						if(!is_multisite()) {
							?>
							<tr valign="top">
								<th scope="row"><?php _e('Auto-update Cookiebot','cookiebot'); ?></th>
								<td>
									<input type="checkbox" name="cookiebot-autoupdate" value="1" <?php checked(1,get_option('cookiebot-autoupdate',false), true); ?> />
									<p class="description">
										<?php _e('Automatic update your Cookiebot plugin when new releases becomes available.','cookiebot') ?>
									</p>
								</td>
							</tr>
							<?php
						}
						?>
						<tr valign="top" id="cookiebot-setting-hide-popup">
							<th scope="row"><?php _e('Hide Cookie Popup','cookiebot'); ?></th>
							<td>
								<?php
								$disabled = false;
								if($is_ms && get_site_option('cookiebot-nooutput',false)) {
									$disabled = true;
									echo '<input type="checkbox" checked disabled />';
								}
								else {
									?>
									<input type="checkbox" name="cookiebot-nooutput" value="1" <?php checked(1,get_option('cookiebot-nooutput',false), true); ?> />
									<?php
								}
								?>
								<p class="description">
									<?php if($disabled) { echo '<b>'._('Network setting applied. Please contact website administrator to change this setting.').'</b><br />';  } ?>
									<b><?php _e('This checkbox will remove the cookie consent banner from your website. The <i>[cookie_declaration]</i> shortcode will still be available.','cookiebot') ?></b><br />
									<?php _e('If you are using Google Tag Manager (or equal), you need to add the Cookiebot script in your Tag Manager.','cookiebot') ?><br />
									<?php _e('<a href="https://support.cookiebot.com/hc/en-us/articles/360003793854-Google-Tag-Manager-deployment" target="_blank">See a detailed guide here</a>','cookiebot') ?>
								</p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Disable Cookiebot in WP Admin','cookiebot'); ?></th>
							<td>
								<?php
								$disabled = false;
								if($is_ms && get_site_option('cookiebot-nooutput-admin',false)) {
									echo '<input type="checkbox" checked disabled />';
									$disabled = true;
								}
								else {
									?>
									<input type="checkbox" name="cookiebot-nooutput-admin" value="1" <?php checked(1,get_option('cookiebot-nooutput-admin',false), true); ?> />
									<?php
								}
								?>
								<p class="description">
									<?php if($disabled) { echo '<b>'._('Network setting applied. Please contact website administrator to change this setting.').'</b><br />';  } ?>
									<b><?php _e('This checkbox will disable Cookiebot in the Wordpress Admin area.','cookiebot') ?></b>
								</p>
							</td>
						</tr>
					</table>
				</div>
				<?php if($this->is_wp_consent_api_active()) { ?>
					<h3 id="consent_level_api_settings" class="cookiebot_fieldset_header"><?php _e('Consent Level API Settings', 'cookiebot'); ?></h3>
					<div  id="consent_level_api_settings" style="display:none;">
						<p><?php _e('WP Consent Level API and Cookiebot categorise cookies a bit different. The default settings should fit mosts needs - but if you need to change the mapping you are able to do it below.','cookiebot'); ?></p>

						<?php
						$mDefault = $this->get_default_wp_consent_api_mapping();

						$m = $this->get_wp_consent_api_mapping();

						$consentTypes = ['preferences', 'statistics', 'marketing'];
						$states = array_reduce($consentTypes, function ($t, $v) {
								$newt = [];
								if (empty($t)) {
										$newt = [
												[$v => true],
												[$v => false],
										];
								} else {
										foreach ($t as $item) {
												$newt[] = array_merge($item, [$v => true]);
												$newt[] = array_merge($item, [$v => false]);
										}
								}

								return $newt;
						}, []);

						?>


						<table class="widefat striped consent_mapping_table">
							<thead>
								<tr>
									<th><?php _e('Cookiebot categories','cookiebot'); ?></th>
									<th class="consent_mapping"><?php _e('WP Consent Level categories','cookiebot'); ?></th>
								</tr>
							</thead>
							<?php
							foreach($states as $state) {

								$key = [];
								$key[] = 'n=1';
								$key[] = 'p='.($state['preferences'] ? '1' : '0');
								$key[] = 's='.($state['statistics'] ? '1' : '0');
								$key[] = 'm='.($state['marketing'] ? '1' : '0');
								$key = implode(';',$key);
								?>
								<tr valign="top">
									<td>
										<div class="cb_consent">
											<span class="forceconsent">Necessary</span>
											<span class="<?php echo ($state['preferences'] ? 'consent' : 'noconsent'); ?>"><?php _e('Preferences','cookiebot'); ?></span>
											<span class="<?php echo ($state['statistics'] ? 'consent' : 'noconsent'); ?>"><?php _e('Statistics','cookiebot'); ?></span>
											<span class="<?php echo ($state['marketing'] ? 'consent' : 'noconsent'); ?>"><?php _e('Marketing','cookiebot'); ?></span>
										</div>
									</td>
									<td>
										<div class="consent_mapping">
											<label><input type="checkbox" name="cookiebot-consent-mapping[<?php echo $key; ?>][functional]" 	data-default-value="1" value="1" checked disabled> Functional </label>
											<label><input type="checkbox" name="cookiebot-consent-mapping[<?php echo $key; ?>][preferences]" data-default-value="<?php echo $mDefault[$key]['preferences']; ?>" value="1" <?php if($m[$key]['preferences']) { echo 'checked'; } ?>> <?php _e('Preferences','cookiebot'); ?> </label>
											<label><input type="checkbox" name="cookiebot-consent-mapping[<?php echo $key; ?>][statistics]" data-default-value="<?php echo $mDefault[$key]['statistics']; ?>"  value="1" <?php if($m[$key]['statistics']) { echo 'checked'; } ?>> <?php _e('Statistics','cookiebot'); ?> </label>
											<label><input type="checkbox" name="cookiebot-consent-mapping[<?php echo $key; ?>][statistics-anonymous]" data-default-value="<?php echo $mDefault[$key]['statistics-anonymous']; ?>"  value="1"  <?php if($m[$key]['statistics-anonymous']) { echo 'checked'; } ?>> <?php _e('Statistics Anonymous','cookiebot'); ?></label>
											<label><input type="checkbox" name="cookiebot-consent-mapping[<?php echo $key; ?>][marketing]" data-default-value="<?php echo $mDefault[$key]['marketing']; ?>"  value="1" <?php if($m[$key]['marketing']) { echo 'checked'; } ?>> <?php _e('Marketing','cookiebot'); ?></label>
										</div>
									</td>
								</tr>
								<?php
							}
							?>
							<tfoot>
								<tr>
									<td colspan="2" style="text-align:right;"><button class="button" onclick="return resetConsentMapping();"><?php _e('Reset to default mapping','cookiebot'); ?></button></td>
								</tr>
							</tfoot>
						</table>
						<script>
							function resetConsentMapping() {
								if(confirm('Are you sure you want to reset to default consent mapping?')) {
									jQuery('.consent_mapping_table input[type=checkbox]').each(function () {
										if(!this.disabled) {
											this.checked = (jQuery(this).data('default-value') == '1') ? true : false;
										}
									});
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
	function network_settings_page() {
		?>
		<div class="wrap">
			<h1><?php _e('Cookiebot Network Settings','cookiebot'); ?></h1>
			<a href="https://www.cookiebot.com">
				<img src="<?php echo plugins_url( 'cookiebot-logo.png', __FILE__ ); ?>" style="float:right;margin-left:1em;">
			</a>
			<p>
				<?php _e('Cookiebot enables your website to comply with current legislation in the EU on the use of cookies for user tracking and profiling. The EU ePrivacy Directive requires prior, informed consent of your site users, while the <a href="https://www.cookiebot.com/goto/gdpr" target="_blank">General Data Protection Regulation (GDPR)</a> requires you to document each consent. At the same time you must be able to account for what user data you share with embedded third-party services on your website and where in the world the user data is sent.','cookiebot'); ?>
			</p>
			<p>
				<b><big style="color:red;"><?php _e('The settings below is network wide settings. See notes below each field.','cookiebot'); ?></big></b>
			</p>
			<form method="post" action="edit.php?action=cookiebot_network_settings">
				<?php wp_nonce_field( 'cookiebot-network-settings' ); ?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php _e('Network Cookiebot ID','cookiebot'); ?></th>
						<td>
							<input type="text" name="cookiebot-cbid" value="<?php echo esc_attr( get_site_option('cookiebot-cbid','') ); ?>" style="width:300px" />
							<p class="description">
								<b><?php _e('If added this will be the default Cookiebot ID for all subsites. Subsites are able to override the Cookiebot ID.','cookiebot'); ?></b>
								<br />
								<?php _e('Need an ID?','cookiebot'); ?>
								<a href="https://www.cookiebot.com/goto/signup" target="_blank"><?php _e('Sign up for free on cookiebot.com','cookiebot'); ?></a>
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e('Cookie-blocking mode','cookiebot'); ?>
						</th>
						<td>
							<?php
							$cbm = get_site_option('cookiebot-cookie-blocking-mode','manual');
							?>
							<label>
								<input type="radio" name="cookiebot-cookie-blocking-mode" value="auto" <?php checked('auto', $cbm, true); ?> />
								<?php _e('Automatic','cookiebot'); ?>
							</label>
							&nbsp; &nbsp;
							<label>
								<input type="radio" name="cookiebot-cookie-blocking-mode" value="manual" <?php checked('manual',$cbm, true); ?> />
								<?php _e('Manual','cookiebot'); ?>
							</label>
							<p class="description">
								<?php _e('Should Cookiebot automatic block cookies by tagging known tags.','cookiebot') ?>
							</p>
						</td>
					</tr>
					<script>
						jQuery(document).ready(function($) {
							var cookieBlockingMode = '<?php echo $cbm; ?>';
							$( 'input[type=radio][name=cookiebot-cookie-blocking-mode]' ).on( 'change', function() {
								if(this.value == 'auto' && cookieBlockingMode != this.value ) {
									$( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 0.4 );
									$( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', true );
								}
								if( this.value == 'manual' && cookieBlockingMode != this.value ) {
									$( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 1 );
									$( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', false );
								}
								cookieBlockingMode = this.value;
							});
							if( cookieBlockingMode == 'auto' ) {
								$( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 0.4 );
								$( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', true );
							}
						});
					</script>
					<tr valign="top" id="cookiebot-setting-async">
						<th scope="row">
							<?php _e('Add async or defer attribute','cookiebot'); ?>
							<br /><?php _e('Consent banner script tag'); ?>
						</th>
						<td>
							<?php
							$cv = get_site_option('cookiebot-script-tag-uc-attribute','custom');
							?>
							<label>
								<input type="radio" name="cookiebot-script-tag-uc-attribute" value="" 		 <?php checked('',		 $cv, true); ?> />
								<i><?php _e('None','cookiebot'); ?></i>
							</label>
							&nbsp; &nbsp;
							<label>
								<input type="radio" name="cookiebot-script-tag-uc-attribute" value="async" <?php checked('async',$cv, true); ?> />
								async
							</label>
							&nbsp; &nbsp;
							<label>
								<input type="radio" name="cookiebot-script-tag-uc-attribute" value="defer" <?php checked('defer',$cv, true); ?> />
								defer
							</label>
							&nbsp; &nbsp;
							<label>
								<input type="radio" name="cookiebot-script-tag-uc-attribute" value="custom" <?php checked('custom',$cv, true); ?> />
								<i><?php _e('Choose per subsite','cookiebot'); ?></i>
							</label>
							<p class="description">
								<b><?php _e('Setting will apply for all subsites. Subsites will not be able to override.','cookiebot'); ?></b><br />
								<?php _e('Add async or defer attribute to Cookiebot script tag. Default: Choose per subsite','cookiebot') ?>
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e('Add async or defer attribute','cookiebot'); ?>
							<br /><?php _e('Cookie declaration script tag','cookiebot'); ?>
						</th>
						<td>
							<?php
							$cv = get_site_option('cookiebot-script-tag-cd-attribute','custom');
							?>
							<label>
								<input type="radio" name="cookiebot-script-tag-cd-attribute" value="" 		 <?php checked('',		 $cv, true); ?> />
								<i><?php _e('None','cookiebot'); ?></i>
							</label>
							&nbsp; &nbsp;
							<label>
								<input type="radio" name="cookiebot-script-tag-cd-attribute" value="async" <?php checked('async',$cv, true); ?> />
								async
							</label>
							&nbsp; &nbsp;
							<label>
								<input type="radio" name="cookiebot-script-tag-cd-attribute" value="defer" <?php checked('defer',$cv, true); ?> />
								defer
							</label>
							&nbsp; &nbsp;
							<label>
								<input type="radio" name="cookiebot-script-tag-cd-attribute" value="custom" <?php checked('custom',$cv, true); ?> />
								<i><?php _e('Choose per subsite','cookiebot'); ?></i>
							</label>
							<p class="description">
								<b><?php _e('Setting will apply for all subsites. Subsites will not be able to override.','cookiebot'); ?></b><br />
								<?php _e('Add async or defer attribute to Cookiebot script tag. Default: Choose per subsite','cookiebot') ?>
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Auto-update Cookiebot','cookiebot'); ?></th>
						<td>
							<input type="checkbox" name="cookiebot-autoupdate" value="1" <?php checked(1,get_site_option('cookiebot-autoupdate',false), true); ?> />
							<p class="description">
								<?php _e('Automatic update your Cookiebot plugin when new releases becomes available.','cookiebot') ?>
							</p>
						</td>
					</tr>
					<tr valign="top" id="cookiebot-setting-hide-popup">
						<th scope="row"><?php _e('Hide Cookie Popup','cookiebot'); ?></th>
						<td>
							<input type="checkbox" name="cookiebot-nooutput" value="1" <?php checked(1,get_site_option('cookiebot-nooutput',false), true); ?> />
							<p class="description">
								<b><?php _e('Remove the cookie consent banner from all subsites. This cannot be changed by subsites. The <i>[cookie_declaration]</i> shortcode will still be available.','cookiebot') ?></b><br />
								<?php _e('If you are using Google Tag Manager (or equal), you need to add the Cookiebot script in your Tag Manager.','cookiebot') ?><br />
								<?php _e('<a href="https://support.cookiebot.com/hc/en-us/articles/360003793854-Google-Tag-Manager-deployment" target="_blank">See a detailed guide here</a>','cookiebot') ?>
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Hide Cookie Popup in WP Admin','cookiebot'); ?></th>
						<td>
							<input type="checkbox" name="cookiebot-nooutput-admin" value="1" <?php checked(1,get_site_option('cookiebot-nooutput-admin',false), true); ?> />
							<p class="description">
								<b><?php _e('Remove the cookie consent banner the Wordpress Admin area for all subsites. This cannot be changed by subsites.','cookiebot') ?></b>
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
	function network_settings_save() {
		check_admin_referer( 'cookiebot-network-settings' );

		update_site_option('cookiebot-cbid', 											$_POST['cookiebot-cbid'] );
		update_site_option('cookiebot-script-tag-uc-attribute', 	$_POST['cookiebot-script-tag-uc-attribute'] );
		update_site_option('cookiebot-script-tag-cd-attribute', 	$_POST['cookiebot-script-tag-cd-attribute'] );
		update_site_option('cookiebot-autoupdate', 								$_POST['cookiebot-autoupdate'] );
		update_site_option('cookiebot-nooutput', 									$_POST['cookiebot-nooutput'] );
		update_site_option('cookiebot-nooutput-admin', 						$_POST['cookiebot-nooutput-admin'] );
		update_site_option('cookiebot-cookie-blocking-mode', 			$_POST['cookiebot-cookie-blocking-mode'] );


		wp_redirect( add_query_arg( array(
			'page' => 'cookiebot_network',
			'updated' => true ), network_admin_url('admin.php')
		));
		exit;
	}

	/**
	 * Cookiebot_WP Cookiebot support page
	 *
	 * @version 2.2.0
	 * @since   2.0.0
	 */
	function support_page() {
		?>
		<div class="wrap">
			<h1><?php _e('Support','cookiebot'); ?></h1>
			<h2><?php _e('How to find my Cookiebot ID','cookiebot'); ?></h2>
			<p>
				<ol>
					<li><?php _e('Log in to your <a href="https://www.cookiebot.com/goto/account" target="_blank">Cookiebot account</a>.','cookiebot'); ?></li>
					<li><?php _e('Go to <b>Manage</b> > <b>Settings</b> and add setup your Cookiebot','cookiebot'); ?></li>
					<li><?php _e('Go to the <b>"Your scripts"</b> tab','cookiebot'); ?></li>
					<li><?php _e('Copy the value inside the data-cid parameter - eg.: abcdef12-3456-7890-abcd-ef1234567890','cookiebot'); ?></li>
					<li><?php _e('Add <b>[cookie_declaration]</b> shortcode to a page to show the declation','cookiebot'); ?></li>
					<li><?php _e('Remember to change your scripts as descripted below','cookiebot'); ?></li>
				</ol>
			</p>
			<h2><?php _e('Add the Cookie Declaration to your website'); ?></h2>
			<p>
				<?php _e('Use the shortcode <b>[cookie_declaration]</b> to add the cookie declaration a page or post. The cookie declaration will always show the latest version from Cookiebot.','cookiebot'); ?>
				<br />
				<?php _e('If you need to force language of the cookie declaration, you can add the <i>lang</i> attribute. Eg. <b>[cookie_declaration lang="de"]</b>.','cookiebot'); ?>
			</p>
			<p>
				<a href="https://www.youtube.com/watch?v=OCXz2bt4H_w" target="_blank" class="button"><?php _e('Watch video demonstration','cookiebot'); ?></a>
			</p>
			<h2><?php _e('Update your script tags','cookiebot'); ?></h2>
			<p>
				<?php _e('To enable prior consent, apply the attribute "data-cookieconsent" to cookie-setting script tags on your website. Set the comma-separated value to one or more of the cookie categories "preferences", "statistics" and "marketing" in accordance with the types of cookies being set by each script. Finally change the attribute "type" from "text/javascript" to "text/plain". Example on modifying an existing Google Analytics Universal script tag.','cookiebot'); ?>
			</p>
			<code>
				<?php
				echo htmlentities("<script type=\"text/plain\" data-cookieconsent=\"statistics\">").'<br />';
				echo htmlentities("(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');").'<br />';
				echo htmlentities("ga('create', 'UA-00000000-0', 'auto');").'<br />';
				echo htmlentities("ga('send', 'pageview');").'<br />';
				echo htmlentities("</script>").'<br />';
				?>
			</code>
			<p>
				<a href="https://www.youtube.com/watch?v=MeHycvV2QCQ" target="_blank" class="button"><?php _e('Watch video demonstration','cookiebot'); ?></a>
			</p>

			<h2><?php _e('Helper function to update your scripts','cookiebot'); ?></h2>
			<p>
				<?php _e('You are able to update your scripts yourself. However, Cookiebot also offers a small helper function that makes the work easier.','cookiebot'); ?>
				<br />
				<?php _e('Update your script tags this way:','cookiebot'); ?>
			</p>
			<?php
			printf(
				__('%s to %s'),
				'<code>'.htmlentities('<script type="text/javascript">').'</code>',
				'<code>'.htmlentities('<script<?php echo cookiebot_assist(\'marketing\') ?>>').'</code>'
			);
			?>
		</div>
		<?php
	}

	/**
	 * Cookiebot_WP Cookiebot IAB page
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function iab_page() {
		?>
        <div class="wrap">
            <h1><?php _e('IAB','cookiebot'); ?></h1>

            <p>For more details about Cookiebot's IAB integration, see <a href="https://support.cookiebot.com/hc/en-us/articles/360007652694-Cookiebot-and-the-IAB-Consent-Framework" target="_blank">article about cookiebot and the IAB consent framework</a></p>

            <form method="post" action="options.php">
	            <?php settings_fields( 'cookiebot-iab' ); ?>
	            <?php do_settings_sections( 'cookiebot-iab' ); ?>

                <label>Enable IAB integration</label>
                <input type="checkbox" name="cookiebot-iab" value="1" <?php checked(1,get_option('cookiebot-iab'), true); ?>>

	            <?php submit_button(); ?>
            </form>
        </div>
		<?php
    }
    
  /**
   * Cookiebot_WP Debug Page
   * 
   * @version	3.6.0
   * @since		3.6.0
   */
   
  function debug_page() {
		global $wpdb;
		
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$plugins = get_plugins();
		$active_plugins = get_option( 'active_plugins' );
		
		
		//$foo = new cookiebot_addons\lib\Settings_Service;
		//$addons = $foo->get_active_addons();
		
		$debugStr = "";
		$debugStr.= "##### Debug Information for ".get_site_url()." generated at ".date("c")." #####\n\n";
		$debugStr.= "Wordpress Version: ".get_bloginfo('version')."\n";
		$debugStr.= "Wordpress Language: ".get_bloginfo('language')."\n";
		$debugStr.= "PHP Version: ".phpversion()."\n";
		$debugStr.= "MySQL Version: ".$wpdb->db_version()."\n";
		$debugStr.= "\n--- Cookiebot Information ---\n";
		$debugStr.= "Plugin Version: ".$this->version."\n";
		$debugStr.= "Cookiebot ID: ".$this->get_cbid()."\n";
		$debugStr.= "Blocking mode: ".get_option('cookiebot-cookie-blocking-mode')."\n";
		$debugStr.= "Language: ".get_option('cookiebot-language')."\n";
		$debugStr.= "IAB: ".(get_option('cookiebot-iab') == '1' ? 'Enabled' : 'Not enabled')."\n";
		$debugStr.= "Add async/defer to banner tag: ".(get_option('cookiebot-script-tag-uc-attribute') != '' ? get_option('cookiebot-script-tag-uc-attribute') : 'None')."\n";
		$debugStr.= "Add async/defer to declaration tag: ".(get_option('cookiebot-script-tag-cd-attribute') != '' ? get_option('cookiebot-script-tag-cd-attribute') : 'None')."\n";
		$debugStr.= "Auto update: ".(get_option('cookiebot-autoupdate') == '1' ? 'Enabled' : 'Not enabled')."\n";
		$debugStr.= "Hide Cookie Popup: ".(get_option('cookiebot-nooutput') == '1' ? 'Yes' : 'No')."\n";
		$debugStr.= "Disable Cookiebot in WP Admin: ".(get_option('cookiebot-nooutput-admin') == '1' ? 'Yes' : 'No')."\n";
		$debugStr.= "Banner tag: ".$this->add_js(false)."\n";
		$debugStr.= "Declaration tag: ".$this->show_declaration()."\n";
		
		if($this->is_wp_consent_api_active()) {
			$debugStr.= "\n--- WP Consent Level API Mapping ---\n";
			$debugStr .= 'F = Functional, N = Necessary, P = Preferences, M = Marketing, S = Statistics, SA = Statistics Anonymous'."\n";
			$m = $this->get_wp_consent_api_mapping();
			foreach($m as $k=>$v) {
				$cb = array();
				
				$debugStr .= strtoupper( str_replace(';', ', ', $k ) ) . '   =>   ';
				
				$debugStr .= 'F=1, ';
				$debugStr .= 'P=' . $v['preferences'] . ', ';
				$debugStr .= 'M=' . $v['marketing'] . ', ';
				$debugStr .= 'S=' . $v['statistics'] . ', ';
				$debugStr .= 'SA=' . $v['statistics-anonymous'] . "\n";
				
			}
			
		} 
		
		if(class_exists('cookiebot_addons\Cookiebot_Addons')) {
			$ca = new cookiebot_addons\Cookiebot_Addons();
			$settingservice = $ca->container->get( 'Settings_Service_Interface' );
			$addons = $settingservice->get_active_addons();
			$debugStr.= "\n--- Activated Cookiebot Addons ---\n";
			foreach($addons as $addon) {
				$debugStr.= $addon->get_addon_name()." (".implode( ", ", $addon->get_cookie_types() ).")\n";
			}
		}
		
		$debugStr.= "\n--- Activated Plugins ---\n";
		foreach($active_plugins as $p) {
			if($p != 'cookiebot/cookiebot.php') {
				$debugStr.= $plugins[$p]['Name'] . " (Version: ".$plugins[$p]['Version'].")\n"; 
			}
		}
				
		$debugStr.= "\n##### Debug Information END #####";
		
		?>
		<div class="wrap">
			<h1><?php _e('Debug information','cookiebot'); ?></h1>
			<p><?php _e('The information below is for debugging purpose. If you have any issues with your Cookiebot integration, the information below is usefull for a supporter to help you the best way.'); ?></p>
			<p><button class="button button-primary" onclick="copyDebugInfo();"><?php _e('Copy debug information to clipboard'); ?></button></p>
			<textarea cols="100" rows="40" style="width:800px;max-width:100%;" id="cookiebot-debug-info" readonly><?php echo $debugStr; ?></textarea>
			<script>
				function copyDebugInfo() {
					var t = document.getElementById("cookiebot-debug-info");
					t.select();
					t.setSelectionRange(0, 99999);
					document.execCommand("copy");
				}
			</script>
		</div>
		<?php
	}

	/**
	 * Cookiebot_WP Add Cookiebot JS to <head>
	 *
	 * @version 3.6.0
	 * @since   1.0.0
	 */
	function add_js($printTag=true) {
		$cbid = $this->get_cbid();
		if(!empty($cbid) && !defined('COOKIEBOT_DISABLE_ON_PAGE')) {
			if(is_multisite() && get_site_option('cookiebot-nooutput',false)) {
				return; //Is multisite - and disabled output is checked as network setting
			}
			if(get_option('cookiebot-nooutput',false)) {
				return; //Do not show JS - output disabled
			}

			if($this->get_cookie_blocking_mode() == 'auto' && $this->can_current_user_edit_theme() && $printTag !== false ) {
				return;
			}

			$lang = $this->get_language();
			if(!empty($lang)) {
				$lang = ' data-culture="'.strtoupper($lang).'"'; //Use data-culture to define language
			}

			if(!is_multisite() || get_site_option('cookiebot-script-tag-uc-attribute','custom') == 'custom') {
				$tagAttr = get_option('cookiebot-script-tag-uc-attribute','async');
			}
			else {
				$tagAttr = get_site_option('cookiebot-script-tag-uc-attribute');
			}

			if($this->get_cookie_blocking_mode() == 'auto') {
				$tagAttr = 'data-blockingmode="auto"';
			}

			$iab = ( get_option('cookiebot-iab') != false ) ? 'data-framework="IAB"' : '';
			
			$tag = '<script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" '.$iab.' data-cbid="'.$cbid.'"'.$lang.' type="text/javascript" '.$tagAttr.'></script>';
			if($printTag===false) {
				return $tag;
			}
			echo $tag;		
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
	function can_current_user_edit_theme() {
	    if( is_user_logged_in() ) {
	        if( current_user_can('edit_themes') ) {
	            return true;
            }

	        if( current_user_can( 'edit_pages' ) ) {
	            return true;
            }

		    if( current_user_can( 'edit_posts' ) ) {
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
	function show_declaration($atts=array()) {
		$cbid = $this->get_cbid();
		$lang = '';
		if(!empty($cbid)) {

			$atts = shortcode_atts(array(
					'lang' => $this->get_language(),
				), $atts, 'cookie_declaration'
			);

			if(!empty($atts['lang'])) {
				$lang = ' data-culture="'.strtoupper($atts['lang']).'"'; //Use data-culture to define language
			}

			if(!is_multisite() || get_site_option('cookiebot-script-tag-cd-attribute','custom') == 'custom') {
				$tagAttr = get_option('cookiebot-script-tag-cd-attribute','async');
			}
			else {
				$tagAttr = get_site_option('cookiebot-script-tag-cd-attribute');
			}

			return '<script id="CookieDeclaration" src="https://consent.cookiebot.com/'.$cbid.'/cd.js"'.$lang.' type="text/javascript" '.$tagAttr.'></script>';
		}
		else {
			return __('Please add your Cookiebot ID to show Cookie Declarations','cookiebot');
		}
	}

	/**
	 * Cookiebot_WP Get cookiebot cbid
	 *
	 * @version	2.2.0
	 * @since		1.0.0
	 */
	public static function get_cbid() {
		$cbid = get_option('cookiebot-cbid');
		if(is_multisite() && ($network_cbid = get_site_option('cookiebot-cbid'))) {
			if(empty($cbid)) {
				return $network_cbid;
			}
		}
		return $cbid;
	}

	/**
	 * Cookiebot_WP Get cookie blocking mode (auto | manual)
	 *
	 * @version	2.2.0
	 * @since		1.0.0
	 */
	public static function get_cookie_blocking_mode() {
		$cbm = get_option('cookiebot-cookie-blocking-mode');
		if(is_multisite() && ($network_cbm = get_site_option('cookiebot-cookie-blocking-mode'))) {
			if(empty($cbm)) {
				return $network_cbm;
			}
		}
		if(empty($cbm)) { $cbm = 'manual'; }
		return $cbm;
	}


	/**
	 * Cookiebot_WP Check if Cookiebot is active in admin
	 *
	 * @version 3.1.0
	 * @since		3.1.0
	 */
	public static function cookiebot_disabled_in_admin() {
			if(is_multisite() && get_site_option('cookiebot-nooutput-admin',false)) {
				return true;
			}
			elseif(get_option('cookiebot-nooutput-admin',false)) {
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
	function get_language($onlyFromSetting=false) {
		// Get language set in setting page - if empty use WP language info
		$lang = get_option('cookiebot-language');
		if(!empty($lang)) {
			if($lang != '_wp') {
				return $lang;
			}
		}

		if($onlyFromSetting) {
			return $lang; //We want only to get if already set
		}

		//Language not set - use WP language
		if($lang == '_wp') {
			$lang = get_bloginfo('language'); //Gets language in en-US format
			if(!empty($lang)) {
				list($lang) = explode('-',$lang); //Changes format from eg. en-US to en.
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
	function wp_rocket_exclude_external_js($external_js_hosts) {
		$external_js_hosts[] = 'consent.cookiebot.com';      // Add cookiebot domains
		$external_js_hosts[] = 'consentcdn.cookiebot.com';
		return $external_js_hosts;
	}


	/**
	 * Cookiebot_WP Check if WP Cookie Consent API is active
	 *
	 * @version 3.5.0
	 * @since		3.5.0
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
	 * @since 	3.5.0
	 */
	public function get_default_wp_consent_api_mapping() {
		return array(
			'n=1;p=1;s=1;m=1' =>
				array('preferences'=>1,'statistics'=>1,'statistics-anonymous'=>0,'marketing'=>1),
			'n=1;p=1;s=1;m=0' =>
				array('preferences'=>1,'statistics'=>1,'statistics-anonymous'=>1,'marketing'=>0),
			'n=1;p=1;s=0;m=1' =>
				array('preferences'=>1,'statistics'=>0,'statistics-anonymous'=>0,'marketing'=>1),
			'n=1;p=1;s=0;m=0' =>
				array('preferences'=>1,'statistics'=>0,'statistics-anonymous'=>0,'marketing'=>0),
			'n=1;p=0;s=1;m=1' =>
				array('preferences'=>0,'statistics'=>1,'statistics-anonymous'=>0,'marketing'=>1),
			'n=1;p=0;s=1;m=0' =>
				array('preferences'=>0,'statistics'=>1,'statistics-anonymous'=>0,'marketing'=>0),
			'n=1;p=0;s=0;m=1' =>
				array('preferences'=>0,'statistics'=>0,'statistics-anonymous'=>0,'marketing'=>1),
			'n=1;p=0;s=0;m=0' =>
				array('preferences'=>0,'statistics'=>0,'statistics-anonymous'=>0,'marketing'=>0),
		);

	}

	/**
	 * Cookiebot_WP Get the mapping between Consent Level API and Cookiebot
	 * Returns array where key is the consent level api category and value
	 * is the mapped Cookiebot category.
	 *
	 * @version 3.5.0
	 * @since 	3.5.0
	 */
	public function get_wp_consent_api_mapping() {
		$mDefault = $this->get_default_wp_consent_api_mapping();
		$mapping = get_option( 'cookiebot-consent-mapping', $mDefault);

		$mapping = ( '' === $mapping ) ? $mDefault : $mapping;

		foreach($mDefault as $k=>$v) {
			if(!isset($mapping[$k])) {
				$mapping[$k] = $v;
			}
			else {
				foreach($v as $vck=>$vcv) {
					if(!isset($mapping[$k][$vck])) {
						$mapping[$k][$vck] = $vcv;
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
	 * @since 	3.5.0
	 */
	function cookiebot_enqueue_consent_api_scripts() {
		wp_register_script( 'cookiebot-wp-consent-level-api-integration', plugins_url( 'cookiebot/js/cookiebot-wp-consent-level-api-integration.js', 'cookiebot' ) );
		wp_enqueue_script( 'cookiebot-wp-consent-level-api-integration' );
		wp_localize_script( 'cookiebot-wp-consent-level-api-integration', 'cookiebot_category_mapping', $this->get_wp_consent_api_mapping() );
	}


	/**
	 * Display admin notice for recommending cookiebot
	 *
	 * @version 2.0.5
	 * @since 2.0.5
	 */
	function cookiebot_admin_notices() {
		if( ! $this->cookiebot_valid_admin_recommendation() ) {
			return false;
		}
		$two_week_review_ignore = add_query_arg( array( 'cookiebot_admin_notice' => 'hide' ) );
		$two_week_review_temp = add_query_arg( array( 'cookiebot_admin_notice' => 'two_week' ) );

		$notices = array(
			'title' => __('Leave A Review?', 'cookiebot'),
			'msg' => __('We hope you enjoy using WordPress Cookiebot! Would you consider leaving us a review on WordPress.org?', 'cookiebot'),
			'link' => '<li><span class="dashicons dashicons-external"></span><a href="https://wordpress.org/support/plugin/cookiebot/reviews?filter=5&rate=5#new-post" target="_blank">' . __('Sure! I\'d love to!', 'cookiebot') . '</a></li>
                         <li><span class="dashicons dashicons-smiley"></span><a href="' . $two_week_review_ignore . '"> ' . __('I\'ve already left a review', 'cookiebot') . '</a></li>
                         <li><span class="dashicons dashicons-calendar-alt"></span><a href="' . $two_week_review_temp . '">' . __('Maybe Later', 'cookiebot') . '</a></li>
                         <li><span class="dashicons dashicons-dismiss"></span><a href="' . $two_week_review_ignore . '">' . __('Never show again', 'cookiebot') . '</a></li>',
			'later_link' => $two_week_review_temp,
			'int' => 14
		);

		echo '<div class="update-nag cookiebot-admin-notice">
                                <div class="cookiebot-notice-logo"></div>
                                <p class="cookiebot-notice-title">' . $notices['title'] . '</p>
                                <p class="cookiebot-notice-body">' . $notices['msg'] . '</p>
                                <ul class="cookiebot-notice-body wd-blue">' . $notices['link'] . '</ul>
                                <a href="' . $notices['later_link'] . '" class="dashicons dashicons-dismiss"></a>
                              </div>';

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
	function cookiebot_valid_admin_recommendation() {
		//Default - the recommendation is allowed to be visible
		$return = true;

		$option = get_option('cookiebot_notice_recommend');

		if( $option != false ) {
			 //Never show again is clicked
			if( $option == 'hide' ) {
				$return = false;
			}
			elseif( is_numeric($option) && strtotime('now') < $option ) {
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
	 function save_notice_link() {
		if( isset( $_GET['cookiebot_admin_notice'] ) ) {
			if( $_GET['cookiebot_admin_notice'] == 'hide' ) {
				update_option('cookiebot_notice_recommend', 'hide' );
			}
			else {
				update_option('cookiebot_notice_recommend', strtotime('+2 weeks') );
			}
		}
	}




	/**
	 * Cookiebot_WP Fix plugin conflicts related to Cookiebot
	 *
	 * @version 3.2.0
	 * @since   3.3.0
	 */
	function cookiebot_fix_plugin_conflicts() {
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
	function _cookiebot_plugin_conflict_divi() {
		if ( defined( 'ET_FB_ENABLED' ) ) {
			if ( 	ET_FB_ENABLED &&
						$this->cookiebot_disabled_in_admin() &&
						$this->get_cookie_blocking_mode() == 'auto' ) {

					define('COOKIEBOT_DISABLE_ON_PAGE',true); //Disable Cookiebot on the current page

			}
		}
	}

	/**
	 * Cookiebot_WP Fix plugin conflicts with page builders - whitelist JS files in automode
	 *
	 * @version 3.2.0
	 * @since   3.3.0
	 */
	function _cookiebot_plugin_conflict_scripttags( $tag, $handle ) {

		//Check if Elementor Page Builder active
		if( defined( 'ELEMENTOR_VERSION' ) ) {
			if( in_array( $handle, [
				'jquery-core',
				'elementor-frontend-modules',
				'elementor-frontend',
				'wp-tinymce' ,
				'underscore',
				'backbone',
				'backbone-marionette',
				'backbone-radio',
				'elementor-common-modules',
				'elementor-dialog',
				'elementor-common',
			] ) )  {
				$tag = str_replace( '<script ', '<script data-cookieconsent="ignore" ', $tag );
			}
		}

		//Check if WPBakery Page Builder active
		if ( defined( 'WPB_VC_VERSION' ) ) {
			if( in_array( $handle, [
				'jquery-core',
				'jquery-ui-core',
				'jquery-ui-sortable',
				'jquery-ui-mouse',
				'jquery-ui-widget',
				'vc_editors-templates-preview-js',
				'vc-frontend-editor-min-js',
				'vc_inline_iframe_js',
				'wpb_composer_front_js',
			] ) ) {
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
 * @version 1.6
 * @since   1.0
 * @return  string
 */
function cookiebot_assist($type='statistics') {
	//change to array
	if(!is_array($type)) { $type = array($type); }

	foreach($type as $tk=>$tv) {
		if(!in_array($tv,array('marketing','statistics','preferences'))) {
			unset($type[$tk]);
		}
	}
	if(sizeof($type) > 0) {
		return ' type="text/plain" data-cookieconsent="'.implode(',',$type).'"';
	}
	return '';
}


/**
 * Helper function to check if cookiebot is active.
 * Useful for other plugins adding support for Cookiebot.
 *
 * @version 2.2.2
 * @since   1.2
 * @return  string
 */
function cookiebot_active() {
	$cbid = Cookiebot_WP::get_cbid();
	if(!empty($cbid)) {
		return true;
	}
	return false;
}


if(!function_exists('cookiebot')) {
	/**
	 * Returns the main instance of Cookiebot_WO to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  Cookiebot_WP
	 */
	function cookiebot() {
		return Cookiebot_WP::instance();
	}
}

cookiebot();
