<?php

namespace cybot\cookiebot\addons\controller\addons\wd_google_analytics;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;

class Wd_Google_Analytics extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME                  = 'WD google analytics';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable tracking.';
	const OPTION_NAME                 = 'wd_google_analytics';
	const PLUGIN_FILE_PATH            = 'wd-google-analytics/google-analytics-wd.php';
	const DEFAULT_COOKIE_TYPES        = array( 'statistics' );
	const ENABLE_ADDON_BY_DEFAULT     = false;

	/**
	 * Disable scripts if state not accepted
	 *
	 * @since 1.3.0
	 */
	public function load_addon_configuration() {
		$this->buffer_output->add_tag(
			'wp_head',
			99,
			array(
				'GoogleAnalyticsObject' => $this->get_cookie_types(),
			),
			false
		);
	}

	/**
	 * @param string $path
	 *
	 * @return string
	 */
	public static function get_svn_url( $path = 'google-analytics-wd.php' ) {
		return 'http://plugins.svn.wordpress.org/wd-google-analytics/trunk/' . $path;
	}
}
