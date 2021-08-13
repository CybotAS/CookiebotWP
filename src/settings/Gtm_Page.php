<?php

namespace cybot\cookiebot\settings;

use cybot\cookiebot\Cookiebot_WP;
use function cybot\cookiebot\addons\lib\asset_url;
use function cybot\cookiebot\addons\lib\include_view;

class Gtm_Page implements Page_Interface {

	public function display() {
		include_view( 'admin/settings/gtm-page.php', array() );

		wp_enqueue_style(
			'cookiebot-gtm-page',
			asset_url( 'css/gtm_page.css' ),
			null,
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
		);
	}
}