<?php

namespace cybot\cookiebot\addons\controller\addons\woocommerce_sourcebuster;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;

class WooCommerce_SourceBuster extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME              = 'WooCommerce Source Buster';
	const DEFAULT_COOKIE_TYPES    = array( 'statistics' );
	const ENABLE_ADDON_BY_DEFAULT = true;
	const OPTION_NAME             = 'woocommerce_sourcebuster';
	const PLUGIN_FILE_PATH        = 'woocommerce/woocommerce.php';

	/**
	 * Manipulate the scripts if they are loaded.
	 *
	 * @since 4.4.0
	 */
	public function load_addon_configuration() {
		$this->script_loader_tag->add_tag( 'sourcebuster-js', $this->get_cookie_types() );
	}

	/**
	 * @return array
	 */
	public function get_extra_information() {
		return array(
			__(
				'Blocks the WooCommerce "Order Attribution Tracking" / "SourceBuster" before cookies accepted.',
				'cookiebot'
			),
		);
	}
}
