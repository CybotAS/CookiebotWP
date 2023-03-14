<?php

namespace cybot\cookiebot\settings;

use cybot\cookiebot\lib\Cookiebot_WP;
use cybot\cookiebot\settings\pages\Support_Page;
use InvalidArgumentException;
use function cybot\cookiebot\lib\asset_url;
use function cybot\cookiebot\lib\include_view;

class Network_Menu_Settings {

	const ICON = 'data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgNzIgNTQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0iI0ZGRkZGRiIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNNDYuODcyNTkwMyA4Ljc3MzU4MzM0QzQxLjk0MzkwMzkgMy4zODI5NTAxMSAzNC44NDI0OTQ2IDAgMjYuOTQ4MjgxOSAwIDEyLjA2NTE1NjggMCAwIDEyLjAyNDQ3NzQgMCAyNi44NTc0MjE5YzAgMTQuODMyOTQ0NSAxMi4wNjUxNTY4IDI2Ljg1NzQyMTkgMjYuOTQ4MjgxOSAyNi44NTc0MjE5IDcuODk0MjEyNyAwIDE0Ljk5NTYyMi0zLjM4Mjk1MDIgMTkuOTI0MzA4NC04Ljc3MzU4MzQtMi44ODk2OTY3LTEuMzY4ODY2My01LjM5OTMxMS0zLjQwNTQzOS03LjMyODA4MzgtNS45MDk2MzU4LTMuMTIxNDMwNiAzLjIwOTQxMDQtNy40OTI5OTQ0IDUuMjA0MTI5MS0xMi4zMzIwMjU4IDUuMjA0MTI5MS05LjQ4NDM0NDQgMC0xNy4xNzI5MjQ3LTcuNjYyNjU3Mi0xNy4xNzI5MjQ3LTE3LjExNTAyMzhzNy42ODg1ODAzLTE3LjExNTAyMzcgMTcuMTcyOTI0Ny0xNy4xMTUwMjM3YzQuNzIzNDgyMiAwIDkuMDAxNTU1MiAxLjkwMDU5MzkgMTIuMTA2MjkyIDQuOTc2MzA5IDEuOTU2OTIzNy0yLjY0MTEzMSA0LjU1MDAyNjMtNC43ODU1MTgzIDcuNTUzODE3Ni02LjIwODQzMTg2eiIvPjxwYXRoIGQ9Ik01NS4zODAzMjgyIDQyLjY1MDE5OTFDNDYuMzMzNzIyNyA0Mi42NTAxOTkxIDM5IDM1LjM0MTIwMzEgMzkgMjYuMzI1MDk5NiAzOSAxNy4zMDg5OTYgNDYuMzMzNzIyNyAxMCA1NS4zODAzMjgyIDEwYzkuMDQ2NjA1NSAwIDE2LjM4MDMyODIgNy4zMDg5OTYgMTYuMzgwMzI4MiAxNi4zMjUwOTk2IDAgOS4wMTYxMDM1LTcuMzMzNzIyNyAxNi4zMjUwOTk1LTE2LjM4MDMyODIgMTYuMzI1MDk5NXptLjAyMTMwOTItNy43NTU2MzQyYzQuNzM3MDI3NiAwIDguNTc3MTQ3MS0zLjgyNzE3MiA4LjU3NzE0NzEtOC41NDgyMjc5IDAtNC43MjEwNTYtMy44NDAxMTk1LTguNTQ4MjI4LTguNTc3MTQ3MS04LjU0ODIyOC00LjczNzAyNzUgMC04LjU3NzE0NyAzLjgyNzE3Mi04LjU3NzE0NyA4LjU0ODIyOCAwIDQuNzIxMDU1OSAzLjg0MDExOTUgOC41NDgyMjc5IDguNTc3MTQ3IDguNTQ4MjI3OXoiLz48L2c+PC9zdmc+';

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
			'admin/settings/network-settings-page.php',
			array(
				'cookiebot_gdpr_url' => 'https://www.cookiebot.com/' . Cookiebot_WP::get_manager_language() . '/gdpr',
				'logo'               => CYBOT_COOKIEBOT_PLUGIN_URL . 'cookiebot-logo.png',
				'cbm'                => $cbm,
			)
		);
	}
}
