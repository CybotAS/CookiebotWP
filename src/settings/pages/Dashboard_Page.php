<?php

namespace cybot\cookiebot\settings\pages;

use cybot\cookiebot\lib\Cbid_Checker;
use cybot\cookiebot\lib\Cookiebot_Frame;
use cybot\cookiebot\lib\Cookiebot_WP;
use cybot\cookiebot\lib\Dashboard_Setup_Ajax;
use cybot\cookiebot\lib\Setup_Offer;
use InvalidArgumentException;
use function cybot\cookiebot\lib\include_view;

// Import WordPress functions from global namespace
use function add_menu_page;
use function add_submenu_page;
use function do_action;
use function wp_enqueue_style;
use function wp_enqueue_script;
use function wp_localize_script;
use function admin_url;
use function wp_create_nonce;
use function __;
use function esc_url;
use function wp_kses_post;
use function defined;
use function constant;
use function cybot\cookiebot\lib\asset_url;

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
		$cbid               = Cookiebot_WP::get_cbid();
		$auth_token         = Cookiebot_WP::get_auth_token();
		$user_data          = Cookiebot_WP::get_user_data();
		$scan_status        = Cookiebot_WP::get_scan_status();
		$scan_id            = get_option( 'cookiebot-scan-id' );
		$banner_enabled     = Cookiebot_WP::get_banner_enabled();
		$auto_blocking_mode = Cookiebot_WP::get_auto_blocking_mode();
		$gcm_enabled        = Cookiebot_WP::get_gcm_enabled();
		$subscription       = Cookiebot_WP::get_subscription_type();
		$legal_framework    = Cookiebot_WP::get_legal_framwework();
		$is_authenticated   = ! empty( Cookiebot_WP::get_auth_token() );
		$is_cb_user         = Cookiebot_Frame::is_cb_frame_type();

		// Get user data and check if they were onboarded via signup
		$was_onboarded = Cookiebot_WP::was_onboarded_via_signup();
		$user_email    = isset( $user_data['email'] ) ? $user_data['email'] : '';

		// Prepare variables for the template
		$template_args = array(
			'cbid'                  => $cbid,
			'user_data'             => $user_data,
			'scan_status'           => $scan_status,
			'scan_id'               => $scan_id,
			'cb_wp'                 => asset_url( 'img/cb-wp.png' ),
			'europe_icon'           => asset_url( 'img/europe.png' ),
			'usa_icon'              => asset_url( 'img/usa.png' ),
			'check_icon'            => asset_url( 'img/icons/check.svg' ),
			'link_icon'             => asset_url( 'img/icons/link.svg' ),
			'banner_enabled'        => $banner_enabled,
			'auto_blocking_mode'    => $auto_blocking_mode,
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

		wp_enqueue_script(
			'cookiebot-amplitude-js',
			asset_url( 'js/backend/amplitude.js' ),
			array( 'jquery' ),
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
			true
		);

		wp_localize_script(
			'cookiebot-amplitude-js',
			'cookiebot_amplitude',
			array()
		);

		if ( ! $is_authenticated && ! empty( $cbid ) && ! empty( $user_data ) && ! empty( $was_onboarded ) ) {
			wp_enqueue_style(
				'cookiebot-dashboard-css',
				asset_url( 'css/backend/dashboard.css' ),
				array(),
				Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
			);

			wp_enqueue_script(
				'cookiebot-account-static-js',
				asset_url( 'js/backend/account-static.js' ),
				array( 'jquery' ),
				Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
				true
			);

			wp_localize_script(
				'cookiebot-account-static-js',
				'cookiebot_account',
				array(
					'ajax_url'          => admin_url( 'admin-ajax.php' ),
					'nonce'             => wp_create_nonce( 'cookiebot-account' ),
					'cbid'              => $cbid,
					'scan_id'           => $scan_id,
					'has_user_data'     => ! empty( $user_data ),
					'has_cbid'          => ! empty( $cbid ),
					'was_onboarded'     => $was_onboarded,
					'debug'             => defined( 'WP_DEBUG' ) && WP_DEBUG,
					'auth_expired_flow' => true,
				)
			);

			require_once CYBOT_COOKIEBOT_PLUGIN_DIR . 'src/view/admin/common/dashboard-page-session-expired.php';
			return;
		}

		// Check if the trial has expired
		if ( Cookiebot_WP::is_trial_expired() && ! Cookiebot_WP::has_upgraded() ) {
			wp_enqueue_style(
				'cookiebot-dashboard-css',
				asset_url( 'css/backend/dashboard.css' ),
				array(),
				Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
			);

			wp_enqueue_script(
				'cookiebot-account-js',
				asset_url( 'js/backend/account.js' ),
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
					'scan_id'       => $scan_id,
					'has_user_data' => ! empty( $user_data ),
					'has_cbid'      => ! empty( $cbid ),
					'was_onboarded' => $was_onboarded,
					'debug'         => defined( 'WP_DEBUG' ) && WP_DEBUG,
				)
			);

			require_once CYBOT_COOKIEBOT_PLUGIN_DIR . 'src/view/admin/common/dashboard-trial-expired.php';
			return;
		}

		// No ID yet: new welcome/setup view.
		if ( empty( $cbid ) ) {
			$this->render_welcome();
			return;
		}

		// Cookiebot (CB) accounts and sites without account data: new connected view.
		if ( true === $is_cb_user || empty( $user_data ) ) {
			$this->render_connected();
			return;
		}

		wp_enqueue_style(
			'cookiebot-dashboard-css',
			asset_url( 'css/backend/dashboard.css' ),
			array(),
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
		);

		wp_enqueue_script(
			'cookiebot-dashboard-js',
			asset_url( 'js/backend/dashboard.js' ),
			array( 'jquery' ),
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
			true
		);

		wp_localize_script(
			'cookiebot-dashboard-js',
			'cookiebot_dashboard',
			array(
				'cbid'      => $cbid,
				'user_data' => $user_data,
			)
		);

		wp_enqueue_script(
			'cookiebot-account-js',
			asset_url( 'js/backend/account.js' ),
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
				'scan_id'          => $scan_id,
				'has_user_data'    => ! empty( $user_data ),
				'has_cbid'         => ! empty( $cbid ),
				'was_onboarded'    => $was_onboarded,
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

	/**
	 * Build the args for the welcome view.
	 *
	 * @return array
	 */
	public function get_welcome_args() {
		return array(
			'domain'         => Cbid_Checker::get_site_host(),
			'signup_clicked' => get_option( Dashboard_Setup_Ajax::OPTION_SIGNUP_CLICKED ) === '1',
			'offer_active'   => Setup_Offer::is_active(),
			'signup_url'     => Setup_Offer::get_signup_url(),
			'support_url'    => admin_url( 'admin.php?page=cookiebot_support' ),
		);
	}

	/**
	 * Build the args for the connected view. Consumes the just-connected transient.
	 *
	 * @return array
	 */
	public function get_connected_args() {
		$cbid           = Cookiebot_WP::get_cbid();
		$id_type        = Cbid_Checker::get_type( $cbid );
		$just_connected = (bool) get_transient( Dashboard_Setup_Ajax::TRANSIENT_JUST_CONNECTED );

		if ( $just_connected ) {
			delete_transient( Dashboard_Setup_Ajax::TRANSIENT_JUST_CONNECTED );
		}

		if ( $just_connected || Cbid_Checker::TYPE_SETTINGS === $id_type ) {
			$check_result = Cbid_Checker::RESULT_MATCH;
		} else {
			$check_result = Cbid_Checker::check( $cbid );
		}

		return array(
			'cbid'           => $cbid,
			'id_type'        => $id_type,
			'domain'         => Cbid_Checker::get_site_host(),
			'check_result'   => $check_result,
			'just_connected' => $just_connected,
			'banner_enabled' => Cookiebot_WP::get_banner_enabled() === '1',
			'is_network_id'  => '' === (string) get_option( 'cookiebot-cbid', '' ),
			'preview_url'    => home_url( '/' ),
			'support_url'    => admin_url( 'admin.php?page=cookiebot_support' ),
		);
	}

	private function render_welcome() {
		$args = $this->get_welcome_args();

		wp_enqueue_style(
			'cookiebot-dashboard-revamp-css',
			asset_url( 'css/backend/dashboard-revamp.css' ),
			array(),
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
		);

		wp_enqueue_script(
			'cookiebot-dashboard-setup-js',
			asset_url( 'js/backend/dashboard-setup.js' ),
			array( 'jquery' ),
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
			true
		);

		wp_localize_script(
			'cookiebot-dashboard-setup-js',
			'cookiebot_setup',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( Dashboard_Setup_Ajax::NONCE_ACTION ),
				'i18n'     => array(
					'connect'          => __( 'Connect', 'cookiebot' ),
					'connecting'       => __( 'Connecting…', 'cookiebot' ),
					'connect_anyway'   => __( 'Connect anyway', 'cookiebot' ),
					'try_again'        => __( 'Try again', 'cookiebot' ),
					'invalid'          => __( 'That doesn’t look like a valid ID. IDs have 9, 14 or 36 characters.', 'cookiebot' ),
					'save_failed_html' => wp_kses_post(
						sprintf(
							/* translators: %s: URL of the plugin's Get support page. */
							__( 'We couldn’t save this ID. Try again, or <a href="%s">get support</a>.', 'cookiebot' ),
							esc_url( $args['support_url'] )
						)
					),
					/* translators: %s: the site's domain, e.g. clientsite.com. */
					'not_set_up'       => __( 'This ID isn’t set up for %1$s. Check you copied the ID for this site, or add %1$s to your domain group in Cookiebot.', 'cookiebot' ),
				),
			)
		);

		include_view( 'admin/common/dashboard/welcome.php', $args );
	}

	private function render_connected() {
		wp_enqueue_style(
			'cookiebot-dashboard-revamp-css',
			asset_url( 'css/backend/dashboard-revamp.css' ),
			array(),
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
		);

		wp_enqueue_script(
			'cookiebot-dashboard-connected-js',
			asset_url( 'js/backend/dashboard-connected.js' ),
			array( 'jquery' ),
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
			true
		);

		wp_localize_script(
			'cookiebot-dashboard-connected-js',
			'cookiebot_connected',
			array(
				'ajax_url'      => admin_url( 'admin-ajax.php' ),
				'nonce'         => wp_create_nonce( Dashboard_Setup_Ajax::NONCE_ACTION ),
				'account_nonce' => wp_create_nonce( 'cookiebot-account' ),
				'i18n'          => array(),
			)
		);

		include_view( 'admin/common/dashboard/connected.php', $this->get_connected_args() );
	}
}
