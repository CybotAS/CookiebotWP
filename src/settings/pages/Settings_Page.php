<?php

namespace cybot\cookiebot\settings\pages;

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

	/**
	 * @throws InvalidArgumentException
	 */
	public function display() {
		$consent_api_helper = new Consent_API_Helper();

		$args = array(
			'cbid'                     => Cookiebot_WP::get_cbid(),
			'is_ms'                    => false,
			'cookiebot_gdpr_url'       => 'https://www.cookiebot.com/' . Cookiebot_WP::get_manager_language() . '/gdpr',
			'cookiebot_logo'           => CYBOT_COOKIEBOT_PLUGIN_URL . 'cookiebot-logo.png',
			'supported_languages'      => Supported_Languages::get(),
			'current_lang'             => cookiebot_get_language_from_setting( true ),
			'is_wp_consent_api_active' => $consent_api_helper->is_wp_consent_api_active(),
			'm_default'                => $consent_api_helper->get_default_wp_consent_api_mapping(),
			'm'                        => $consent_api_helper->get_wp_consent_api_mapping(),
			'cookie_blocking_mode'     => Cookiebot_WP::get_cookie_blocking_mode(),
			'network_auto'             => Cookiebot_WP::check_network_auto_blocking_mode(),
			'add_language_gif_url'     => asset_url( 'img/guide_add_language.gif' ),
		);

		/* Check if multisite */
		if ( is_multisite() ) {
			// Receive settings from multisite - this might change the way we render the form
			$args['network_cbid']              = get_site_option( 'cookiebot-cbid', '' );
			$args['network_scrip_tag_uc_attr'] = get_site_option( 'cookiebot-script-tag-uc-attribute', 'custom' );
			$args['network_scrip_tag_cd_attr'] = get_site_option( 'cookiebot-script-tag-cd-attribute', 'custom' );
			$args['is_ms']                     = true;
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
			array( 'cookieBlockingMode' => $args['cookie_blocking_mode'] )
		);

		include_view( 'admin/settings/settings-page.php', $args );
	}
}
