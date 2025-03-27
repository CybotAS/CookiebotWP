<?php

namespace cybot\cookiebot\settings\pages;

use cybot\cookiebot\lib\Cookiebot_Frame;
use cybot\cookiebot\lib\Cookiebot_WP;
use InvalidArgumentException;
use function cybot\cookiebot\lib\include_view;

// Import WordPress functions from global namespace
use function \add_menu_page;
use function \add_submenu_page;
use function \do_action;
use function \wp_enqueue_style;
use function \wp_enqueue_script;
use function \wp_localize_script;
use function \admin_url;
use function \wp_create_nonce;
use function \__;
use function \defined;
use function \constant;

// Add constant for WP_DEBUG
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

class Dashboard_Page implements Settings_Page_Interface {


	const ICON = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGcgY2xpcC1wYXRoPSJ1cmwoI2NsaXAwXzY0ODFfMzE4MTUpIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik01Ljg2MTYgNS44MDUyVjE5LjgxMTRDNS44NjE2IDI3LjUzNjMgMTIuMjAzOSAzMy44MTc2IDIwLjAwMzkgMzMuODE3NkMyNy44MDM4IDMzLjgxNzYgMzQuMTQ2MiAyNy41MzYzIDM0LjE0NjIgMTkuODExNFY1LjgwNTJINS44NjE2Wk0yMCAzOS42MjI4QzguOTc2MzggMzkuNjIwNyAwIDMwLjczNzEgMCAxOS44MTE0VjBINDBWMTkuODExNEM0MCAzMC43Mjk0IDMxLjAzMTQgMzkuNjIwNyAyMCAzOS42MjI4Wk0yMi42ODk0IDI2Ljk0ODZMMjIuNjg4OCAyNi45NDk5SDE1LjkyTDE1LjkzMTIgMjYuOTI2Nkw5Ljk4OTIxIDE2LjU4MjFIMTYuNzY1N0wxOS4wMTA2IDIwLjQ5MDJMMjMuNzEyMiAxMC42NjMxSDMwLjQ4ODhMMjIuNzAzNSAyNi45MTkyTDIyLjcyMDQgMjYuOTQ4NkgyMi42ODk0WiIgZmlsbD0iYmxhY2siLz4KPC9nPgo8ZGVmcz4KPGNsaXBQYXRoIGlkPSJjbGlwMF82NDgxXzMxODE1Ij4KPHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSJ3aGl0ZSIvPgo8L2NsaXBQYXRoPgo8L2RlZnM+Cjwvc3ZnPgo=';

	const ADMIN_SLUG = 'cookiebot';

	public function menu() {
		add_menu_page(
			'Cookiebot',
			__( 'Cookiebot', 'cookiebot' ),
			'manage_options',
			self::ADMIN_SLUG,
			array( $this, 'display' ),
			self::ICON
		);

		if ( Cookiebot_Frame::is_cb_frame_type() !== 'empty' ) {
			add_submenu_page(
				'cookiebot',
				__( 'Cookiebot Dashboard', 'cookiebot' ),
				__( 'Dashboard', 'cookiebot' ),
				'manage_options',
				self::ADMIN_SLUG,
				array( $this, 'display' ),
				1
			);
		}
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function display() {
		// Get all necessary data upfront
		$cbid            = Cookiebot_WP::get_cbid();
		$user_data       = Cookiebot_WP::get_user_data();
		$scan_status     = Cookiebot_WP::get_scan_status();
		$banner_enabled  = Cookiebot_WP::get_banner_enabled();
		$gcm_enabled     = Cookiebot_WP::get_gcm_enabled();
		$subscription    = Cookiebot_WP::get_subscription_type();
		$legal_framework = Cookiebot_WP::get_legal_framwework();

		// Get user data and check if they were onboarded via signup
		$was_onboarded = isset( $user_data['onboarded_via_signup'] ) && $user_data['onboarded_via_signup'] === true;
		$user_email    = isset( $user_data['email'] ) ? $user_data['email'] : '';

		// Prepare variables for the template
		$template_args = array(
			'cbid'                  => $cbid,
			'user_data'             => $user_data,
			'scan_status'           => $scan_status,
			'cb_wp'                 => CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/cb-wp.png',
			'europe_icon'           => CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/europe.png',
			'usa_icon'              => CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/usa.png',
			'check_icon'            => CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/check.svg',
			'link_icon'             => CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/img/icons/link.svg',
			'banner_enabled'        => $banner_enabled,
			'gcm_enabled'           => $gcm_enabled,
			'preview_link'          => Cookiebot_WP::get_preview_link(),
			'subscription'          => $subscription,
			'legal_framework'       => $legal_framework,
			'customize_banner_link' => 'https://admin.usercentrics.eu/#/v3/appearance/layout?settingsId=' . $cbid,
			'cookiebot_admin_link'  => 'https://admin.cookiebot.com',
			'uc_admin_link'         => 'https://admin.usercentrics.eu',
			'configure_banner_link' => 'https://support.usercentrics.com/hc/en-us/articles/18225055002908-WordPress-Plugin-FAQ',
			'has_user_data'         => ! empty( $user_data ),
			'has_cbid'              => ! empty( $cbid ),
			'is_authenticated'      => ! empty( Cookiebot_WP::get_auth_token() ),
			'was_onboarded'         => $was_onboarded,
			'user_email'            => $user_email,
		);

		// Modified condition to check for onboarding status
		if ( ! empty( $cbid ) && ( empty( $user_data ) && ! $was_onboarded ) ) {
			wp_enqueue_style(
				'cookiebot-dashboard-backup-css',
				CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/css/backend/dashboard-old.css',
				array(),
				Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
			);

			wp_enqueue_script(
				'cookiebot-account-js',
				CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/js/backend/account.js',
				array( 'jquery' ),
				Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
				true
			);

			wp_localize_script(
				'cookiebot-account-js',
				'cookiebot_account',
				array(
					'ajax_url'      => admin_url( 'admin-ajax.php' ),
					'nonce'         => wp_create_nonce( 'cookiebot-account' ),
					'cbid'          => $cbid,
					'has_user_data' => ! empty( $user_data ),
					'has_cbid'      => ! empty( $cbid ),
					'debug'         => defined( 'WP_DEBUG' ) && WP_DEBUG,
				)
			);

			require_once CYBOT_COOKIEBOT_PLUGIN_DIR . 'src/view/admin/common/dashboard-page-old.php';
			return;
		}

		wp_enqueue_style(
			'cookiebot-dashboard-css',
			CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/css/backend/dashboard.css',
			array(),
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
		);

		wp_enqueue_script(
			'cookiebot-dashboard-js',
			CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/js/backend/dashboard.js',
			array( 'jquery' ),
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
			true
		);

		wp_enqueue_script(
			'cookiebot-account-js',
			CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/js/backend/account.js',
			array( 'jquery' ),
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
			true
		);

		wp_localize_script(
			'cookiebot-account-js',
			'cookiebot_account',
			array(
				'ajax_url'         => admin_url( 'admin-ajax.php' ),
				'nonce'            => wp_create_nonce( 'cookiebot-account' ),
				'cbid'             => $cbid,
				'has_user_data'    => ! empty( $user_data ),
				'has_cbid'         => ! empty( $cbid ),
				'is_authenticated' => ! empty( Cookiebot_WP::get_auth_token() ),
				'messages'         => array(
					'success_create' => __( 'Account created successfully!', 'cookiebot' ),
					'error_create'   => __( 'Failed to create account.', 'cookiebot' ),
					'success_verify' => __( 'CBID verified successfully!', 'cookiebot' ),
					'error_verify'   => __( 'Invalid CBID.', 'cookiebot' ),
				),
				'debug'            => defined( 'WP_DEBUG' ) && WP_DEBUG,
			)
		);

		require_once CYBOT_COOKIEBOT_PLUGIN_DIR . 'src/view/admin/common/dashboard-page.php';
	}
}
