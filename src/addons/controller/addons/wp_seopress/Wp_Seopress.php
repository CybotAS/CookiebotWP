<?php

namespace cybot\cookiebot\addons\controller\addons\wp_seopress;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;

class Wp_Seopress extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME               = 'WP SEOPress';
	const OPTION_NAME              = 'wp_seopress';
	const PLUGIN_FILE_PATH         = 'wp-seopress/seopress.php';
	const DEFAULT_COOKIE_TYPES     = array( 'statistics', 'marketing' );
	const ENABLE_ADDON_BY_DEFAULT  = false;
	const SVN_URL_BASE_PATH        = 'https://plugins.svn.wordpress.org/wp-seopress/trunk/';
	const SVN_URL_DEFAULT_SUB_PATH = 'seopress.php';

	/**
	 * Loads addon configuration
	 *
	 * @since 1.3.0
	 */
	public function load_addon_configuration() {
		$this->buffer_output->add_tag(
			'wp_head',
			999,
			array(
				'gtag'                                 => $this->get_cookie_types(),
				'google-analytics'                     => $this->get_cookie_types(),
				'_gaq'                                 => $this->get_cookie_types(),
				'www.googletagmanager.com/gtag/js?id=' => $this->get_cookie_types(),
			),
			false
		);
	}

	/**
	 * @return array
	 */
	public function get_extra_information() {
		return array(
			__(
				'Blocks cookies from WP SEOPress\' Google Analytics integration.',
				'cookiebot'
			),
		);
	}
}
