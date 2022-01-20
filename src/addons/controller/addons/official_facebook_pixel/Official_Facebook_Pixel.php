<?php

namespace cybot\cookiebot\addons\controller\addons\official_facebook_pixel;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;
use function cybot\cookiebot\lib\cookiebot_addons_remove_class_action;

class Official_Facebook_Pixel extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME                  = 'Official Facebook Pixel';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable Facebook Pixel.';
	const OPTION_NAME                 = 'official_facebook_pixel';
	const PLUGIN_FILE_PATH            = 'official-facebook-pixel/facebook-for-wordpress.php';
	const DEFAULT_COOKIE_TYPES        = array( 'statistics', 'marketing' );
	const ENABLE_ADDON_BY_DEFAULT     = false;
	const SVN_URL_BASE_PATH           = 'https://plugins.svn.wordpress.org/official-facebook-pixel/trunk/';
	const SVN_URL_DEFAULT_SUB_PATH    = 'facebook-for-wordpress.php';

	/**
	 * Disable scripts if state not accepted
	 *
	 * @since 1.3.0
	 */
	public function load_addon_configuration() {
		// Pageview
		$this->buffer_output->add_tag(
			'wp_head',
			10,
			array(
				'connect.facebook.net' => $this->get_cookie_types(),
				'fbq'                  => $this->get_cookie_types(),
			)
		);

		// Caldera forms integration
		$this->buffer_output->add_tag(
			'caldera_forms_ajax_return',
			10,
			array(
				'fbq' => $this->get_cookie_types(),
			),
			false
		);

		// Gravity forms integration
		$this->buffer_output->add_tag(
			'gform_confirmation',
			10,
			array(
				'fbq' => $this->get_cookie_types(),
			),
			false
		);

		// Ninja forms integration
		$this->buffer_output->add_tag(
			'ninja_forms_submission_actions',
			10,
			array(
				'fbq' => $this->get_cookie_types(),
			),
			false
		);

		// WP Ecommerce integration
		$this->buffer_output->add_tag(
			'wpsc_add_to_cart_json_response',
			11,
			array(
				'fbq' => $this->get_cookie_types(),
			),
			false
		);

		$this->buffer_output->add_tag(
			'wpsc_transaction_results_shutdown',
			11,
			array(
				'fbq' => $this->get_cookie_types(),
			),
			false
		);

		// WP Forms integration
		$this->buffer_output->add_tag(
			'wp_footer',
			20,
			array(
				'fbq' => $this->get_cookie_types(),
			),
			false
		);

		// Catching most events created with \FacebookPixelPlugin\Integration\FacebookWordpressIntegrationBase::addPixelFireForHook
		$this->buffer_output->add_tag(
			'wp_footer',
			11,
			array(
				'fbq' => $this->get_cookie_types(),
			),
			false
		);

		// Server side pixel events
		// Caldera form integration
		cookiebot_addons_remove_class_action(
			'caldera_forms_ajax_return',
			'FacebookPixelPlugin\Integration\FacebookWordpressCalderaForm',
			'injectLeadEvent'
		);

		// Contact Form 7 integration
		cookiebot_addons_remove_class_action(
			'wpcf7_submit',
			'FacebookPixelPlugin\Integration\FacebookWordpressContactForm7',
			'trackServerEvent'
		);
		cookiebot_addons_remove_class_action(
			'wpcf7_ajax_json_echo',
			'FacebookPixelPlugin\Integration\FacebookWordpressContactForm7',
			'injectLeadEvent',
			20
		);
		cookiebot_addons_remove_class_action(
			'wpcf7_feedback_response',
			'FacebookPixelPlugin\Integration\FacebookWordpressContactForm7',
			'injectLeadEvent',
			20
		);

		// Formidable Form integration
		cookiebot_addons_remove_class_action(
			'frm_after_create_entry',
			'FacebookPixelPlugin\Integration\FacebookWordpressFormidableForm',
			'trackServerEvent',
			20
		);
		cookiebot_addons_remove_class_action(
			'wp_footer',
			'FacebookPixelPlugin\Integration\FacebookWordpressFormidableForm',
			'injectLeadEvent',
			20
		);

		// Easy digital downloads integration
		cookiebot_addons_remove_class_action(
			'edd_payment_receipt_after',
			'FacebookPixelPlugin\Integration\FacebookWordpressEasyDigitalDownloads',
			'trackPurchaseEvent'
		);
		cookiebot_addons_remove_class_action(
			'edd_after_download_content',
			'FacebookPixelPlugin\Integration\FacebookWordpressEasyDigitalDownloads',
			'injectAddToCartEvent',
			11
		);
		cookiebot_addons_remove_class_action(
			'edd_after_checkout_cart',
			'FacebookPixelPlugin\Integration\FacebookWordpressEasyDigitalDownloads',
			'injectInitiateCheckoutEvent',
			11
		);
		cookiebot_addons_remove_class_action(
			'edd_after_download_content',
			'FacebookPixelPlugin\Integration\FacebookWordpressEasyDigitalDownloads',
			'injectViewContentEvent',
			11
		);

		// Gravity forms integration
		cookiebot_addons_remove_class_action(
			'gform_confirmation',
			'FacebookPixelPlugin\Integration\FacebookWordpressGravityForms',
			'injectLeadEvent'
		);

		// Mailchimp for WP integration
		cookiebot_addons_remove_class_action(
			'mc4wp_form_subscribed',
			'FacebookPixelPlugin\Integration\FacebookWordpressMailchimpForWp',
			'injectLeadEvent',
			11
		);

		// Ninja forms integration
		cookiebot_addons_remove_class_action(
			'ninja_forms_submission_actions',
			'FacebookPixelPlugin\Integration\FacebookWordpressNinjaForms',
			'injectLeadEvent'
		);

		// WooCommerce integration
		cookiebot_addons_remove_class_action(
			'woocommerce_after_checkout_form',
			'FacebookPixelPlugin\Integration\FacebookWordpressWooCommerce',
			'trackInitiateCheckout',
			40
		);
		cookiebot_addons_remove_class_action(
			'woocommerce_add_to_cart',
			'FacebookPixelPlugin\Integration\FacebookWordpressWooCommerce',
			'trackAddToCartEvent',
			40
		);
		cookiebot_addons_remove_class_action(
			'woocommerce_thankyou',
			'FacebookPixelPlugin\Integration\FacebookWordpressWooCommerce',
			'trackPurchaseEvent',
			40
		);
		cookiebot_addons_remove_class_action(
			'woocommerce_payment_complete',
			'FacebookPixelPlugin\Integration\FacebookWordpressWooCommerce',
			'trackPurchaseEvent',
			40
		);

		// WP Ecommerce integration
		cookiebot_addons_remove_class_action(
			'wpsc_add_to_cart_json_response',
			'FacebookPixelPlugin\Integration\FacebookWordpressWPECommerce',
			'injectAddToCartEvent',
			11
		);
		cookiebot_addons_remove_class_action(
			'wpsc_before_shopping_cart_page',
			'FacebookPixelPlugin\Integration\FacebookWordpressWPECommerce',
			'injectInitiateCheckoutEvent',
			11
		);
		cookiebot_addons_remove_class_action(
			'wpsc_transaction_results_shutdown',
			'FacebookPixelPlugin\Integration\FacebookWordpressWPECommerce',
			'injectPurchaseEvent',
			11
		);

		// WP Forms integration
		cookiebot_addons_remove_class_action(
			'wpforms_process_before',
			'FacebookPixelPlugin\Integration\FacebookWordpressWPForms',
			'trackEvent',
			20
		);
	}

	/**
	 * @return array
	 */
	public function get_extra_information() {
		return array(
			__( 'Blocks Official Facebook Pixel scripts', 'cookiebot' ),
		);
	}
}
