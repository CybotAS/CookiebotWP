<?php

namespace cookiebot_addons\controller\addons\woocommerce_google_analytics_pro;

use cookiebot_addons\controller\addons\Base_Cookiebot_Addon;

class Woocommerce_Google_Analytics_Pro extends Base_Cookiebot_Addon {

	const ADDON_NAME                  = 'WooCommerce Google Analytics Pro';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable tracking.';
	const OPTION_NAME                 = 'woocommerce_google_analytics_pro';
	const PLUGIN_FILE_PATH            = 'woocommerce-google-analytics-pro/woocommerce-google-analytics-pro.php';
	const DEFAULT_COOKIE_TYPES        = array( 'statistics' );
	const ENABLE_ADDON_BY_DEFAULT     = false;

	/**
	 * Manipulate the scripts if they are loaded.
	 *
	 * @since 1.3.0
	 */
	public function load_addon_configuration() {
		add_filter( 'wc_google_analytics_pro_script_attributes', array( $this, 'cookiebot_addon_woocommerce_google_analytics_pro_script_attributes' ) );
	}

	/**
	 * Return attributes for script tags
	 */
	public function cookiebot_addon_woocommerce_google_analytics_pro_script_attributes() {
		$attr                       = array();
		$attr['type']               = 'text/plain';
		$attr['data-cookieconsent'] = implode( ',', $this->get_cookie_types() );
		return $attr;
	}

	/**
	 * Adds extra information under the label
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_extra_information() {
		return false;
	}

	/**
	 * Returns the url of WordPress SVN repository or another link where we can verify the plugin file.
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_svn_url() {
		return false; //Commercial plugin - not available
	}
}
