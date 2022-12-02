<?php

namespace cybot\cookiebot\settings\templates;

use cybot\cookiebot\lib\Cookiebot_WP;
use InvalidArgumentException;
use function cybot\cookiebot\lib\asset_url;
use function cybot\cookiebot\lib\include_view;

class Header {

	/**
	 * @throws InvalidArgumentException
	 */
	public function display() {
		$args = array(
			'cookiebot_logo' => CYBOT_COOKIEBOT_PLUGIN_URL . 'logo.svg',
		);

		$style_sheets = array(
			array( 'cookiebot-main-css', 'css/backend/cookiebot_admin_main.css' ),
		);

		foreach ( $style_sheets as $style ) {
			wp_enqueue_style(
				$style[0],
				asset_url( $style[1] ),
				null,
				Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
			);
		}

		include_view( 'admin/templates/header.php', $args );
	}
}
