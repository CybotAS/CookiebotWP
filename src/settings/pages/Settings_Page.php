<?php

namespace cybot\cookiebot\settings\pages;

use cybot\cookiebot\lib\Cookiebot_Frame;
use cybot\cookiebot\lib\Cookiebot_WP;
use cybot\cookiebot\lib\Consent_API_Helper;
use cybot\cookiebot\lib\Supported_Languages;
use InvalidArgumentException;
use function cybot\cookiebot\lib\include_view;
use function cybot\cookiebot\lib\asset_url;
use function cybot\cookiebot\lib\cookiebot_get_language_from_setting;

class Settings_Page implements Settings_Page_Interface {


	const ICON = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGcgY2xpcC1wYXRoPSJ1cmwoI2NsaXAwXzY0ODFfMzE4MTUpIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik01Ljg2MTYgNS44MDUyVjE5LjgxMTRDNS44NjE2IDI3LjUzNjMgMTIuMjAzOSAzMy44MTc2IDIwLjAwMzkgMzMuODE3NkMyNy44MDM4IDMzLjgxNzYgMzQuMTQ2MiAyNy41MzYzIDM0LjE0NjIgMTkuODExNFY1LjgwNTJINS44NjE2Wk0yMCAzOS42MjI4QzguOTc2MzggMzkuNjIwNyAwIDMwLjczNzEgMCAxOS44MTE0VjBINDBWMTkuODExNEM0MCAzMC43Mjk0IDMxLjAzMTQgMzkuNjIwNyAyMCAzOS42MjI4Wk0yMi42ODk0IDI2Ljk0ODZMMjIuNjg4OCAyNi45NDk5SDE1LjkyTDE1LjkzMTIgMjYuOTI2Nkw5Ljk4OTIxIDE2LjU4MjFIMTYuNzY1N0wxOS4wMTA2IDIwLjQ5MDJMMjMuNzEyMiAxMC42NjMxSDMwLjQ4ODhMMjIuNzAzNSAyNi45MTkyTDIyLjcyMDQgMjYuOTQ4NkgyMi42ODk0WiIgZmlsbD0iYmxhY2siLz4KPC9nPgo8ZGVmcz4KPGNsaXBQYXRoIGlkPSJjbGlwMF82NDgxXzMxODE1Ij4KPHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSJ3aGl0ZSIvPgo8L2NsaXBQYXRoPgo8L2RlZnM+Cjwvc3ZnPgo=';

	const ADMIN_SLUG = 'cookiebot_settings';

	public function menu() {
		if ( ! Cookiebot_WP::get_user_data() ) {
			add_submenu_page(
				'cookiebot',
				__( 'Cookiebot Settings', 'cookiebot' ),
				__( 'Settings', 'cookiebot' ),
				'manage_options',
				self::ADMIN_SLUG,
				array(
					$this,
					'display',
				),
				2
			);
		}
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function display() {
		$args = array(
			'cbid'                 => Cookiebot_WP::get_cbid(),
			'ruleset_id'           => ! empty( get_option( 'cookiebot-ruleset-id' ) ) ? get_option( 'cookiebot-ruleset-id' ) : 'settings',
			'is_ms'                => false,
			'cookie_blocking_mode' => Cookiebot_WP::get_cookie_blocking_mode(),
		);

		/* Check if multisite */
		if ( is_multisite() ) {
			// Receive settings from multisite - this might change the way we render the form
			$args['network_cbid'] = get_site_option( 'cookiebot-cbid', '' );
			$args['is_ms']        = true;
		}

		wp_enqueue_style(
			'cookiebot-consent-mapping-table',
			asset_url( 'css/backend/consent_mapping_table.css' ),
			null,
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
		);

		wp_enqueue_style(
			'cookiebot-settings-page-css',
			asset_url( 'css/backend/settings-page.css' ),
			null,
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
		);

		wp_enqueue_script(
			'cookiebot-settings-page-js',
			asset_url( 'js/backend/settings-page.js' ),
			null,
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
			true
		);

		wp_localize_script(
			'cookiebot-settings-page-js',
			'cookiebot_settings',
			array( 'cookieBlockingMode' => Cookiebot_WP::get_cookie_blocking_mode() )
		);

		include_view( Cookiebot_Frame::get_view_path() . 'settings-page.php', $args );
	}
}
