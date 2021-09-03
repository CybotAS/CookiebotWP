<?php

namespace cybot\cookiebot\addons\controller\addons\matomo;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;

/**
 * Class Matomo
 * @package cybot\cookiebot\addons\controller\addons\wp_mautic
 */
class Matomo extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME                  = 'Matomo Analytics';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable tracking.';
	const OPTION_NAME                 = 'matomo';
	const PLUGIN_FILE_PATH            = 'matomo/matomo.php';
	const DEFAULT_COOKIE_TYPES        = array( 'statistics' );
	const ENABLE_ADDON_BY_DEFAULT     = false;

	/**
	 * Disable scripts if state not accepted
	 *
	 * @since 1.5.0
	 */
	public function load_addon_configuration() {
		$possible_tags = array(
			'admin_footer',
			'admin_head',
			'wp_footer',
			'wp_head',
		);
		foreach ( $possible_tags as $possible_tag ) {
			$this->buffer_output->add_tag(
				$possible_tag,
				10,
				array(
					'matomo' => $this->get_cookie_types(),
				),
				false
			);
		}
	}

	/**
	 * Returns the url of WordPress SVN repository or another link where we can verify the plugin file.
	 *
	 * @return boolean
	 *
	 * @since 1.8.0
	 */
	public static function get_svn_url() {
		return 'https://plugins.svn.wordpress.org/matomo/trunk/matomo.php';
	}
}
