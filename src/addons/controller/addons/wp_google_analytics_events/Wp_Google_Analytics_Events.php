<?php

namespace cybot\cookiebot\addons\controller\addons\wp_google_analytics_events;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;
use cybot\cookiebot\lib\Addon_With_Extra_Information_Interface;
use function cybot\cookiebot\lib\cookiebot_addons_remove_class_action;

class Wp_Google_Analytics_Events extends Base_Cookiebot_Plugin_Addon implements Addon_With_Extra_Information_Interface {

	const ADDON_NAME                  = 'WP Google Analytics Events';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable tracking.';
	const OPTION_NAME                 = 'wp_google_analytics_events';
	const PLUGIN_FILE_PATH            = 'wp-google-analytics-events/ga-scroll-event.php';
	const DEFAULT_COOKIE_TYPES        = array( 'statistics' );
	const ENABLE_ADDON_BY_DEFAULT     = false;

	/**
	 * Disable scripts if state not accepted
	 *
	 * @since 1.3.0
	 */
	public function load_addon_configuration() {
		$this->script_loader_tag->add_tag( 'ga_events_frontend_bundle', $this->get_cookie_types() );
		$this->script_loader_tag->add_tag( 'ga_events_main_script', $this->get_cookie_types() );
		cookiebot_addons_remove_class_action( 'wp_head', 'GAESnippets', 'add_snippet_to_header', 0 );
	}

	/**
	 * Returns default cookie types
	 * @return array
	 *
	 * @since 1.5.0
	 */
	public function get_default_cookie_types() {
		return array( 'statistics' );
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
			__(
				'The plugin allows you to fire events whenever someone interacts or views elements on your website.',
				'cookiebot'
			),
		);
	}

	/**
	 * @return string
	 */
	public static function get_svn_url() {
		return 'http://plugins.svn.wordpress.org/wp-google-analytics-events/trunk/ga-scroll-event.php';
	}
}
