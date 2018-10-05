<?php
/*
Plugin Name: Cookiebot | GDPR Compliant Cookie Consent and Notice
Plugin URI: https://cookiebot.com/
Description: Cookiebot is a fully GDPR & ePrivacy compliant cookie consent solution supporting prior consent, cookie declaration, and documentation of consents. Easy to install, implement and configure.
Author: Cybot A/S
Version: 2.1.0
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
	public $version = '2.1.0';

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
	 * @version 1.6.1
	 * @since   1.0.0
	 * @access  public
	 */
	function __construct() {
		add_action('plugins_loaded', array($this, 'cookiebot_init'), 5);
	}

	/**
	 * Cookiebot_WP Init Cookiebot.
	 *
	 * @version 2.0.1
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
		else {
			if( (!defined('COOKIEBOT_ADDONS_STANDALONE') || COOKIEBOT_ADDONS_STANDALONE != true || !defined('COOKIE_ADDONS_LOADED')) && $dismissAddons !== true ) {
				//Make sure we got a PHP version that works
				if(version_compare(PHP_VERSION, '5.4.0', '>=')) {
					include_once('addons/cookiebot-addons-init.php');
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
			add_action('admin_menu', array($this,'add_menu'),1);
			add_action('admin_menu', array($this,'add_menu_iab'),11);
			add_action('admin_init', array($this,'register_cookiebot_settings'));
			add_action('wp_dashboard_setup',  array($this,'add_dashboard_widgets'));
			//adding cookie banner in admin area too
			add_action('admin_head', array($this,'add_js'));
			add_action( 'admin_notices', array( $this, 'cookiebot_admin_notices' ) );
			add_action('admin_init', array($this,'save_notice_link'));
		}


		// Set up localisation
		load_plugin_textdomain('cookiebot', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );

		//add JS
		add_action('wp_head', array($this,'add_js'));
		add_shortcode('cookie_declaration', array($this,'show_declaration'));

		//Add filter if WP rocket is enabled
		if(defined('WP_ROCKET_VERSION')) {
			add_filter('rocket_minify_excluded_external_js', array($this,'wp_rocket_exclude_external_js'));
		}

		//Automatic update plugin
		if(is_admin() || (defined('DOING_CRON') && DOING_CRON)) {
			add_filter('auto_update_plugin', array($this,'automatic_updates'), 10, 2);
		}
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
		$cbid = get_option('cookiebot-cbid');
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
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_menu() {
		//Cookiebot Icon SVG base64 encoded
		$icon = 'data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgNzIgNTQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0iI0ZGRkZGRiIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNNDYuODcyNTkwMyA4Ljc3MzU4MzM0QzQxLjk0MzkwMzkgMy4zODI5NTAxMSAzNC44NDI0OTQ2IDAgMjYuOTQ4MjgxOSAwIDEyLjA2NTE1NjggMCAwIDEyLjAyNDQ3NzQgMCAyNi44NTc0MjE5YzAgMTQuODMyOTQ0NSAxMi4wNjUxNTY4IDI2Ljg1NzQyMTkgMjYuOTQ4MjgxOSAyNi44NTc0MjE5IDcuODk0MjEyNyAwIDE0Ljk5NTYyMi0zLjM4Mjk1MDIgMTkuOTI0MzA4NC04Ljc3MzU4MzQtMi44ODk2OTY3LTEuMzY4ODY2My01LjM5OTMxMS0zLjQwNTQzOS03LjMyODA4MzgtNS45MDk2MzU4LTMuMTIxNDMwNiAzLjIwOTQxMDQtNy40OTI5OTQ0IDUuMjA0MTI5MS0xMi4zMzIwMjU4IDUuMjA0MTI5MS05LjQ4NDM0NDQgMC0xNy4xNzI5MjQ3LTcuNjYyNjU3Mi0xNy4xNzI5MjQ3LTE3LjExNTAyMzhzNy42ODg1ODAzLTE3LjExNTAyMzcgMTcuMTcyOTI0Ny0xNy4xMTUwMjM3YzQuNzIzNDgyMiAwIDkuMDAxNTU1MiAxLjkwMDU5MzkgMTIuMTA2MjkyIDQuOTc2MzA5IDEuOTU2OTIzNy0yLjY0MTEzMSA0LjU1MDAyNjMtNC43ODU1MTgzIDcuNTUzODE3Ni02LjIwODQzMTg2eiIvPjxwYXRoIGQ9Ik01NS4zODAzMjgyIDQyLjY1MDE5OTFDNDYuMzMzNzIyNyA0Mi42NTAxOTkxIDM5IDM1LjM0MTIwMzEgMzkgMjYuMzI1MDk5NiAzOSAxNy4zMDg5OTYgNDYuMzMzNzIyNyAxMCA1NS4zODAzMjgyIDEwYzkuMDQ2NjA1NSAwIDE2LjM4MDMyODIgNy4zMDg5OTYgMTYuMzgwMzI4MiAxNi4zMjUwOTk2IDAgOS4wMTYxMDM1LTcuMzMzNzIyNyAxNi4zMjUwOTk1LTE2LjM4MDMyODIgMTYuMzI1MDk5NXptLjAyMTMwOTItNy43NTU2MzQyYzQuNzM3MDI3NiAwIDguNTc3MTQ3MS0zLjgyNzE3MiA4LjU3NzE0NzEtOC41NDgyMjc5IDAtNC43MjEwNTYtMy44NDAxMTk1LTguNTQ4MjI4LTguNTc3MTQ3MS04LjU0ODIyOC00LjczNzAyNzUgMC04LjU3NzE0NyAzLjgyNzE3Mi04LjU3NzE0NyA4LjU0ODIyOCAwIDQuNzIxMDU1OSAzLjg0MDExOTUgOC41NDgyMjc5IDguNTc3MTQ3IDguNTQ4MjI3OXoiLz48L2c+PC9zdmc+';
		add_menu_page( 'Cookiebot', __('Cookiebot','cookiebot'), 'manage_options', 'cookiebot', array($this,'settings_page'),$icon);

		add_submenu_page('cookiebot',__('Cookiebot Settings','cookiebot'),__('Settings','cookiebot'), 'manage_options', 'cookiebot',array($this,'settings_page'));
		add_submenu_page('cookiebot',__('Cookiebot Support','cookiebot'),__('Support','cookiebot'), 'manage_options', 'cookiebot_support',array($this,'support_page'));

		if(defined('COOKIEBOT_ADDONS_UNSUPPORTED_PHPVERSION')) {
			//Load prior consent page anyway - but from Cookiebot WP Core plugin.
			add_submenu_page( 'cookiebot', __( 'Prior Consent', 'cookiebot' ), __( 'Prior Consent', 'cookiebot' ), 'manage_options', 'cookiebot-addons', array($this,'setting_page_placeholder'	) );
		}
	}

	/**
	 * CookiebotWP add option menu page for IAB
     *
     * @version 2.0.3
     * @since 2.0.3
	 */
	function add_menu_iab() {
		add_submenu_page('cookiebot',__('IAB','cookiebot'),__('IAB','cookiebot'), 'manage_options', 'cookiebot_iab',array($this,'iab_page'));
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
	 * @version 1.4.0
	 * @since   1.0.0
	 */
	function register_cookiebot_settings() {
		register_setting('cookiebot', 'cookiebot-cbid');
		register_setting('cookiebot', 'cookiebot-language');
		register_setting('cookiebot', 'cookiebot-nooutput');
		register_setting('cookiebot', 'cookiebot-autoupdate');
		register_setting('cookiebot-iab', 'cookiebot-iab');
	}

	/**
	 * Cookiebot_WP Automatic update plugin if activated
	 *
	 * @version 1.5.0
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
		if(!get_option('cookiebot-autoupdate',true)) {
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
	function get_supported_languages() {
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
		asort($supportedLanguages,SORT_LOCALE_STRING);
		return $supportedLanguages;
	}

	/**
	 * Cookiebot_WP Output settings page
	 *
	 * @version 1.6.0
	 * @since   1.0.0
	 */
	function settings_page() {
		?>
		<div class="wrap">
			<h1><?php _e('Cookiebot Settings','cookiebot'); ?></h1>
			<a href="https://www.cookiebot.com">
				<img src="<?php echo plugins_url( 'cookiebot-logo.png', __FILE__ ); ?>" style="float:right;margin-left:1em;">
			</a>
			<p>
				<?php _e('Cookiebot enables your website to comply with current legislation in the EU on the use of cookies for user tracking and profiling. The EU ePrivacy Directive requires prior, informed consent of your site users, while the <a href="https://www.cookiebot.com/en/gdpr" target="_blank">General Data Protection Regulation (GDPR)</a> requires you to document each consent. At the same time you must be able to account for what user data you share with embedded third-party services on your website and where in the world the user data is sent.','cookiebot'); ?>
			</p>
			<form method="post" action="options.php">
				<?php settings_fields( 'cookiebot' ); ?>
				<?php do_settings_sections( 'cookiebot' ); ?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php _e('Cookiebot ID','cookiebot'); ?></th>
						<td>
							<input type="text" name="cookiebot-cbid" value="<?php echo esc_attr( get_option('cookiebot-cbid') ); ?>" style="width:300px" />
							<p class="description">
								<?php _e('Need an ID?','cookiebot'); ?>
								<a href="https://www.cookiebot.com/en/signup" target="_blank"><?php _e('Sign up for free on cookiebot.com','cookiebot'); ?></a>
							</p>
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
					<tr valign="top">
						<th scope="row"><?php _e('Auto-update Cookiebot','cookiebot'); ?></th>
						<td>
							<input type="checkbox" name="cookiebot-autoupdate" value="1" <?php checked(1,get_option('cookiebot-autoupdate',true), true); ?> />
							<p class="description">
								<?php _e('Automatic update your Cookiebot plugin when new releases becomes available.','cookiebot') ?>
							</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Hide Cookie Popup','cookiebot'); ?></th>
						<td>
							<input type="checkbox" name="cookiebot-nooutput" value="1" <?php checked(1,get_option('cookiebot-nooutput',false), true); ?> />
							<p class="description">
								<b><?php _e('This checkbox will remove the cookie consent banner from your website. The <i>[cookie_declaration]</i> shortcode will still be available.','cookiebot') ?></b><br />
								<?php _e('If you are using Google Tag Manager (or equal), you need to add the Cookiebot script in your Tag Manager.','cookiebot') ?><br />
								<?php _e('<a href="https://support.cookiebot.com/hc/en-us/articles/360003793854-Google-Tag-Manager-deployment" target="_blank">See a detailed guide here</a>','cookiebot') ?>
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
	 * Cookiebot_WP Cookiebot support page
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function support_page() {
		?>
		<div class="wrap">
			<h1><?php _e('Support','cookiebot'); ?></h1>
			<h2><?php _e('How to find my Cookiebot ID','cookiebot'); ?></h2>
			<p>
				<ol>
					<li><?php _e('Log in to your <a href="https://www.cookiebot.com/en/account" target="_blank">Cookiebot account</a>.','cookiebot'); ?></li>
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
	 * Cookiebot_WP Add Cookiebot JS to <head>
	 *
	 * @version 1.6.0
	 * @since   1.0.0
	 */
	function add_js() {
		$cbid = get_option('cookiebot-cbid');
		if(!empty($cbid) && !get_option('cookiebot-nooutput',false)) {
			$lang = $this->get_language();
			if(!empty($lang)) {
				$lang = ' data-culture="'.strtoupper($lang).'"'; //Use data-culture to define language
			}

			$iab = ( get_option('cookiebot-iab') != false ) ? 'data-framework="IAB"' : '';
			?>
			<script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" <?php echo $iab; ?> data-cbid="<?php echo $cbid; ?>"<?php echo $lang; ?> type="text/javascript" async></script>
			<?php
		}
	}

	/**
	 * Cookiebot_WP Output declation shortcode [cookie_declaration]
	 * Support attribute lang="LANGUAGE_CODE". Eg. lang="en".
	 *
	 * @version 1.4.2
	 * @since   1.0.0
	 */
	function show_declaration($atts) {
		$cbid = get_option('cookiebot-cbid');
		$lang = '';
		if(!empty($cbid)) {

			$atts = shortcode_atts(array(
					'lang' => $this->get_language(),
				), $atts, 'cookie_declaration'
			);

			if(!empty($atts['lang'])) {
				$lang = ' data-culture="'.strtoupper($atts['lang']).'"'; //Use data-culture to define language
			}
			return '<script id="CookieDeclaration" src="https://consent.cookiebot.com/'.$cbid.'/cd.js"'.$lang.' type="text/javascript" async></script>';
		}
		else {
			return __('Please add your Cookiebot ID to show Cookie Declarations','cookiebot');
		}
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
			'msg' => __('We hope you\'ve enjoyed using WordPress Cookiebot! Would you consider leaving us a review on WordPress.org?', 'cookiebot'),
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
	    /**
	     * Default - the recommendation is allowed to be visible
	     */
	    $return = true;

	    $option = get_option('cookiebot_notice_recommend');

	    if( $option != false ) {
		    /**
		     * Never show again is clicked
		     */
	        if( $option == 'hide' ) {
	            $return = false;
            }elseif( is_numeric($option) && strtotime('now') < $option ) {
		        /**
		         * Show me after 2 weeks is clicked and the time is not valid yet
		         */
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
           }else{
                update_option('cookiebot_notice_recommend', strtotime('+2 weeks') );
           }
        }
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
 * @version 1.2
 * @since   1.2
 * @return  string
 */
function cookiebot_active() {
	$cbid = get_option('cookiebot-cbid');
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
