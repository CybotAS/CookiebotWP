<?php

namespace cybot\cookiebot\lib;

use function add_action;
use function admin_url;
use function check_ajax_referer;
use function current_user_can;
use function get_option;
use function sanitize_text_field;
use function set_transient;
use function update_option;
use function wp_send_json_error;
use function wp_send_json_success;
use function wp_unslash;

/**
 * AJAX handlers for the dashboard setup flow: connect / disconnect an ID and
 * record that the sign-up link was clicked.
 *
 * The ID is sensitive: never log or echo it.
 */
class Dashboard_Setup_Ajax {

	const NONCE_ACTION             = 'cookiebot-dashboard-setup';
	const OPTION_SIGNUP_CLICKED    = 'cookiebot-signup-clicked';
	const TRANSIENT_JUST_CONNECTED = 'cookiebot_just_connected';

	/**
	 * Register the AJAX hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_action( 'wp_ajax_cookiebot_connect_cbid', array( $this, 'ajax_connect' ) );
		add_action( 'wp_ajax_cookiebot_disconnect_cbid', array( $this, 'ajax_disconnect' ) );
		add_action( 'wp_ajax_cookiebot_signup_clicked', array( $this, 'ajax_signup_clicked' ) );
	}

	/**
	 * Validate, optionally check, and save the submitted ID.
	 *
	 * @return void
	 */
	public function ajax_connect() {
		$this->authorize();

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- Nonce verified in authorize().
		$id    = isset( $_POST['cbid'] ) ? Cbid_Checker::normalize( sanitize_text_field( wp_unslash( $_POST['cbid'] ) ) ) : '';
		$force = isset( $_POST['force'] ) && '1' === $_POST['force'];
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		if ( Cbid_Checker::TYPE_INVALID === Cbid_Checker::get_type( $id ) ) {
			wp_send_json_error( array( 'code' => 'invalid' ) );
		}

		if ( ! $force ) {
			$result = Cbid_Checker::check( $id );

			if ( Cbid_Checker::RESULT_INVALID === $result ) {
				wp_send_json_error( array( 'code' => 'invalid' ) );
			}

			if ( Cbid_Checker::RESULT_NOT_SET_UP === $result ) {
				wp_send_json_error(
					array(
						'code'   => 'not_set_up',
						'domain' => Cbid_Checker::get_site_host(),
					)
				);
			}
		}

		update_option( 'cookiebot-cbid', $id );

		// update_option() returns false for an unchanged value, so verify instead.
		if ( get_option( 'cookiebot-cbid' ) !== $id ) {
			wp_send_json_error( array( 'code' => 'save_failed' ) );
		}

		if ( ! $force ) {
			set_transient( self::TRANSIENT_JUST_CONNECTED, '1', 300 );
		}

		$this->send_redirect();
	}

	/**
	 * Clear the site's ID. update_option (not delete_option) so that the
	 * updated_option cleanup hook fires. The network option is untouched.
	 *
	 * @return void
	 */
	public function ajax_disconnect() {
		$this->authorize();

		update_option( 'cookiebot-cbid', '' );

		$this->send_redirect();
	}

	/**
	 * Record that the sign-up link was clicked.
	 *
	 * @return void
	 */
	public function ajax_signup_clicked() {
		$this->authorize();

		update_option( self::OPTION_SIGNUP_CLICKED, '1' );

		wp_send_json_success();
	}

	/**
	 * Verify nonce and capability, or end the request with a 401.
	 *
	 * @return void
	 */
	private function authorize() {
		if ( ! check_ajax_referer( self::NONCE_ACTION, 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized', 401 );
		}
	}

	/**
	 * Respond with success and the dashboard URL.
	 *
	 * @return void
	 */
	private function send_redirect() {
		wp_send_json_success( array( 'redirect' => admin_url( 'admin.php?page=cookiebot' ) ) );
	}
}
