<?php

namespace cybot\cookiebot\addons\controller\addons\google_analytics;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;

class Google_Analytics extends Base_Cookiebot_Plugin_Addon {
	const ADDON_NAME                  = 'Google Analytics'; // @TODO is this even the correct name for this plugin?
	const OPTION_NAME                 = 'google_analytics';
	const PLUGIN_FILE_PATH            = 'googleanalytics/googleanalytics.php';
	const DEFAULT_COOKIE_TYPES        = array( 'statistics' );
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to track for google analytics.';
	const SVN_URL_BASE_PATH           = 'https://plugins.svn.wordpress.org/googleanalytics/trunk/';
	const SVN_URL_DEFAULT_SUB_PATH    = 'googleanalytics.php';

	public function load_addon_configuration() {
		$this->buffer_output->add_tag(
			'wp_footer',
			10,
			array(
				'googleanalytics_get_script' => $this->get_cookie_types(),
			),
			false
		);

		if ( has_action( 'wp_enqueue_scripts', 'Ga_Frontend::platform_sharethis' ) ) {
			$this->script_loader_tag->add_tag( GA_NAME . '-platform-sharethis', $this->get_cookie_types() );
		}
	}

	/**
	 * @return array
	 */
	public function get_extra_information() {
		return array(
			__( 'Google Analytics is used to track how visitor interact with website content.', 'cookiebot' ),
		);
	}
}
