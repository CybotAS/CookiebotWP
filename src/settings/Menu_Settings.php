<?php

namespace cybot\cookiebot\settings;

use cybot\cookiebot\settings\pages\Dashboard_Page;
use cybot\cookiebot\settings\pages\Debug_Page;
use cybot\cookiebot\settings\pages\Gtm_Page;
use cybot\cookiebot\settings\pages\Iab_Page;
use cybot\cookiebot\settings\pages\Legislations_Page;
use cybot\cookiebot\settings\pages\Settings_Page;
use cybot\cookiebot\settings\pages\Support_Page;

class Menu_Settings {

	const MENU = array(
		Dashboard_Page::class,
		Settings_Page::class,
	);

	const SUBMENU = array(
		Support_Page::class,
		Debug_Page::class,
	);

	public function add_menu() {
		add_action( 'admin_menu', array( $this, 'load_menu' ), 1 );

		// Register settings
		add_action( 'admin_init', array( $this, 'register_cookiebot_settings' ) );
	}

	public function load_menu() {
		foreach ( static::MENU as $menu ) {
			( new $menu() )->menu();
		}

		foreach ( static::SUBMENU as $submenu ) {
			( new $submenu() )->menu();
		}
	}

	/**
	 * Cookiebot_WP Register Cookiebot settings
	 *
	 * @version 3.9.0
	 * @since   1.0.0
	 */
	public function register_cookiebot_settings() {
		register_setting( 'cookiebot', 'cookiebot-cbid' );
		register_setting( 'cookiebot', 'cookiebot-language' );
		register_setting( 'cookiebot', 'cookiebot-nooutput' );
		register_setting( 'cookiebot', 'cookiebot-nooutput-admin' );
		register_setting( 'cookiebot', 'cookiebot-output-logged-in' );
		register_setting( 'cookiebot', 'cookiebot-ignore-scripts' );
		register_setting( 'cookiebot', 'cookiebot-autoupdate' );
		register_setting( 'cookiebot', 'cookiebot-script-tag-uc-attribute' );
		register_setting( 'cookiebot', 'cookiebot-script-tag-cd-attribute' );
		register_setting( 'cookiebot', 'cookiebot-cookie-blocking-mode' );
		register_setting( 'cookiebot', 'cookiebot-consent-mapping' );
		register_setting( 'cookiebot', 'cookiebot-iab' );
		register_setting( 'cookiebot', 'cookiebot-ccpa' );
		register_setting( 'cookiebot-legislations', 'cookiebot-ccpa-domain-group-id' );
		register_setting( 'cookiebot', 'cookiebot-gtm' );
		register_setting( 'cookiebot', 'cookiebot-gtm-id' );
		register_setting( 'cookiebot', 'cookiebot-data-layer' );
		register_setting( 'cookiebot', 'cookiebot-gcm' );
		register_setting( 'cookiebot', 'cookiebot-gcm-first-run' );
		register_setting( 'cookiebot', 'cookiebot-gcm-url-passthrough' );
		register_setting( 'cookiebot', 'cookiebot-multiple-config' );
		register_setting( 'cookiebot', 'cookiebot-second-banner-regions' );
		register_setting( 'cookiebot', 'cookiebot-second-banner-id' );
	}
}
