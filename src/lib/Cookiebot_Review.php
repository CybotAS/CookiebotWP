<?php

namespace cybot\cookiebot\lib;

use WP_REST_SERVER;
use function current_user_can;
use cybot\cookiebot\settings\pages\Debug_Page;

class Cookiebot_Review {

	/**
	 * Handler url.
	 *
	 * @var string
	 */
	protected $api_url = 'https://www.cookiebot.com/wp-json/cmp/v1/survey/';

	public function register_hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'cookiebot_admin_script' ), 9999 );
		add_filter( 'plugin_action_links_cookiebot/cookiebot.php', array( $this, 'plugin_action_links' ) );
		add_filter( 'network_admin_plugin_action_links', array( $this, 'plugin_action_links' ) );
		add_action( 'wp_ajax_cb_submit_survey', array( $this, 'send_uninstall_survey' ) );
	}

	/**
	 * Edit action links
	 *
	 * @param array $links action links.
	 * @return array
	 */
	public function plugin_action_links( $links ) {
		if ( array_key_exists( 'deactivate', $links ) ) {
			$links['deactivate'] = str_replace( '<a', '<a class="cb-deactivate-action"', $links['deactivate'] );
		}

		return $links;
	}

	/**
	 * Cookiebot Add ajax url
	 */
	public function cookiebot_admin_script( $hook ) {
		wp_enqueue_script(
			'cookiebot_admin_js',
			asset_url( 'js/backend/cookiebot-admin-script.js' ),
			null,
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
			true
		);

		wp_enqueue_style(
			'cookiebot-admin-global-css',
			asset_url( 'css/backend/global/cookiebot_admin.css' ),
			null,
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
		);

		wp_localize_script(
			'cookiebot_admin_js',
			'cb_ajax',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			)
		);

		wp_localize_script(
			'cookiebot_admin_js',
			'cb_survey',
			array(
				'survey_nonce'       => wp_create_nonce( 'cookiebot_survey_nonce' ),
				'logo'               => asset_url( 'img/icons/shield_icon.svg' ),
				'popup_header_title' => __( 'Cookiebot CMP Deactivation', 'cookiebot' ),
				'first_msg'          => __( 'We are sad to lose you. Take a moment to help us improve?', 'cookiebot' ),
				'options'            => array(
					array(
						'text'  => __( 'The installation is too complicated', 'cookiebot' ),
						'value' => '1',
					),
					array(
						'text'  => __( 'I found a plugin that better serves my needs', 'cookiebot' ),
						'value' => '2',
					),
					array(
						'text'  => __( 'Missing features / did not meet my expectations', 'cookiebot' ),
						'value' => '3',
					),
					array(
						'text'  => __( 'I need more customization options', 'cookiebot' ),
						'value' => '4',
					),
					array(
						'text'  => __( 'The premium plan is too expensive', 'cookiebot' ),
						'value' => '5',
					),
					array(
						'text'  => __( 'I’m only deactivating the plugin temporarily', 'cookiebot' ),
						'value' => '6',
					),
					array(
						'text'  => __( 'Other', 'cookiebot' ),
						'value' => '7',
						'extra' => __( 'Please specify here', 'cookiebot' ),
					),
				),
				'consent'            => array(
					'optional' => __( '(Optional)', 'cookiebot' ),
					'first'    => __(
						' By checking this box, you agree to submit troubleshooting information and allow us to contact you regarding the problem if necessary.',
						'cookiebot'
					),
					'second'   => __(
						'The information will be kept for no longer than 90 days. You may revoke this consent at any time, e.g. by sending an email to ',
						'cookiebot'
					),
				),
				'alert'              => __( 'Please select one option', 'cookiebot' ),
				'actions'            => array(
					'skip'   => __( 'Skip and Deactivate', 'cookiebot' ),
					'submit' => __( 'Submit and Deactivate', 'cookiebot' ),
				),
			)
		);

		if ( 'plugins.php' === $hook ) {
			include_view( 'admin/common/templates/extra/review-form.php' );
		}
	}

	/**
	 * Send uninstall reason to server
	 *
	 * @return void
	 */
	public function send_uninstall_survey() {
		global $wpdb;
		if ( ! check_ajax_referer( 'cookiebot_survey_nonce', 'survey_nonce', false ) || ! current_user_can( 'deactivate_plugins' ) ) {
			wp_send_json_error( esc_html__( 'Sorry you are not allowed to do this.', 'cookiebot' ), 401 );
		}
		if ( ! isset( $_POST['reason_id'] ) ) {
			wp_send_json_error( esc_html__( 'Please select one option', 'cookiebot' ), 400 );
		}
		$data = array(
			'survey_check'   => sanitize_text_field( wp_unslash( $_POST['survey_check'] ) ),
			'reason_slug'    => sanitize_text_field( wp_unslash( $_POST['reason_id'] ) ),
			'reason_detail'  => ! empty( $_POST['reason_text'] ) ? sanitize_text_field( wp_unslash( $_POST['reason_text'] ) ) : null,
			'comments'       => ! empty( $_POST['reason_info'] ) ? sanitize_text_field( wp_unslash( $_POST['reason_info'] ) ) : null,
			'date'           => gmdate( 'M d, Y h:i:s A' ),
			'server'         => ! empty( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : null,
			'php_version'    => phpversion(),
			'mysql_version'  => $wpdb->db_version(),
			'wp_version'     => get_bloginfo( 'version' ),
			'locale'         => get_locale(),
			'plugin_version' => Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
			'is_multisite'   => is_multisite(),
		);

		if ( ! empty( $_POST['reason_debug'] ) && rest_sanitize_boolean( $_POST['reason_debug'] ) === true ) {
			$debug_info         = new Debug_Page();
			$data['debug_info'] = wp_json_encode( $debug_info->prepare_debug_data() );
		}

		wp_remote_post(
			$this->api_url,
			array(
				'headers'     => array(
					'Content-Type' => 'application/json; charset=utf-8',
				),
				'method'      => 'POST',
				'timeout'     => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => false,
				'body'        => wp_json_encode( $data ),
				'cookies'     => array(),
			)
		);

		wp_send_json_success( null, 200 );
	}
}
