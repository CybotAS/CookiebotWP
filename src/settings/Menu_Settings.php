<?php

namespace cybot\cookiebot\settings;

use cybot\cookiebot\settings\pages\Dashboard_Page;
use cybot\cookiebot\settings\pages\Gtm_Page;
use cybot\cookiebot\settings\pages\Iab_Page;
use cybot\cookiebot\settings\pages\Legislations_Page;
use cybot\cookiebot\settings\pages\Settings_Page;
use cybot\cookiebot\settings\pages\Support_Page;
use cybot\cookiebot\lib\Cookiebot_WP;

class Menu_Settings {


	const MENU = array(
		Dashboard_Page::class,
		Support_Page::class,
		Settings_Page::class,
	);

	public function add_menu() {
		add_action( 'admin_menu', array( $this, 'load_menu' ), 1 );

		// Register settings
		add_action( 'admin_init', array( $this, 'register_cookiebot_settings' ) );
		add_action( 'register_setting', array( $this, 'set_blocking_mode_to_auto' ), 10, 3 );
		add_action( 'updated_option', array( $this, 'check_update_option_cbid' ), 10, 3 );
	}

	public function set_blocking_mode_to_auto( $option_group, $option_name, $args ) {
		if ( $option_name === 'cookiebot-cbid' ) {
			$cbid = get_option( 'cookiebot-cbid' );
			// If the account was disconnected (empty CBID) set blocking mode to 'auto' and 'Hide cookie popup' to false.
			// Later, if the account is re-connected, the banner will be visible by default.
			if ( empty( $cbid ) ) {
				update_site_option( 'cookiebot-nooutput', '' );
				update_site_option( 'cookiebot-cookie-blocking-mode', 'auto' );
			}
		}
	}

	public function check_update_option_cbid( $option_name, $old_value, $option_value ) {
		$auth_token = Cookiebot_WP::get_auth_token();
		$user_data  = Cookiebot_WP::get_user_data();

		if ( $option_name === 'cookiebot-cbid' && empty( $option_value ) && ! empty( $auth_token ) && ! empty( $user_data ) ) {
			Cookiebot_WP::debug_log( 'Account Disconnected: clearing user_data' );
			delete_option( 'cookiebot-user-data' );
		}
	}

	public function load_menu() {
		foreach ( static::MENU as $menu ) {
			( new $menu() )->menu();
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
		register_setting( 'cookiebot', 'cookiebot-cbid-override' );
		register_setting( 'cookiebot', 'cookiebot-ruleset-id' );
		register_setting( 'cookiebot', 'cookiebot-cbid-first-run' );
		register_setting( 'cookiebot', 'cookiebot-language' );
		register_setting( 'cookiebot', 'cookiebot-front-language' );
		register_setting( 'cookiebot', 'cookiebot-nooutput' );
		register_setting( 'cookiebot', 'cookiebot-nooutput-admin' );
		register_setting( 'cookiebot', 'cookiebot-banner-enabled' );
		register_setting( 'cookiebot', 'cookiebot-ignore-scripts' );
		register_setting( 'cookiebot', 'cookiebot-autoupdate' );
		register_setting( 'cookiebot', 'cookiebot-script-tag-uc-attribute' );
		register_setting( 'cookiebot', 'cookiebot-script-tag-cd-attribute' );
		register_setting( 'cookiebot', 'cookiebot-cookie-blocking-mode' );
		register_setting( 'cookiebot', 'cookiebot-iab' );
		register_setting( 'cookiebot', 'cookiebot-tcf-version' );
		register_setting( 'cookiebot', 'cookiebot-tcf-purposes' );
		register_setting( 'cookiebot', 'cookiebot-tcf-special-purposes' );
		register_setting( 'cookiebot', 'cookiebot-tcf-features' );
		register_setting( 'cookiebot', 'cookiebot-tcf-special-features' );
		register_setting( 'cookiebot', 'cookiebot-tcf-vendors' );
		register_setting( 'cookiebot', 'cookiebot-tcf-disallowed' );
		register_setting( 'cookiebot', 'cookiebot-tcf-ac-vendors' );
		register_setting( 'cookiebot', 'cookiebot-ccpa' );
		register_setting( 'cookiebot-legislations', 'cookiebot-ccpa-domain-group-id' );
		register_setting( 'cookiebot', 'cookiebot-gtm' );
		register_setting( 'cookiebot', 'cookiebot-gtm-id' );
		register_setting( 'cookiebot', 'cookiebot-gtm-cookies' );
		register_setting( 'cookiebot', 'cookiebot-data-layer' );
		register_setting( 'cookiebot', 'cookiebot-gcm' );
		register_setting( 'cookiebot', 'cookiebot-gcm-first-run' );
		register_setting( 'cookiebot', 'cookiebot-gcm-url-passthrough' );
		register_setting( 'cookiebot', 'cookiebot-gcm-cookies' );
		register_setting( 'cookiebot', 'cookiebot-multiple-config' );
		register_setting( 'cookiebot', 'cookiebot-second-banner-regions' );
		register_setting( 'cookiebot', 'cookiebot-second-banner-id' );
		register_setting( 'cookiebot', 'cookiebot-multiple-banners' );
	}
}
