<?php

namespace cookiebot_addons\controller\addons\official_facebook_pixel;

use cookiebot_addons\controller\addons\Cookiebot_Addons_Interface;
use cookiebot_addons\lib\Cookie_Consent_Interface;
use cookiebot_addons\lib\Settings_Service_Interface;
use cookiebot_addons\lib\script_loader_tag\Script_Loader_Tag_Interface;
use cookiebot_addons\lib\buffer\Buffer_Output_Interface;
use FacebookPixelPlugin\Core\FacebookPluginConfig;

class Official_Facebook_Pixel implements Cookiebot_Addons_Interface {

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
	 * @param $settings          Settings_Service_Interface
	 * @param $script_loader_tag Script_Loader_Tag_Interface
	 * @param $cookie_consent    Cookie_Consent_Interface
	 * @param $buffer_output     Buffer_Output_Interface
	 *
	 * @since 1.3.0
	 */
	public function __construct(
		Settings_Service_Interface $settings,
		Script_Loader_Tag_Interface $script_loader_tag,
		Cookie_Consent_Interface $cookie_consent,
		Buffer_Output_Interface $buffer_output
	) {
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
		add_action( 'wp_loaded', array( $this, 'cookiebot_addon_official_facebook_pixel' ), 5 );
	}

	/**
	 * Disable scripts if state not accepted
	 *
	 * @since 1.3.0
	 */
	public function cookiebot_addon_official_facebook_pixel() {
		// Pageview
		$this->buffer_output->add_tag( 'wp_head', 10, array(
			'connect.facebook.net' => $this->get_cookie_types(),
			'fbq'                  => $this->get_cookie_types(),
		) );

		// Caldera forms integration
		$this->buffer_output->add_tag( 'caldera_forms_ajax_return', 10, array(
			'fbq' => $this->get_cookie_types(),
		), false );

		// Gravity forms integration
		$this->buffer_output->add_tag( 'gform_confirmation', 10, array(
			'fbq' => $this->get_cookie_types(),
		), false );

		// Ninja forms integration
		$this->buffer_output->add_tag( 'ninja_forms_submission_actions', 10, array(
			'fbq' => $this->get_cookie_types(),
		), false );

		// WP Ecommerce integration
		$this->buffer_output->add_tag( 'wpsc_add_to_cart_json_response', 11, array(
			'fbq' => $this->get_cookie_types(),
		), false );

		$this->buffer_output->add_tag( 'wpsc_transaction_results_shutdown', 11, array(
			'fbq' => $this->get_cookie_types(),
		), false );

		// WP Forms integration
		$this->buffer_output->add_tag( 'wp_footer', 20, array(
			'fbq' => $this->get_cookie_types(),
		), false );

		// Catching most events created with \FacebookPixelPlugin\Integration\FacebookWordpressIntegrationBase::addPixelFireForHook
		$this->buffer_output->add_tag( 'wp_footer', 11, array(
			'fbq' => $this->get_cookie_types(),
		), false );

		// Server side pixel events
		// Caldera form integration
		cookiebot_addons_remove_class_action( 'caldera_forms_ajax_return',
			'FacebookPixelPlugin\Integration\FacebookWordpressCalderaForm', 'injectLeadEvent' );

		// Contact Form 7 integration
		cookiebot_addons_remove_class_action( 'wpcf7_submit',
			'FacebookPixelPlugin\Integration\FacebookWordpressContactForm7', 'trackServerEvent' );
		cookiebot_addons_remove_class_action( 'wpcf7_ajax_json_echo',
			'FacebookPixelPlugin\Integration\FacebookWordpressContactForm7', 'injectLeadEvent', 20 );
		cookiebot_addons_remove_class_action( 'wpcf7_feedback_response',
			'FacebookPixelPlugin\Integration\FacebookWordpressContactForm7', 'injectLeadEvent', 20 );

		// Formidable Form integration
		cookiebot_addons_remove_class_action( 'frm_after_create_entry',
			'FacebookPixelPlugin\Integration\FacebookWordpressFormidableForm', 'trackServerEvent', 20 );
		cookiebot_addons_remove_class_action( 'wp_footer',
			'FacebookPixelPlugin\Integration\FacebookWordpressFormidableForm', 'injectLeadEvent', 20 );

		// Easy digital downloads integration
		cookiebot_addons_remove_class_action( 'edd_payment_receipt_after',
			'FacebookPixelPlugin\Integration\FacebookWordpressEasyDigitalDownloads', 'trackPurchaseEvent' );
		cookiebot_addons_remove_class_action( 'edd_after_download_content',
			'FacebookPixelPlugin\Integration\FacebookWordpressEasyDigitalDownloads', 'injectAddToCartEvent', 11 );
		cookiebot_addons_remove_class_action( 'edd_after_checkout_cart',
			'FacebookPixelPlugin\Integration\FacebookWordpressEasyDigitalDownloads', 'injectInitiateCheckoutEvent',
			11 );
		cookiebot_addons_remove_class_action( 'edd_after_download_content',
			'FacebookPixelPlugin\Integration\FacebookWordpressEasyDigitalDownloads', 'injectViewContentEvent', 11 );

		// Gravity forms integration
		cookiebot_addons_remove_class_action( 'gform_confirmation',
			'FacebookPixelPlugin\Integration\FacebookWordpressGravityForms', 'injectLeadEvent' );

		// Mailchimp for WP integration
		cookiebot_addons_remove_class_action( 'mc4wp_form_subscribed',
			'FacebookPixelPlugin\Integration\FacebookWordpressMailchimpForWp', 'injectLeadEvent', 11 );

		// Ninja forms integration
		cookiebot_addons_remove_class_action( 'ninja_forms_submission_actions',
			'FacebookPixelPlugin\Integration\FacebookWordpressNinjaForms', 'injectLeadEvent' );

		// WooCommerce integration
		cookiebot_addons_remove_class_action( 'woocommerce_after_checkout_form',
			'FacebookPixelPlugin\Integration\FacebookWordpressWooCommerce', 'trackInitiateCheckout', 40 );
		cookiebot_addons_remove_class_action( 'woocommerce_add_to_cart',
			'FacebookPixelPlugin\Integration\FacebookWordpressWooCommerce', 'trackAddToCartEvent', 40 );
		cookiebot_addons_remove_class_action( 'woocommerce_thankyou',
			'FacebookPixelPlugin\Integration\FacebookWordpressWooCommerce', 'trackPurchaseEvent', 40 );
		cookiebot_addons_remove_class_action( 'woocommerce_payment_complete',
			'FacebookPixelPlugin\Integration\FacebookWordpressWooCommerce', 'trackPurchaseEvent', 40 );

		// WP Ecommerce integration
		cookiebot_addons_remove_class_action( 'wpsc_add_to_cart_json_response',
			'FacebookPixelPlugin\Integration\FacebookWordpressWPECommerce', 'injectAddToCartEvent', 11 );
		cookiebot_addons_remove_class_action( 'wpsc_before_shopping_cart_page',
			'FacebookPixelPlugin\Integration\FacebookWordpressWPECommerce', 'injectInitiateCheckoutEvent', 11 );
		cookiebot_addons_remove_class_action( 'wpsc_transaction_results_shutdown',
			'FacebookPixelPlugin\Integration\FacebookWordpressWPECommerce', 'injectPurchaseEvent', 11 );

		// WP Forms integration
		cookiebot_addons_remove_class_action( 'wpforms_process_before',
			'FacebookPixelPlugin\Integration\FacebookWordpressWPForms', 'trackEvent', 20 );
	}

	/**
	 * Return addon/plugin name
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_addon_name() {
		return 'Official Facebook Pixel';
	}

	/**
	 * Default placeholder content
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_default_placeholder() {
		return 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable Social Share buttons.';
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
		return $this->settings->get_placeholder( $this->get_option_name(), $this->get_default_placeholder(),
			cookiebot_addons_output_cookie_types( $this->get_cookie_types() ), $src );
	}

	/**
	 * Option name in the database
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_option_name() {
		return 'official_facebook_pixel';
	}

	/**
	 * Plugin file name
	 *
	 * @return string
	 *
	 * @since 1.3.0
	 */
	public function get_plugin_file() {
		return 'official-facebook-pixel/facebook-for-wordpress.php';
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
		return '<p>' . esc_html__( 'Blocks Official Facebook Pixel scripts', 'cookiebot-addons' ) . '</p>';
	}

	/**
	 * Returns the url of WordPress SVN repository or another link where we can verify the plugin file.
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_svn_url() {
		return 'https://plugins.svn.wordpress.org/official-facebook-pixel/trunk/facebook-for-wordpress.php';
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
