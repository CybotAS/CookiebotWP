<?php

namespace cybot\cookiebot\addons\controller\addons\facebook_for_woocommerce;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;
use function cybot\cookiebot\lib\cookiebot_addons_remove_class_action;

class Facebook_For_Woocommerce extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME                  = 'Facebook For WooCommerce';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable facebook shopping feature.';
	const OPTION_NAME                 = 'facebook_for_woocommerce';
	const PLUGIN_FILE_PATH            = 'facebook-for-woocommerce/facebook-for-woocommerce.php';
	const DEFAULT_COOKIE_TYPES        = array( 'marketing' );
	const SVN_URL_BASE_PATH           = 'https://raw.githubusercontent.com/facebookincubator/facebook-for-woocommerce/master/';
	const SVN_URL_DEFAULT_SUB_PATH    = 'facebook-commerce.php';

	// Events
	const FB_VIEW_CONTENT  = 'fbq(\'ViewContent\'';
	const FB_VIEW_CATEGORY = 'fbq(\'ViewCategory\'';
	const FB_SEARCH        = 'fbq(\'Search\'';
	const FB_ADD_TO_CART   = 'fbq(\'AddToCart\'';
	const FB_INIT_CHECKOUT = 'fbq(\'InitiateCheckout\'';
	const FB_PURCHASE      = 'fbq(\'Purchase\'';
	const FB_TRACK         = 'fbq(\'track\',';

	public function load_addon_configuration() {
		add_filter( 'wc_facebook_pixel_script_attributes', array( $this, 'cookiebot_addon_facebook_for_woocommerce_script_attributes' ) );

		/* Keep for old version */
		$this->buffer_output->add_tag(
			'woocommerce_after_single_product',
			2,
			array(
				self::FB_VIEW_CONTENT => $this->get_cookie_types(),
			),
			false
		);

		$this->buffer_output->add_tag(
			'woocommerce_after_shop_loop',
			10,
			array(
				self::FB_VIEW_CATEGORY => $this->get_cookie_types(),
			),
			false
		);

		$this->buffer_output->add_tag(
			'pre_get_posts',
			10,
			array(
				self::FB_SEARCH => $this->get_cookie_types(),
			),
			false
		);

		$this->buffer_output->add_tag(
			'woocommerce_after_cart',
			10,
			array(
				self::FB_ADD_TO_CART => $this->get_cookie_types(),
			),
			false
		);

		$this->buffer_output->add_tag(
			'woocommerce_add_to_cart',
			2,
			array(
				self::FB_ADD_TO_CART => $this->get_cookie_types(),
			),
			false
		);

		$this->buffer_output->add_tag(
			'wc_ajax_fb_inject_add_to_cart_event',
			2,
			array(
				self::FB_ADD_TO_CART => $this->get_cookie_types(),
			),
			false
		);

		$this->buffer_output->add_tag(
			'woocommerce_after_checkout_form',
			10,
			array(
				self::FB_INIT_CHECKOUT => $this->get_cookie_types(),
			),
			false
		);

		$this->buffer_output->add_tag(
			'woocommerce_thankyou',
			2,
			array(
				self::FB_PURCHASE => $this->get_cookie_types(),
			),
			false
		);

		$this->buffer_output->add_tag(
			'woocommerce_payment_complete',
			2,
			array(
				self::FB_PURCHASE => $this->get_cookie_types(),
			),
			false
		);

		$this->buffer_output->add_tag(
			'wp_head',
			10,
			array(
				self::FB_TRACK => $this->get_cookie_types(),
			),
			false
		);

		/**
		 * inject base pixel
		 */
		// We always need to remove this untill consent is given - because we can force no execution before consent it given
		cookiebot_addons_remove_class_action( 'wp_footer', 'WC_Facebookcommerce_EventsTracker', 'inject_base_pixel_noscript' );
	}

	/**
	 * Return attributes for script tags
	 */
	public function cookiebot_addon_facebook_for_woocommerce_script_attributes() {
		$attr                       = array();
		$attr['type']               = 'text/plain';
		$attr['data-cookieconsent'] = implode( ',', $this->get_cookie_types() );
		return $attr;
	}
}
