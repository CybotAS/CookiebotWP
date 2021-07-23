<?php

namespace cookiebot_addons\controller\addons\pixel_caffeine;

use cookiebot_addons\controller\addons\Base_Cookiebot_Addon;
use cookiebot_addons\controller\addons\Cookiebot_Addons_Interface;
use cookiebot_addons\lib\Cookie_Consent_Interface;
use cookiebot_addons\lib\Settings_Service_Interface;
use cookiebot_addons\lib\script_loader_tag\Script_Loader_Tag_Interface;
use cookiebot_addons\lib\buffer\Buffer_Output_Interface;

class Pixel_Caffeine extends Base_Cookiebot_Addon {

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
		return 'http://plugins.svn.wordpress.org/pixel-caffeine/trunk/pixel-caffeine.php';
	}
}
