<?php

namespace cybot\cookiebot\settings;

use cybot\cookiebot\lib\Cookiebot_Frame;
use cybot\cookiebot\lib\Cookiebot_WP;
use cybot\cookiebot\settings\pages\Support_Page;
use InvalidArgumentException;
use function cybot\cookiebot\lib\asset_url;
use function cybot\cookiebot\lib\include_view;

class Network_Menu_Settings {


	const ICON = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGcgY2xpcC1wYXRoPSJ1cmwoI2NsaXAwXzY0ODFfMzE4MTUpIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik01Ljg2MTYgNS44MDUyVjE5LjgxMTRDNS44NjE2IDI3LjUzNjMgMTIuMjAzOSAzMy44MTc2IDIwLjAwMzkgMzMuODE3NkMyNy44MDM4IDMzLjgxNzYgMzQuMTQ2MiAyNy41MzYzIDM0LjE0NjIgMTkuODExNFY1LjgwNTJINS44NjE2Wk0yMCAzOS42MjI4QzguOTc2MzggMzkuNjIwNyAwIDMwLjczNzEgMCAxOS44MTE0VjBINDBWMTkuODExNEM0MCAzMC43Mjk0IDMxLjAzMTQgMzkuNjIwNyAyMCAzOS42MjI4Wk0yMi42ODk0IDI2Ljk0ODZMMjIuNjg4OCAyNi45NDk5SDE1LjkyTDE1LjkzMTIgMjYuOTI2Nkw5Ljk4OTIxIDE2LjU4MjFIMTYuNzY1N0wxOS4wMTA2IDIwLjQ5MDJMMjMuNzEyMiAxMC42NjMxSDMwLjQ4ODhMMjIuNzAzNSAyNi45MTkyTDIyLjcyMDQgMjYuOTQ4NkgyMi42ODk0WiIgZmlsbD0iYmxhY2siLz4KPC9nPgo8ZGVmcz4KPGNsaXBQYXRoIGlkPSJjbGlwMF82NDgxXzMxODE1Ij4KPHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSJ3aGl0ZSIvPgo8L2NsaXBQYXRoPgo8L2RlZnM+Cjwvc3ZnPgo=';

	public function add_menu() {
		add_action( 'network_admin_menu', array( $this, 'add_network_menu' ), 1 );
		add_action(
			'network_admin_edit_cookiebot_network_settings',
			array(
				$this,
				'network_settings_save',
			)
		);
	}

	/**
	 * Cookiebot_WP Add menu for network sites
	 *
	 * @version 2.2.0
	 * @since       2.2.0
	 */
	public function add_network_menu() {
		add_menu_page(
			'Cookiebot',
			__( 'Cookiebot', 'cookiebot' ),
			'manage_network_options',
			'cookiebot_network',
			array( $this, 'display' ),
			static::ICON
		);
		add_submenu_page(
			'cookiebot_network',
			__( 'Cookiebot Settings', 'cookiebot' ),
			__( 'Settings', 'cookiebot' ),
			'network_settings_page',
			'cookiebot_network',
			array( $this, 'display' )
		);
		add_submenu_page(
			'cookiebot_network',
			__( 'Cookiebot Support', 'cookiebot' ),
			__( 'Support', 'cookiebot' ),
			'network_settings_page',
			'cookiebot_support',
			array( new Support_Page(), 'display' )
		);
	}

	/**
	 * Cookiebot_WP Cookiebot save network settings
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 */
	public function network_settings_save() {
		check_admin_referer( 'cookiebot-network-settings' );

		update_site_option(
			'cookiebot-cbid',
			! empty( $_POST['cookiebot-cbid'] ) ? $_POST['cookiebot-cbid'] : ''
		);
		update_site_option(
			'cookiebot-ruleset-id',
			! empty( $_POST['cookiebot-ruleset-id'] ) ? $_POST['cookiebot-ruleset-id'] : ''
		);
		update_site_option(
			'cookiebot-script-tag-uc-attribute',
			! empty( $_POST['cookiebot-script-tag-uc-attribute'] ) ? $_POST['cookiebot-script-tag-uc-attribute'] : ''
		);
		update_site_option(
			'cookiebot-script-tag-cd-attribute',
			! empty( $_POST['cookiebot-script-tag-cd-attribute'] ) ? $_POST['cookiebot-script-tag-cd-attribute'] : ''
		);
		update_site_option(
			'cookiebot-autoupdate',
			! empty( $_POST['cookiebot-autoupdate'] ) ? $_POST['cookiebot-autoupdate'] : ''
		);
		update_site_option(
			'cookiebot-nooutput',
			! empty( $_POST['cookiebot-nooutput'] ) ? $_POST['cookiebot-nooutput'] : ''
		);
		update_site_option(
			'cookiebot-nooutput-admin',
			! empty( $_POST['cookiebot-nooutput-admin'] ) ? $_POST['cookiebot-nooutput-admin'] : ''
		);
		update_site_option(
			'cookiebot-cookie-blocking-mode',
			! empty( $_POST['cookiebot-cookie-blocking-mode'] ) ? $_POST['cookiebot-cookie-blocking-mode'] : ''
		);

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'    => 'cookiebot_network',
					'updated' => true,
				),
				network_admin_url( 'admin.php' )
			)
		);
		exit;
	}

	/**
	 * Cookiebot_WP Cookiebot network setting page
	 *
	 * @throws InvalidArgumentException
	 * @since   2.2.0
	 * @version 2.2.0
	 */
	public function display() {
		$cbm = get_site_option( 'cookiebot-cookie-blocking-mode', 'manual' );

		wp_enqueue_script(
			'cookiebot-network-settings-page-js',
			asset_url( 'js/backend/network-settings-page.js' ),
			null,
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
			true
		);

		wp_add_inline_script(
			'cookiebot-network-settings-page-js',
			'const cookiebotNetworkSettings = ' . wp_json_encode( array( 'cbm' => esc_attr( $cbm ) ) ),
			'before'
		);

		wp_enqueue_style(
			'cookiebot-settings-page-css',
			asset_url( 'css/backend/settings-page.css' ),
			null,
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
		);

		include_view(
			Cookiebot_Frame::get_view_path( true ) . 'network-settings-page.php',
			array(
				'cookiebot_gdpr_url' => 'https://www.cookiebot.com/' . Cookiebot_WP::get_manager_language() . '/gdpr/?utm_source=wordpress&utm_medium=referral&utm_campaign=banner',
				'logo'               => CYBOT_COOKIEBOT_PLUGIN_URL . 'cookiebot-logo.png',
				'cbm'                => $cbm,
				'ruleset_id'         => ! empty( get_site_option( 'cookiebot-ruleset-id' ) ) ? get_site_option( 'cookiebot-ruleset-id' ) : 'settings',
			)
		);
	}
}
