<?php

namespace cybot\cookiebot\addons\controller\addons\enhanced_ecommerce_for_woocommerce_store;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;

class Enhanced_Ecommerce_For_WooCommerce_Store extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME                  = 'Enhanced Ecommerce Google Analytics Plugin for WooCommerce';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable Social Share buttons.';
	const OPTION_NAME                 = 'enhanced_ecommerce_for_woocommerce_store';
	const PLUGIN_FILE_PATH            = 'enhanced-e-commerce-for-woocommerce-store/enhanced-ecommerce-google-analytics.php';
	const DEFAULT_COOKIE_TYPES        = array( 'statistics' );
	const SVN_URL_BASE_PATH           = 'https://plugins.svn.wordpress.org/enhanced-e-commerce-for-woocommerce-store/trunk/';
	const SVN_URL_DEFAULT_SUB_PATH    = 'enhanced-ecommerce-google-analytics.php';

	public function load_addon_configuration() {
		$this->buffer_output->add_tag(
			'wp_footer',
			25,
			array(
				'gtag' => $this->get_cookie_types(),
			),
			false
		);

		$this->buffer_output->add_tag(
			'wp_head',
			10,
			array(
				'gtag'       => $this->get_cookie_types(),
				'gaProperty' => $this->get_cookie_types(),
			),
			false
		);
	}

	/**
	 * @return array
	 */
	public function get_extra_information() {
		return array(
			__( 'Blocks enhanced e-commerce for WooCommerce store', 'cookiebot' ),
		);
	}
}
