<?php

namespace cybot\cookiebot\settings\pages;

use cybot\cookiebot\lib\Cookiebot_WP;
use InvalidArgumentException;
use function cybot\cookiebot\lib\asset_url;
use function cybot\cookiebot\lib\include_view;

class Support_Page implements Settings_Page_Interface {

	const ADMIN_SLUG = 'cookiebot_support';

	public function menu() {
		add_submenu_page(
			'cookiebot',
			__( 'Cookiebot Support', 'cookiebot' ),
			__( 'Support', 'cookiebot' ),
			'manage_options',
			self::ADMIN_SLUG,
			array( $this, 'display' ),
			20
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function display() {
		$args = array(
			'manager_language' => Cookiebot_WP::get_manager_language(),
		);

		$scripts = array(
			array( 'cookiebot-support-page-js', 'js/backend/support-page.js' ),
		);

		foreach ( $scripts as $script ) {
			wp_enqueue_script(
				$script[0],
				asset_url( $script[1] ),
				null,
				Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
				true
			);
		}

		$style_sheets = array(
			array( 'cookiebot-support-css', 'css/backend/support_page.css' ),
		);

		foreach ( $style_sheets as $style ) {
			wp_enqueue_style(
				$style[0],
				asset_url( $style[1] ),
				null,
				Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
			);
		}

		include_view( 'admin/settings/support-page.php', $args );
	}
}
