<?php

namespace cybot\cookiebot\settings\pages;

use cybot\cookiebot\lib\Cookiebot_WP;
use InvalidArgumentException;
use function cybot\cookiebot\lib\asset_url;
use function cybot\cookiebot\lib\include_view;

class Gtm_Page implements Settings_Page_Interface {

	public function menu() {
		add_submenu_page(
			'cookiebot',
			__( 'Google Tag Manager', 'cookiebot' ),
			__( 'Google Tag Manager', 'cookiebot' ),
			'manage_options',
			'cookiebot_GTM',
			array( $this, 'display' )
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function display() {
		include_view( 'admin/settings/gtm-page.php', array() );

		wp_enqueue_style(
			'cookiebot-gtm-page-css',
			asset_url( 'css/backend/gtm_page.css' ),
			null,
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
		);
	}
}
