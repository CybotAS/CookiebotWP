<?php

namespace cybot\cookiebot\addons\controller\addons\woocommerce;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;

class WooCommerce extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME              = 'WooCommerce';
	const OPTION_NAME             = 'woocommerce';
	const DEFAULT_COOKIE_TYPES    = array( 'statistics' );
	const PLUGIN_FILE_PATH        = 'woocommerce/woocommerce.php';
	const ENABLE_ADDON_BY_DEFAULT = true;

	/**
	 * Block WooCommerce Order Attribution Tracking scripts until consent is given.
	 *
	 * @since 4.6.0
	 */
	public function load_addon_configuration() {
		$this->script_loader_tag->add_tag( 'sourcebuster-js', $this->get_cookie_types() );
		$this->script_loader_tag->add_tag( 'wc-order-attribution', $this->get_cookie_types() );
	}

	/**
	 * @return array
	 */
	public function get_extra_information() {
		return array(
			__( 'Blocks WooCommerce Order Attribution Tracking scripts (SourceBuster) before consent is accepted.', 'cookiebot' ),
		);
	}
}
