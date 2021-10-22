<?php

namespace cybot\cookiebot\addons\controller\addons\addthis;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Plugin_Addon;
use cybot\cookiebot\lib\Open_Source_Addon_Interface;

class Addthis extends Base_Cookiebot_Plugin_Addon implements Open_Source_Addon_Interface {

	const ADDON_NAME                  = 'WordPress Share Buttons Plugin – AddThis';
	const OPTION_NAME                 = 'addthis';
	const DEFAULT_COOKIE_TYPES        = array( 'marketing', 'statistics' );
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to view this element.';
	const PLUGIN_FILE_PATH            = 'addthis/addthis_social_widget.php';
	const ENABLE_ADDON_BY_DEFAULT     = false;

	/**
	 * Manipulate the scripts if they are loaded.
	 *
	 * @since 1.3.0
	 */
	public function load_addon_configuration() {

		// block the script until the consent is given
		$this->script_loader_tag->add_tag( 'addthis_widget', $this->get_cookie_types() );

		$this->buffer_output->add_tag(
			'wp_footer',
			19,
			array(
				'addthis_product' => $this->get_cookie_types(),
			),
			false
		);
		$this->buffer_output->add_tag(
			'wp_head',
			19,
			array(
				'addthis_product ' => $this->get_cookie_types(),
			),
			false
		);
	}

	/**
	 * @param string $path
	 *
	 * @return string
	 */
	public static function get_svn_url( $path = 'addthis_social_widget.php' ) {
		return 'https://plugins.svn.wordpress.org/addthis/trunk/' . $path;
	}
}
