<?php

namespace cybot\cookiebot\addons\controller\addons\google_analytics_plus;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;

class Google_Analytics_Plus extends Base_Cookiebot_Plugin_Addon {
	const ADDON_NAME           = 'Google Analytics +';
	const OPTION_NAME          = 'google_analytics_plus';
	const PLUGIN_FILE_PATH     = 'google-analytics-async/google-analytics-async.php';
	const DEFAULT_COOKIE_TYPES = array( 'statistics' );
	// @todo watch this video? is that correct?
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to watch this video.';

	public function load_addon_configuration() {
		// Disable Analytify if cookie consent not allowed
		$this->buffer_output->add_tag(
			'wp_head',
			10,
			array(
				'GoogleAnalyticsObject' => $this->get_cookie_types(),
			),
			false
		);
	}

	/**
	 * @return array
	 */
	public function get_extra_information() {
		return array(
			__( 'Google Analytics is a simple, easy-to-use tool that helps website owners measure how users interact with website content', 'cookiebot' ),
		);
	}
}
