<?php

namespace cookiebot_addons\controller\addons\facebook_for_woocommerce;

use cookiebot_addons\controller\addons\Cookiebot_Addons_Interface;
use cookiebot_addons\lib\buffer\Buffer_Output_Interface;
use cookiebot_addons\lib\script_loader_tag\Script_Loader_Tag_Interface;
use cookiebot_addons\lib\Cookie_Consent_Interface;
use cookiebot_addons\lib\Settings_Service_Interface;

class Facebook_For_Woocommerce implements Cookiebot_Addons_Interface {
	/**
	 * @var Settings_Service_Interface
	 *
	 * @since 1.3.0
	 */
	protected $settings;

	/**
	 * @var Script_Loader_Tag_Interface
	 *
	 * @since 1.3.0
	 */
	protected $script_loader_tag;

	/**
	 * @var Cookie_Consent_Interface
	 *
	 * @since 1.3.0
	 */
	public $cookie_consent;

	/**
	 * @var Buffer_Output_Interface
	 *
	 * @since 1.3.0
	 */
	protected $buffer_output;

	/**
	 * Jetpack constructor.
	 *
	 * @param $settings Settings_Service_Interface
	 * @param $script_loader_tag Script_Loader_Tag_Interface
	 * @param $cookie_consent Cookie_Consent_Interface
	 * @param $buffer_output Buffer_Output_Interface
	 *
	 * @since 1.2.0
	 */
	public function __construct( Settings_Service_Interface $settings, Script_Loader_Tag_Interface $script_loader_tag, Cookie_Consent_Interface $cookie_consent, Buffer_Output_Interface $buffer_output ) {
		$this->settings          = $settings;
		$this->script_loader_tag = $script_loader_tag;
		$this->cookie_consent    = $cookie_consent;
		$this->buffer_output     = $buffer_output;
	}

	/**
	 * Loads addon configuration
	 *
	 * @since 1.3.0
	 */
	public function load_configuration() {
		/**
		 * We add the action after wp_loaded and replace the original GA Google
		 * Analytics action with our own adjusted version.
		 */
		add_action( 'wp_loaded', array( $this, 'cookiebot_addon_facebook_for_woocommerce_tracking_code' ), 5 );
	}

	/**
	 * Manipulate the scripts if they are loaded.
	 *
	 * @since 1.3.0
	 */
	public function cookiebot_addon_facebook_for_woocommerce_tracking_code() {
		
		add_filter( 'wc_facebook_pixel_script_attributes', array( $this, 'cookiebot_addon_facebook_for_woocommerce_script_attributes' ) );
		
		/* Keep for old version */
		$this->buffer_output->add_tag( 'woocommerce_after_single_product', 2, array(
			'fbq(\'ViewContent\'' => $this->get_cookie_types()
		), false );

		$this->buffer_output->add_tag( 'woocommerce_after_shop_loop', 10, array(
			'fbq(\'ViewCategory\'' => $this->get_cookie_types()
		), false );

		$this->buffer_output->add_tag( 'pre_get_posts', 10, array(
			'fbq(\'Search\'' => $this->get_cookie_types()
		), false );

		$this->buffer_output->add_tag( 'woocommerce_after_cart', 10, array(
			'fbq(\'AddToCart\'' => $this->get_cookie_types()
		), false );

		$this->buffer_output->add_tag( 'woocommerce_add_to_cart', 2, array(
			'fbq(\'AddToCart\'' => $this->get_cookie_types()
		), false );

		$this->buffer_output->add_tag( 'wc_ajax_fb_inject_add_to_cart_event', 2, array(
			'fbq(\'AddToCart\'' => $this->get_cookie_types()
		), false );

		$this->buffer_output->add_tag( 'woocommerce_after_checkout_form', 10, array(
			'fbq(\'InitiateCheckout\'' => $this->get_cookie_types()
		), false );

		$this->buffer_output->add_tag( 'woocommerce_thankyou', 2, array(
			'fbq(\'Purchase\'' => $this->get_cookie_types()
		), false );

		$this->buffer_output->add_tag( 'woocommerce_payment_complete', 2, array(
			'fbq(\'Purchase\'' => $this->get_cookie_types()
		), false );

		$this->buffer_output->add_tag( 'wp_head', 10, array(
			'fbq(\'track\',' => $this->get_cookie_types()
		), false );

		/**
		 * inject base pixel
		 */
		//We always need to remove this untill consent is given - because we can force no execution before consent it given
		cookiebot_addons_remove_class_action( 'wp_footer', 'WC_Facebookcommerce_EventsTracker', 'inject_base_pixel_noscript' );

	}
	
	/**
	 * Return attributes for script tags
	 */
	function cookiebot_addon_facebook_for_woocommerce_script_attributes() {
		$attr = array();
		$attr['type'] = 'text/plain';
		$attr['data-cookieconsent'] = implode(',',$this->get_cookie_types());
		return $attr;
	}

	/**
	 * Return addon/plugin name
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_addon_name() {
		return 'Facebook For WooCommerce';
	}

	/**
	 * Default placeholder content
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_default_placeholder() {
		return 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable facebook shopping feature.';
	}

	/**
	 * Get placeholder content
	 *
	 * This function will check following features:
	 * - Current language
	 *
	 * @param $src
	 *
	 * @return bool|mixed
	 *
	 * @since 1.8.0
	 */
	public function get_placeholder( $src = '' ) {
		return $this->settings->get_placeholder( $this->get_option_name(), $this->get_default_placeholder(), cookiebot_addons_output_cookie_types( $this->get_cookie_types() ), $src );
	}

	/**
	 * Option name in the database
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_option_name() {
		return 'facebook_for_woocommerce';
	}

	/**
	 * Plugin file name
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_plugin_file() {
		return 'facebook-for-woocommerce/facebook-for-woocommerce.php';
	}

	/**
	 * Returns checked cookie types
	 * @return mixed
	 *
	 * @since 1.3.0
	 */
	public function get_cookie_types() {
		return $this->settings->get_cookie_types( $this->get_option_name(), $this->get_default_cookie_types() );
	}

	/**
	 * Returns default cookie types
	 * @return array
	 *
	 * @since 1.5.0
	 */
	public function get_default_cookie_types() {
		return array( 'marketing' );
	}

	/**
	 * Check if plugin is activated and checked in the backend
	 *
	 * @since 1.3.0
	 */
	public function is_addon_enabled() {
		return $this->settings->is_addon_enabled( $this->get_option_name() );
	}

	/**
	 * Checks if addon is installed
	 *
	 * @since 1.3.0
	 */
	public function is_addon_installed() {
		return $this->settings->is_addon_installed( $this->get_plugin_file() );
	}

	/**
	 * Checks if addon is activated
	 *
	 * @since 1.3.0
	 */
	public function is_addon_activated() {
		return $this->settings->is_addon_activated( $this->get_plugin_file() );
	}

	/**
	 * Retrieves current installed version of the addon
	 *
	 * @return bool
	 *
	 * @since 2.2.1
	 */
	public function get_addon_version() {
		return $this->settings->get_addon_version( $this->get_plugin_file() );
	}

	/**
	 * Checks if it does have custom placeholder content
	 *
	 * @return mixed
	 *
	 * @since 1.8.0
	 */
	public function has_placeholder() {
		return $this->settings->has_placeholder( $this->get_option_name() );
	}

	/**
	 * returns all placeholder contents
	 *
	 * @return mixed
	 *
	 * @since 1.8.0
	 */
	public function get_placeholders() {
		return $this->settings->get_placeholders( $this->get_option_name() );
	}

	/**
	 * Return true if the placeholder is enabled
	 *
	 * @return mixed
	 *
	 * @since 1.8.0
	 */
	public function is_placeholder_enabled() {
		return $this->settings->is_placeholder_enabled( $this->get_option_name() );
	}

	/**
	 * Adds extra information under the label
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_extra_information() {

	}

	/**
	 * Returns the url of WordPress SVN repository or another link where we can verify the plugin file.
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_svn_url() {
		return 'https://raw.githubusercontent.com/facebookincubator/facebook-for-woocommerce/master/facebook-commerce.php';
	}

	/**
	 * Placeholder helper overlay in the settings page.
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_placeholder_helper() {
		return '<p>Merge tags you can use in the placeholder text:</p><ul><li>%cookie_types - Lists required cookie types</li><li>[renew_consent]text[/renew_consent] - link to display cookie settings in frontend</li></ul>';
	}


	/**
	 * Returns parent class or false
	 *
	 * @return string|bool
	 *
	 * @since 2.1.3
	 */
	public function get_parent_class() {
		return get_parent_class( $this );
	}

	/**
	 * Action after enabling the addon on the settings page
	 *
	 * @since 2.2.0
	 */
	public function post_hook_after_enabling() {
		//do nothing
	}

	/**
	 * Cookiebot plugin is deactivated
	 *
	 * @since 2.2.0
	 */
	public function plugin_deactivated() {
		//do nothing
	}

	/**
	 * @return mixed
	 *
	 * @since 2.4.5
	 */
	public function extra_available_addon_option() {
		//do nothing
	}

	/**
	 * Returns boolean to enable/disable plugin by default
	 *
	 * @return bool
	 *
	 * @since 3.6.3
	 */
	public function enable_by_default() {
		return false;
	}

	/**
	 * Sets default settings for this addon
	 *
	 * @return array
	 *
	 * @since 3.6.3
	 */
	public function get_default_enable_setting() {
		return array(
			'enabled'     => 1,
			'cookie_type' => $this->get_default_cookie_types(),
			'placeholder' => $this->get_default_placeholder(),
		);
	}
}
