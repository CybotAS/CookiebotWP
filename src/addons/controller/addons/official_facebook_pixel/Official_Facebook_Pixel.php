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

	// Server side pixel events constants
	const CALDERA_INTEGRATION_CLASS         = 'FacebookPixelPlugin\Integration\FacebookWordpressCalderaForm';
	const CONTACT_FORM_INTEGRATION_CLASS    = 'FacebookPixelPlugin\Integration\FacebookWordpressContactForm7';
	const FORMIDABLE_FORM_INTEGRATION_CLASS = 'FacebookPixelPlugin\Integration\FacebookWordpressFormidableForm';
	const EASY_DIGITAL_INTEGRATION_CLASS    = 'FacebookPixelPlugin\Integration\FacebookWordpressEasyDigitalDownloads';
	const GRAVITY_FORMS_INTEGRATION_CLASS   = 'FacebookPixelPlugin\Integration\FacebookWordpressGravityForms';
	const MAILCHIMP_INTEGRATION_CLASS       = 'FacebookPixelPlugin\Integration\FacebookWordpressMailchimpForWp';
	const NINJA_FORMS_INTEGRATION_CLASS     = 'FacebookPixelPlugin\Integration\FacebookWordpressNinjaForms';
	const WOOCOMMERCE_INTEGRATION_CLASS     = 'FacebookPixelPlugin\Integration\FacebookWordpressWooCommerce';
	const WP_ECOMMERCE_INTEGRATION_CLASS    = 'FacebookPixelPlugin\Integration\FacebookWordpressWPECommerce';
	const WP_FORMS_INTEGRATION_CLASS        = 'FacebookPixelPlugin\Integration\FacebookWordpressWPForms';


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
			$this::CALDERA_INTEGRATION_CLASS,
			'injectLeadEvent'
		);

		// Contact Form 7 integration
		cookiebot_addons_remove_class_action(
			'wpcf7_submit',
			$this::CONTACT_FORM_INTEGRATION_CLASS,
			'trackServerEvent'
		);
		cookiebot_addons_remove_class_action(
			'wpcf7_ajax_json_echo',
			$this::CONTACT_FORM_INTEGRATION_CLASS,
			'injectLeadEvent',
			20
		);
		cookiebot_addons_remove_class_action(
			'wpcf7_feedback_response',
			$this::CONTACT_FORM_INTEGRATION_CLASS,
			'injectLeadEvent',
			20
		);

		// Formidable Form integration
		cookiebot_addons_remove_class_action(
			'frm_after_create_entry',
			$this::FORMIDABLE_FORM_INTEGRATION_CLASS,
			'trackServerEvent',
			20
		);
		cookiebot_addons_remove_class_action(
			'wp_footer',
			$this::FORMIDABLE_FORM_INTEGRATION_CLASS,
			'injectLeadEvent',
			20
		);

		// Easy digital downloads integration
		cookiebot_addons_remove_class_action(
			'edd_payment_receipt_after',
			$this::EASY_DIGITAL_INTEGRATION_CLASS,
			'trackPurchaseEvent'
		);
		cookiebot_addons_remove_class_action(
			'edd_after_download_content',
			$this::EASY_DIGITAL_INTEGRATION_CLASS,
			'injectAddToCartEvent',
			11
		);
		cookiebot_addons_remove_class_action(
			'edd_after_checkout_cart',
			$this::EASY_DIGITAL_INTEGRATION_CLASS,
			'injectInitiateCheckoutEvent',
			11
		);
		cookiebot_addons_remove_class_action(
			'edd_after_download_content',
			$this::EASY_DIGITAL_INTEGRATION_CLASS,
			'injectViewContentEvent',
			11
		);

		// Gravity forms integration
		cookiebot_addons_remove_class_action(
			'gform_confirmation',
			$this::GRAVITY_FORMS_INTEGRATION_CLASS,
			'injectLeadEvent'
		);

		// Mailchimp for WP integration
		cookiebot_addons_remove_class_action(
			'mc4wp_form_subscribed',
			$this::MAILCHIMP_INTEGRATION_CLASS,
			'injectLeadEvent',
			11
		);

		// Ninja forms integration
		cookiebot_addons_remove_class_action(
			'ninja_forms_submission_actions',
			$this::NINJA_FORMS_INTEGRATION_CLASS,
			'injectLeadEvent'
		);

		// WooCommerce integration
		cookiebot_addons_remove_class_action(
			'woocommerce_after_checkout_form',
			$this::WOOCOMMERCE_INTEGRATION_CLASS,
			'trackInitiateCheckout',
			40
		);
		cookiebot_addons_remove_class_action(
			'woocommerce_add_to_cart',
			$this::WOOCOMMERCE_INTEGRATION_CLASS,
			'trackAddToCartEvent',
			40
		);
		cookiebot_addons_remove_class_action(
			'woocommerce_thankyou',
			$this::WOOCOMMERCE_INTEGRATION_CLASS,
			'trackPurchaseEvent',
			40
		);
		cookiebot_addons_remove_class_action(
			'woocommerce_payment_complete',
			$this::WOOCOMMERCE_INTEGRATION_CLASS,
			'trackPurchaseEvent',
			40
		);

		// WP Ecommerce integration
		cookiebot_addons_remove_class_action(
			'wpsc_add_to_cart_json_response',
			$this::WP_ECOMMERCE_INTEGRATION_CLASS,
			'injectAddToCartEvent',
			11
		);
		cookiebot_addons_remove_class_action(
			'wpsc_before_shopping_cart_page',
			$this::WP_ECOMMERCE_INTEGRATION_CLASS,
			'injectInitiateCheckoutEvent',
			11
		);
		cookiebot_addons_remove_class_action(
			'wpsc_transaction_results_shutdown',
			$this::WP_ECOMMERCE_INTEGRATION_CLASS,
			'injectPurchaseEvent',
			11
		);

		// WP Forms integration
		cookiebot_addons_remove_class_action(
			'wpforms_process_before',
			$this::WP_FORMS_INTEGRATION_CLASS,
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
