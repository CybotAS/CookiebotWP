<?php

namespace cybot\cookiebot\addons\controller\addons\google_analytics;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;
use cybot\cookiebot\lib\Addon_With_Extra_Information_Interface;
use cybot\cookiebot\lib\Open_Source_Addon_Interface;

/**
 * Class Google_Analytics
 * @package cybot\cookiebot\addons\controller\addons\google_analytics
 */
class Google_Analytics extends Base_Cookiebot_Plugin_Addon implements Open_Source_Addon_Interface, Addon_With_Extra_Information_Interface {
	const ADDON_NAME                  = 'Google Analytics'; // @TODO is this even the correct name for this plugin?
	const OPTION_NAME                 = 'google_analytics';
	const PLUGIN_FILE_PATH            = 'googleanalytics/googleanalytics.php';
	const DEFAULT_COOKIE_TYPES        = array( 'statistics' );
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to track for google analytics.';

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
	 * Adds extra information under the label
	 *
	 * @return string[]
	 *
	 * @since 1.8.0
	 */
	public function get_extra_information() {
		return array(
			__( 'Google Analytics is used to track how visitor interact with website content.', 'cookiebot-addons' ),
		);
	}

	/**
	 * Returns the url of WordPress SVN repository or another link where we can verify the plugin file.
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_svn_url() {
		return 'http://plugins.svn.wordpress.org/googleanalytics/trunk/googleanalytics.php';
	}
}
