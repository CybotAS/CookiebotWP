<?php

namespace cybot\cookiebot\settings\pages;

use cybot\cookiebot\Cookiebot_WP;
use function cybot\cookiebot\addons\lib\asset_url;
use function cybot\cookiebot\addons\lib\include_view;

class Support_Page implements Settings_Page_Interface {

	public function menu() {
		add_submenu_page(
			'cookiebot',
			__( 'Cookiebot Support', 'cookiebot' ),
			__( 'Support', 'cookiebot' ),
			'manage_options',
			'cookiebot_support',
			array( $this, 'display' ),
			20
		);
	}

	public function display() {
		include_view( 'admin/settings/support-page.php', array() );

		wp_enqueue_style(
			'cookiebot-gtm-page',
			asset_url( 'css/gtm_page.css' ),
			null,
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
		);
	}
}