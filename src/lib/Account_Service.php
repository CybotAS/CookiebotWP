<?php

namespace cybot\cookiebot\lib;

use cybot\cookiebot\settings\pages\Dashboard_Page;
use cybot\cookiebot\lib\Cookiebot_WP;
use function add_action;
use function admin_url;
use function check_ajax_referer;
use function current_user_can;
use function esc_html__;
use function esc_url_raw;
use function is_email;
use function sanitize_email;
use function sanitize_text_field;
use function update_option;
use function wp_enqueue_script;
use function wp_json_encode;
use function wp_localize_script;
use function wp_remote_post;
use function wp_remote_retrieve_body;
use function wp_remote_retrieve_response_code;
use function wp_send_json_error;
use function wp_send_json_success;
use function wp_script_is;
use function wp_remote_get;
use function current_time;

class Account_Service {

	public function __construct() {
		$this->register_hooks();
	}

	public function register_hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'cookiebot_admin_script' ), 100 );
		add_action( 'wp_ajax_cookiebot_store_cbid', array( $this, 'ajax_store_cbid' ) );
		add_action( 'wp_ajax_cookiebot_get_cbid', array( $this, 'ajax_get_cbid' ) );
		add_action( 'wp_ajax_cookiebot_get_auth_token', array( $this, 'ajax_get_auth_token' ) );
		add_action( 'wp_ajax_cookiebot_set_gcm_enabled', array( $this, 'ajax_set_gcm_enabled' ) );
		add_action( 'wp_ajax_cookiebot_set_banner_enabled', array( $this, 'ajax_set_banner_enabled' ) );
		add_action( 'wp_ajax_cookiebot_set_auto_blocking_mode', array( $this, 'ajax_set_auto_blocking_mode' ) );
		add_action( 'wp_ajax_cookiebot_process_auth_code', array( $this, 'ajax_process_auth_code' ) );
		add_action( 'wp_ajax_cookiebot_dismiss_banner', array( $this, 'ajax_dismiss_banner' ) );
		add_action( 'wp_ajax_cookiebot_post_user_data', array( $this, 'ajax_post_user_data' ) );
		add_action( 'wp_ajax_cookiebot_store_scan_details', array( $this, 'ajax_store_scan_details' ) );
		add_action( 'wp_ajax_cookiebot_get_scan_details', array( $this, 'ajax_get_scan_details' ) );
		add_action( 'wp_ajax_cookiebot_store_configuration', array( $this, 'ajax_store_configuration' ) );
		add_action( 'wp_ajax_cookiebot_clear_config_data', array( $this, 'ajax_clear_config_data' ) );
		add_action( 'wp_ajax_cookiebot_clear_config_data_keep_cbid', array( $this, 'ajax_clear_config_data_keep_cbid' ) );
		add_action( 'wp_ajax_cookiebot_delete_auth_token', array( $this, 'ajax_delete_auth_token' ) );
		add_action( 'wp_ajax_cookiebot_store_onboarding_status', array( $this, 'ajax_store_onboarding_status' ) );
	}

	public function cookiebot_admin_script( $hook ) {
		if ( 'toplevel_page_' . Dashboard_Page::ADMIN_SLUG !== $hook ) {
			return;
		}

		$is_authenticated = ! empty( Cookiebot_WP::get_auth_token() );
		$cbid             = Cookiebot_WP::get_cbid();
		$user_data        = Cookiebot_WP::get_user_data();
		$was_onboarded    = Cookiebot_WP::was_onboarded_via_signup();

		if ( ! $is_authenticated && ! empty( $cbid ) && ! empty( $user_data ) && ! empty( $was_onboarded ) ) {
			wp_enqueue_script(
				'cookiebot-account-static-js',
				CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/js/backend/account-static.js',
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
					'has_user_data'     => ! empty( $user_data ),
					'has_cbid'          => ! empty( $cbid ),
					'debug'             => defined( 'WP_DEBUG' ) && WP_DEBUG,
					'auth_expired_flow' => true,
				)
			);
		} else {
			wp_enqueue_script(
				'cookiebot-account-js',
				CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/js/backend/account.js',
				array(),
				Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
				true
			);

			wp_localize_script(
				'cookiebot-account-js',
				'cookiebot_account',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( 'cookiebot-account' ),
					'debug'    => true,
				)
			);
		}
	}

	public function ajax_store_cbid() {
		if ( ! check_ajax_referer( 'cookiebot-account', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
		}

		$cbid = isset( $_POST['cbid'] ) ? sanitize_text_field( $_POST['cbid'] ) : '';

		if ( empty( $cbid ) ) {
			wp_send_json_error( 'CBID is required', 400 );
		}

		// Store with consistent name
		update_option( 'cookiebot-cbid', $cbid );

		wp_send_json_success();
	}

	public function ajax_post_user_data() {
		if ( ! check_ajax_referer( 'cookiebot-account', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
			return;
		}

		$raw_data = isset( $_POST['data'] ) ? wp_unslash( $_POST['data'] ) : '';

		if ( empty( $raw_data ) ) {
			wp_send_json_error( 'No data provided', 400 );
			return;
		}

		$data = json_decode( $raw_data, true );
		update_option( 'cookiebot-user-data', $data );

		wp_send_json_success( array( 'message' => 'User data stored successfully' ) );
	}

	public function ajax_store_scan_details() {
		if ( ! check_ajax_referer( 'cookiebot-account', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
			return;
		}

		$scan_id     = isset( $_POST['scan_id'] ) ? sanitize_text_field( $_POST['scan_id'] ) : '';
		$scan_status = isset( $_POST['scan_status'] ) ? wp_unslash( $_POST['scan_status'] ) : '';

		update_option( 'cookiebot-scan-id', $scan_id );
		update_option( 'cookiebot-scan-status', $scan_status );

		wp_send_json_success( array( 'message' => 'Scan details stored successfully' ) );
	}

	public function ajax_set_banner_enabled() {
		if ( ! check_ajax_referer( 'cookiebot-account', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
			return;
		}

		$value = isset( $_POST['value'] ) ? trim( $_POST['value'] ) : '';

		// Save option value
		update_option( 'cookiebot-banner-enabled', $value );
		wp_send_json_success();
	}

	public function ajax_set_auto_blocking_mode() {
		if ( ! check_ajax_referer( 'cookiebot-account', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
			return;
		}

		$value = isset( $_POST['value'] ) ? trim( $_POST['value'] ) : '';

		// Save option value
		update_option( 'cookiebot-uc-auto-blocking-mode', $value );
		wp_send_json_success();
	}

	public function ajax_delete_auth_token() {
		delete_option( 'cookiebot-auth-token' );
		wp_send_json_success();
	}

	public function ajax_set_gcm_enabled() {
		if ( ! check_ajax_referer( 'cookiebot-account', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
			return;
		}

		$value = isset( $_POST['value'] ) ? trim( $_POST['value'] ) : '';

		// Save option value
		update_option( 'cookiebot-gcm', $value );
		wp_send_json_success();
	}

	public function ajax_get_cbid() {
		$cbid_req = get_option( 'cookiebot-cbid' );
		if ( $cbid_req ) {
			wp_send_json_success( $cbid_req );
		} else {
			wp_send_json_error( 'No CBID found', 404 );
		}
	}

	public function ajax_get_auth_token() {
		if ( ! check_ajax_referer( 'cookiebot-account', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
			return;
		}

		$auth_token = get_option( 'cookiebot-auth-token' );
		wp_send_json_success( $auth_token );
	}

	public function ajax_process_auth_code() {
		if ( ! check_ajax_referer( 'cookiebot-account', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
			return;
		}

		$code = isset( $_POST['code'] ) ? sanitize_text_field( $_POST['code'] ) : '';

		if ( empty( $code ) ) {
			wp_send_json_error( 'No code provided', 400 );
			return;
		}

		// Use POST request with code as query parameter
		// phpcs:ignore
		$api_url = 'https://api.ea.prod.usercentrics.cloud/v1/auth/auth0/exchange?code=' . urlencode( $code );

		$response = wp_remote_post(
			$api_url,
			array(
				'timeout' => 45,
				'headers' => array(
					'Content-Type' => 'application/json',
					'Accept'       => 'application/json',
				),
				'body'    => '',
			)
		);

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			wp_send_json_error( 'Error: ' . $error_message, 500 );
			return;
		}

		$status = wp_remote_retrieve_response_code( $response );
		$body   = wp_remote_retrieve_body( $response );

		$data  = json_decode( $body, true );
		$token = $data['token'];

		update_option( 'cookiebot-auth-token', $token );
	}

	public function ajax_dismiss_banner() {
		// Check if user has permission
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
			return;
		}

		// Store the dismissed state as a site option
		update_option( 'cookiebot_banner_live_dismissed', true );

		wp_send_json_success( array( 'message' => 'Banner dismissed successfully' ) );
	}

	public function ajax_get_scan_details() {
		if ( ! check_ajax_referer( 'cookiebot-account', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
			return;
		}

		$scan_id     = get_option( 'cookiebot-scan-id' );
		$scan_status = get_option( 'cookiebot-scan-status', '' );

		if ( $scan_id ) {
			wp_send_json_success(
				array(
					'scan_id'     => $scan_id,
					'scan_status' => $scan_status,
				)
			);
		} else {
			wp_send_json_error( 'No scan details found', 404 );
		}
	}

	public function ajax_store_configuration() {
		if ( ! check_ajax_referer( 'cookiebot-account', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
			return;
		}

		$configuration = isset( $_POST['configuration'] ) ? wp_unslash( $_POST['configuration'] ) : '';

		if ( empty( $configuration ) ) {
			wp_send_json_error( 'Configuration data is required', 400 );
			return;
		}

		$data = json_decode( $configuration, true );
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			wp_send_json_error( 'Invalid configuration data format', 400 );
			return;
		}

		update_option( 'cookiebot-configuration', $data );
		wp_send_json_success( array( 'message' => 'Configuration stored successfully' ) );
	}

	public function ajax_clear_config_data() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
			return;
		}

		// Save option value
		delete_option( 'cookiebot-cbid' );
		delete_option( 'cookiebot-auth-token' );
		delete_option( 'cookiebot-user-data' );
		delete_option( 'cookiebot-configuration' );
		delete_option( 'cookiebot-scan-id' );
		delete_option( 'cookiebot-scan-status' );
		delete_option( 'cookiebot-banner-enabled' );
		delete_option( 'cookiebot_banner_live_dismissed' );
		wp_send_json_success();
	}

	public function ajax_clear_config_data_keep_cbid() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
			return;
		}

		// Save option value
		delete_option( 'cookiebot-auth-token' );
		delete_option( 'cookiebot-user-data' );
		delete_option( 'cookiebot-configuration' );
		delete_option( 'cookiebot-scan-id' );
		delete_option( 'cookiebot-scan-status' );
		delete_option( 'cookiebot-banner-enabled' );
		delete_option( 'cookiebot_banner_live_dismissed' );
		wp_send_json_success();
	}

	public function ajax_store_onboarding_status() {
		if ( ! check_ajax_referer( 'cookiebot-account', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
			return;
		}

		$onboarded = isset( $_POST['onboarded'] ) ? (bool) $_POST['onboarded'] : false;
		update_option( 'cookiebot-uc-onboarded-via-signup', $onboarded );

		wp_send_json_success( array( 'message' => 'Onboarding status stored successfully' ) );
	}
}
