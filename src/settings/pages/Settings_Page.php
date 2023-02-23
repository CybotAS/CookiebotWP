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

	const ICON = 'data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgNzIgNTQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0iI0ZGRkZGRiIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNNDYuODcyNTkwMyA4Ljc3MzU4MzM0QzQxLjk0MzkwMzkgMy4zODI5NTAxMSAzNC44NDI0OTQ2IDAgMjYuOTQ4MjgxOSAwIDEyLjA2NTE1NjggMCAwIDEyLjAyNDQ3NzQgMCAyNi44NTc0MjE5YzAgMTQuODMyOTQ0NSAxMi4wNjUxNTY4IDI2Ljg1NzQyMTkgMjYuOTQ4MjgxOSAyNi44NTc0MjE5IDcuODk0MjEyNyAwIDE0Ljk5NTYyMi0zLjM4Mjk1MDIgMTkuOTI0MzA4NC04Ljc3MzU4MzQtMi44ODk2OTY3LTEuMzY4ODY2My01LjM5OTMxMS0zLjQwNTQzOS03LjMyODA4MzgtNS45MDk2MzU4LTMuMTIxNDMwNiAzLjIwOTQxMDQtNy40OTI5OTQ0IDUuMjA0MTI5MS0xMi4zMzIwMjU4IDUuMjA0MTI5MS05LjQ4NDM0NDQgMC0xNy4xNzI5MjQ3LTcuNjYyNjU3Mi0xNy4xNzI5MjQ3LTE3LjExNTAyMzhzNy42ODg1ODAzLTE3LjExNTAyMzcgMTcuMTcyOTI0Ny0xNy4xMTUwMjM3YzQuNzIzNDgyMiAwIDkuMDAxNTU1MiAxLjkwMDU5MzkgMTIuMTA2MjkyIDQuOTc2MzA5IDEuOTU2OTIzNy0yLjY0MTEzMSA0LjU1MDAyNjMtNC43ODU1MTgzIDcuNTUzODE3Ni02LjIwODQzMTg2eiIvPjxwYXRoIGQ9Ik01NS4zODAzMjgyIDQyLjY1MDE5OTFDNDYuMzMzNzIyNyA0Mi42NTAxOTkxIDM5IDM1LjM0MTIwMzEgMzkgMjYuMzI1MDk5NiAzOSAxNy4zMDg5OTYgNDYuMzMzNzIyNyAxMCA1NS4zODAzMjgyIDEwYzkuMDQ2NjA1NSAwIDE2LjM4MDMyODIgNy4zMDg5OTYgMTYuMzgwMzI4MiAxNi4zMjUwOTk2IDAgOS4wMTYxMDM1LTcuMzMzNzIyNyAxNi4zMjUwOTk1LTE2LjM4MDMyODIgMTYuMzI1MDk5NXptLjAyMTMwOTItNy43NTU2MzQyYzQuNzM3MDI3NiAwIDguNTc3MTQ3MS0zLjgyNzE3MiA4LjU3NzE0NzEtOC41NDgyMjc5IDAtNC43MjEwNTYtMy44NDAxMTk1LTguNTQ4MjI4LTguNTc3MTQ3MS04LjU0ODIyOC00LjczNzAyNzUgMC04LjU3NzE0NyAzLjgyNzE3Mi04LjU3NzE0NyA4LjU0ODIyOCAwIDQuNzIxMDU1OSAzLjg0MDExOTUgOC41NDgyMjc5IDguNTc3MTQ3IDguNTQ4MjI3OXoiLz48L2c+PC9zdmc+';

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
