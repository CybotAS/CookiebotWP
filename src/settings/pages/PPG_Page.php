<?php

namespace cybot\cookiebot\settings\pages;

use cybot\cookiebot\lib\Cookiebot_WP;
use function cybot\cookiebot\lib\asset_url;
use function cybot\cookiebot\lib\include_view;

class PPG_Page implements Settings_Page_Interface {

	const ADMIN_SLUG = 'cookiebot_ppg';

	const PPG_PLUGIN_SLUG = 'privacy-policy-usercentrics/privacy-policy-usercentrics.php';

	public function menu() {
		add_submenu_page(
			'cookiebot',
			__( 'Policy Generator Plugin', 'cookiebot' ),
			__( 'Policy Generator Plugin', 'cookiebot' ),
			'manage_options',
			self::ADMIN_SLUG,
			array( $this, 'display' ),
			30
		);
	}

	public function register_ajax_hooks() {
		add_action( 'wp_ajax_cookiebot_activate_ppg', array( $this, 'ajax_activate_plugin' ) );
		add_action( 'wp_ajax_cookiebot_install_ppg', array( $this, 'ajax_install_plugin' ) );
	}

	public function ajax_activate_plugin() {
		check_ajax_referer( 'cookiebot_ppg_nonce', 'nonce' );

		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to activate plugins.', 'cookiebot' ) ), 403 );
		}

		if ( self::is_plugin_active() ) {
			wp_send_json_success();
		}

		if ( ! self::is_plugin_installed() ) {
			wp_send_json_error( array( 'message' => __( 'Plugin is not installed.', 'cookiebot' ) ), 404 );
		}

		$result = activate_plugin( self::PPG_PLUGIN_SLUG );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ), 500 );
		}

		wp_send_json_success();
	}

	public function ajax_install_plugin() {
		check_ajax_referer( 'cookiebot_ppg_nonce', 'nonce' );

		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to install plugins.', 'cookiebot' ) ), 403 );
		}

		if ( self::is_plugin_installed() ) {
			wp_send_json_success();
		}

		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$api = plugins_api(
			'plugin_information',
			array(
				'slug'   => 'privacy-policy-usercentrics',
				'fields' => array( 'sections' => false ),
			)
		);

		if ( is_wp_error( $api ) ) {
			wp_send_json_error( array( 'message' => $api->get_error_message() ), 500 );
		}

		$upgrader = new \Plugin_Upgrader( new \WP_Ajax_Upgrader_Skin() );
		$result   = $upgrader->install( $api->download_link );

		if ( is_wp_error( $result ) || ! $result ) {
			wp_send_json_error( array( 'message' => __( 'Plugin installation failed.', 'cookiebot' ) ), 500 );
		}

		wp_send_json_success();
	}

	public function display() {
		wp_enqueue_style(
			'cookiebot-ppg-page-css',
			asset_url( 'css/backend/ppg_page.css' ),
			array(),
			Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION
		);

		$is_installed = self::is_plugin_installed();
		$is_active    = self::is_plugin_active();

		if ( ! $is_active ) {
			wp_enqueue_script(
				'cookiebot-ppg-page-js',
				asset_url( 'js/backend/ppg-page.js' ),
				array(),
				Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
				true
			);

			$ppg_redirect_url = add_query_arg(
				array(
					'page'    => 'privacy-policy-usercentrics',
					'ppg_ref' => 'content-distribution',
				),
				admin_url( 'admin.php' )
			);

			wp_localize_script(
				'cookiebot-ppg-page-js',
				'cookiebot_ppg',
				array(
					'ajax_url'     => admin_url( 'admin-ajax.php' ),
					'nonce'        => wp_create_nonce( 'cookiebot_ppg_nonce' ),
					'redirect_url' => $ppg_redirect_url,
					'i18n'         => array(
						'installing' => __( 'Installing...', 'cookiebot' ),
						'install'    => __( 'Install Now', 'cookiebot' ),
						'activating' => __( 'Activating...', 'cookiebot' ),
						'activate'   => __( 'Activate', 'cookiebot' ),
					),
				)
			);
		}

		$args = array(
			'hero_image'   => asset_url( 'img/ppg-hero.png' ),
			'is_installed' => $is_installed,
			'is_active'    => $is_active,
		);

		include_view( 'admin/common/ppg-page.php', $args );
	}

	private static function is_plugin_installed() {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins = get_plugins();
		return isset( $plugins[ self::PPG_PLUGIN_SLUG ] );
	}

	private static function is_plugin_active() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return is_plugin_active( self::PPG_PLUGIN_SLUG );
	}
}
