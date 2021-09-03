<?php

namespace cybot\cookiebot\addons\controller\addons\enfold;

use cybot\cookiebot\addons\controller\addons\Base_Cookiebot_Theme_Addon;
use cybot\cookiebot\lib\Addon_With_Extra_Information_Interface;

class Enfold extends Base_Cookiebot_Theme_Addon implements Addon_With_Extra_Information_Interface {

	const ADDON_NAME                  = 'Enfold';
	const OPTION_NAME                 = 'enfold';
	const DEFAULT_PLACEHOLDER_CONTENT = 'Please accept [renew_consent]%cookie_types[/renew_consent] cookies to enable Google Services.';
	const DEFAULT_COOKIE_TYPES        = array( 'marketing', 'statistics' );

	public function load_addon_configuration() {
		$this->buffer_output->add_tag(
			'wp_footer',
			10000,
			array(
				'google_analytics_script' => $this->get_cookie_types(),
			)
		);
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
			__( 'Blocks cookies created by Enfold theme\'s Google Services.', 'cookiebot-addons' ),
		);
	}
}
