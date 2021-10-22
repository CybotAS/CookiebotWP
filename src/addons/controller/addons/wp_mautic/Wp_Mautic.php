<?php

namespace cybot\cookiebot\addons\controller\addons\wp_mautic;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;

/**
 * Class Wp_Mautic
 * @package cybot\cookiebot\addons\controller\addons\wp_mautic
 */
class Wp_Mautic extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME                  = 'Mautic';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable tracking.';
	const OPTION_NAME                 = 'mautic';
	const PLUGIN_FILE_PATH            = 'wp-mautic/wpmautic.php';
	const DEFAULT_COOKIE_TYPES        = array( 'statistics' );
	const ENABLE_ADDON_BY_DEFAULT     = false;

	/**
	 * Disable scripts if state not accepted
	 *
	 * @since 1.5.0
	 */
	public function load_addon_configuration() {
			$this->buffer_output->add_tag(
				'wp_head',
				10,
				array(
					'MauticTrackingObject' => $this->get_cookie_types(),
				),
				false
			);
			$this->buffer_output->add_tag(
				'wp_footer',
				10,
				array(
					'MauticTrackingObject' => $this->get_cookie_types(),
				),
				false
			);

		//Remove noscript tracking
		if ( has_action( 'wp_footer', 'wpmautic_inject_noscript' ) ) {
			remove_action( 'wp_footer', 'wpmautic_inject_noscript' );
		}
	}

	/**
	 * @return string
	 */
	public static function get_svn_url() {
		return 'https://plugins.svn.wordpress.org/wp-mautic/trunk/wpmautic.php';
	}
}
