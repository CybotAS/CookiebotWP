<?php

namespace cybot\cookiebot\addons\controller\addons\optinmonster;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Addon;

class Optinmonster extends Base_Cookiebot_Addon {

	const ADDON_NAME                  = 'Optinmonster';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable tracking.';
	const OPTION_NAME                 = 'optinmonster';
	const PLUGIN_FILE_PATH            = 'optinmonster/optin-monster-wp-api.php';
	const DEFAULT_COOKIE_TYPES        = array( 'marketing', 'statistics' );
	const ENABLE_ADDON_BY_DEFAULT     = false;

	/**
	 * Check for optinmonster action hooks
	 *
	 * @since 1.3.0
	 */
	public function load_addon_configuration() {
		$this->script_loader_tag->add_tag( 'optinmonster-api-script', $this->get_cookie_types() );
	}

	/**
	 * Adds extra information under the label
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_extra_information() {
		return '<p>' . esc_html__( 'OptinMonster API plugin to connect your WordPress site to your OptinMonster account.', 'cookiebot-addons' ) . '</p>';
	}

	/**
	 * Returns the url of WordPress SVN repository or another link where we can verify the plugin file.
	 *
	 * @return boolean
	 *
	 * @since 1.8.0
	 */
	public function get_svn_url() {
		return 'https://plugins.svn.wordpress.org/optinmonster/trunk/optin-monster-wp-api.php';
	}
}
