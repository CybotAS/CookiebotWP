<?php

namespace cybot\cookiebot\addons\controller\addons\wp_piwik;

require_once ABSPATH . 'wp-admin/includes/plugin.php';

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;

/**
 * Class Wp_Piwik
 * @package cybot\cookiebot\addons\controller\addons\wp_piwik
 */
class Wp_Piwik extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME                  = 'WP Piwik';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to allow Matomo statistics.';
	const OPTION_NAME                 = 'wp_piwik';
	const PLUGIN_FILE_PATH            = 'wp-piwik/wp-piwik.php';
	const DEFAULT_COOKIE_TYPES        = array( 'statistics' );
	const ENABLE_ADDON_BY_DEFAULT     = false;

	/**
	 * Manipulate the scripts if they are loaded.
	 *
	 * @since 1.3.0
	 */
	public function load_addon_configuration() {
		if ( ! $this->cookie_consent->are_cookie_states_accepted( $this->get_cookie_types() ) ) {
			// wp_footer
			$this->buffer_output->add_tag(
				'wp_footer',
				10,
				array(
					'matomo' => $this->get_cookie_types(),
				),
				false
			);

			// wp_head
			$this->buffer_output->add_tag(
				'wp_head',
				10,
				array(
					'matomo' => $this->get_cookie_types(),
				),
				false
			);
		}
	}

	/**
	 * Adds extra information under the label
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_extra_information() {
		return '';
	}

	/**
	 * Returns the url of WordPress SVN repository or another link where we can verify the plugin file.
	 *
	 * @return boolean
	 *
	 * @since 1.8.0
	 */
	public function get_svn_url() {
		return 'http://plugins.svn.wordpress.org/wp-piwik/trunk/wp-piwik.php';
	}
}
