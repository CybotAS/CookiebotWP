<?php

namespace cybot\cookiebot\settings\pages;

use cybot\cookiebot\lib\Cookiebot_WP;
use InvalidArgumentException;
use function cybot\cookiebot\lib\asset_url;
use function cybot\cookiebot\lib\include_view;

class Dashboard_Page implements Settings_Page_Interface {


	const ICON = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGcgY2xpcC1wYXRoPSJ1cmwoI2NsaXAwXzY0ODFfMzE4MTUpIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik01Ljg2MTYgNS44MDUyVjE5LjgxMTRDNS44NjE2IDI3LjUzNjMgMTIuMjAzOSAzMy44MTc2IDIwLjAwMzkgMzMuODE3NkMyNy44MDM4IDMzLjgxNzYgMzQuMTQ2MiAyNy41MzYzIDM0LjE0NjIgMTkuODExNFY1LjgwNTJINS44NjE2Wk0yMCAzOS42MjI4QzguOTc2MzggMzkuNjIwNyAwIDMwLjczNzEgMCAxOS44MTE0VjBINDBWMTkuODExNEM0MCAzMC43Mjk0IDMxLjAzMTQgMzkuNjIwNyAyMCAzOS42MjI4Wk0yMi42ODk0IDI2Ljk0ODZMMjIuNjg4OCAyNi45NDk5SDE1LjkyTDE1LjkzMTIgMjYuOTI2Nkw5Ljk4OTIxIDE2LjU4MjFIMTYuNzY1N0wxOS4wMTA2IDIwLjQ5MDJMMjMuNzEyMiAxMC42NjMxSDMwLjQ4ODhMMjIuNzAzNSAyNi45MTkyTDIyLjcyMDQgMjYuOTQ4NkgyMi42ODk0WiIgZmlsbD0iYmxhY2siLz4KPC9nPgo8ZGVmcz4KPGNsaXBQYXRoIGlkPSJjbGlwMF82NDgxXzMxODE1Ij4KPHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSJ3aGl0ZSIvPgo8L2NsaXBQYXRoPgo8L2RlZnM+Cjwvc3ZnPgo=';

	const ADMIN_SLUG = 'cookiebot';

	public function menu() {
		add_menu_page(
			'Cookiebot',
			__( 'Cookiebot', 'cookiebot' ),
			'manage_options',
			self::ADMIN_SLUG,
			array(
				$this,
				'display',
			),
			self::ICON
		);

		add_submenu_page(
			'cookiebot',
			__( 'Cookiebot Dashboard', 'cookiebot' ),
			__( 'Dashboard', 'cookiebot' ),
			'manage_options',
			self::ADMIN_SLUG,
			array(
				$this,
				'display',
			),
			1
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function display() {
		$args = array(
			'cbid'        => Cookiebot_WP::get_cbid(),
			'cb_wp'       => asset_url( 'img/cb-wp.png' ),
			'europe_icon' => asset_url( 'img/europe.png' ),
			'usa_icon'    => asset_url( 'img/usa.png' ),
			'check_icon'  => asset_url( 'img/icons/check.svg' ),
			'link_icon'   => asset_url( 'img/icons/link.svg' ),
		);

		$style_sheets = array(
			array( 'cookiebot-dashboard-css', 'css/backend/dashboard_page.css' ),
		);

		foreach ( $style_sheets as $style ) {
			wp_enqueue_style(
				$style[0],
				asset_url( $style[1] ),
				null,
				Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
			);
		}

		include_view( 'admin/settings/dashboard-page.php', $args );
	}
}
