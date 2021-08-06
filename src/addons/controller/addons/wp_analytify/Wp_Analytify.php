<?php

namespace cybot\cookiebot\addons\controller\addons\wp_analytify;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Addon;

/**
 * Class Wp_Analytify
 * @package cybot\cookiebot\addons\controller\addons\wp_analytify
 */
class Wp_Analytify extends Base_Cookiebot_Addon {

	const ADDON_NAME                  = 'Analytify';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable tracking.';
	const OPTION_NAME                 = 'analytify';
	const PLUGIN_FILE_PATH            = 'wp-analytify/wp-analytify.php';
	const DEFAULT_COOKIE_TYPES        = array( 'statistics' );
	const ENABLE_ADDON_BY_DEFAULT     = false;

	/**
	 * Disable scripts if state not accepted
	 *
	 * @since 1.5.0
	 */
	public function load_addon_configuration() {
        $this->buffer_output->add_tag( 'wp_head', 10, array(
            'GoogleAnalyticsObject'     => $this->get_cookie_types()
        ), false );
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
	 * @return boolean
	 *
	 * @since 1.8.0
	 */
	public function get_svn_url() {
		return 'http://plugins.svn.wordpress.org/wp-analytify/trunk/wp-analytify.php';
	}
}
