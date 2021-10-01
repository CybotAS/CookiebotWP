<?php

namespace cybot\cookiebot\addons\controller\addons\pixel_caffeine;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;

/**
 * Class Pixel_Caffeine
 * @package cybot\cookiebot\addons\controller\addons\pixel_caffeine
 */
class Pixel_Caffeine extends Base_Cookiebot_Plugin_Addon {

	const ADDON_NAME                  = 'Pixel Caffeine';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable tracking.';
	const OPTION_NAME                 = 'pixel_caffeine';
	const PLUGIN_FILE_PATH            = 'pixel-caffeine/pixel-caffeine.php';
	const DEFAULT_COOKIE_TYPES        = array( 'statistics', 'marketing' );
	const ENABLE_ADDON_BY_DEFAULT     = false;

	/**
	 * Disable scripts if state not accepted
	 *
	 * @since 1.4.0
	 */
	public function load_addon_configuration() {
		$this->script_loader_tag->add_tag( 'aepc-pixel-events', $this->get_cookie_types() );

		$this->buffer_output->add_tag(
			'wp_head',
			99,
			array(
				'aepc_pixel' => $this->get_cookie_types(),
			),
			false
		);

		$this->buffer_output->add_tag(
			'wp_footer',
			1,
			array(
				'aepc_pixel' => $this->get_cookie_types(),
			),
			false
		);
	}

	/**
	 * Default placeholder content
	 *
	 * @return string
	 *
	 * @since 1.8.0
	 */
	public function get_default_placeholder() {
		return 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable Facebook pixel.';
	}

	/**
	 * Returns the url of WordPress SVN repository or another link where we can verify the plugin file.
	 *
	 * @return boolean
	 *
	 * @since 1.8.0
	 */
	public static function get_svn_url( $path = 'pixel-caffeine.php' ) {
		return 'http://plugins.svn.wordpress.org/pixel-caffeine/trunk/' . $path;
	}
}