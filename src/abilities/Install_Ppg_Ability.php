<?php
// src/abilities/Install_Ppg_Ability.php

namespace cybot\cookiebot\abilities;

use cybot\cookiebot\settings\pages\PPG_Page;

class Install_Ppg_Ability implements Cookiebot_Ability_Interface {

	/**
	 * @var Ability_Audit_Logger
	 */
	private $logger;

	/**
	 * @param Ability_Audit_Logger $logger
	 *
	 * @since 4.8.0
	 */
	public function __construct( Ability_Audit_Logger $logger ) {
		$this->logger = $logger;
	}

	/**
	 * @return string
	 *
	 * @since 4.8.0
	 */
	public function get_name() {
		return 'cookiebot/install-ppg';
	}

	/**
	 * @return array
	 *
	 * @since 4.8.0
	 */
	public function get_args() {
		$logger = $this->logger;

		return array(
			'label'               => __( 'Install Privacy Policy Generator', 'cookiebot' ),
			'description'         => __( 'Installs and activates the Privacy Policy Generator plugin (privacy-policy-usercentrics). Idempotent — returns immediately if already active. After install, direct the user to admin_url to complete policy setup.', 'cookiebot' ),
			'category'            => 'cookiebot',
			'output_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'was_already_active' => array( 'type' => 'boolean', 'description' => 'True if plugin was already active.' ),
					'success'            => array( 'type' => 'boolean', 'description' => 'Whether the operation succeeded.' ),
					'admin_url'          => array( 'type' => 'string',  'description' => 'WP admin URL for the PPG settings page.' ),
				),
				'additionalProperties' => false,
			),
			'execute_callback'    => function() use ( $logger ) {
				if ( ! function_exists( 'is_plugin_active' ) ) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				$admin_url = admin_url( 'admin.php?page=' . PPG_Page::ADMIN_SLUG );

				if ( is_plugin_active( PPG_Page::PPG_PLUGIN_SLUG ) ) {
					$logger->log( 'cookiebot/install-ppg', 'already_active', 'already_active' );
					return array( 'was_already_active' => true, 'success' => true, 'admin_url' => $admin_url );
				}

				if ( ! function_exists( 'get_plugins' ) ) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				$plugins = get_plugins();
				if ( isset( $plugins[ PPG_Page::PPG_PLUGIN_SLUG ] ) ) {
					$result = activate_plugin( PPG_Page::PPG_PLUGIN_SLUG );
					if ( is_wp_error( $result ) ) {
						return new \WP_Error( 'cookiebot_ppg_install_failed', $result->get_error_message() );
					}
					$logger->log( 'cookiebot/install-ppg', 'installed_inactive', 'activated' );
					return array( 'was_already_active' => false, 'success' => true, 'admin_url' => $admin_url );
				}

				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
				require_once ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php';
				require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

				$api = plugins_api(
					'plugin_information',
					array(
						'slug'   => 'privacy-policy-usercentrics',
						'fields' => array( 'sections' => false ),
					)
				);

				if ( is_wp_error( $api ) ) {
					return new \WP_Error( 'cookiebot_ppg_install_failed', $api->get_error_message() );
				}

				// Verify the API returned the expected slug and a trusted download origin.
				if ( ! isset( $api->slug ) || 'privacy-policy-usercentrics' !== $api->slug ) {
					return new \WP_Error( 'cookiebot_ppg_install_failed', __( 'Unexpected plugin data returned from WordPress.org.', 'cookiebot' ) );
				}
				if ( ! isset( $api->download_link ) || strpos( $api->download_link, 'https://downloads.wordpress.org/' ) !== 0 ) {
					return new \WP_Error( 'cookiebot_ppg_install_failed', __( 'Plugin download URL does not originate from WordPress.org.', 'cookiebot' ) );
				}

				$upgrader = new \Plugin_Upgrader( new \WP_Ajax_Upgrader_Skin() );
				$install  = $upgrader->install( $api->download_link );

				if ( is_wp_error( $install ) || ! $install ) {
					return new \WP_Error( 'cookiebot_ppg_install_failed', __( 'Plugin installation failed.', 'cookiebot' ) );
				}

				$activate = activate_plugin( PPG_Page::PPG_PLUGIN_SLUG );
				if ( is_wp_error( $activate ) ) {
					return new \WP_Error( 'cookiebot_ppg_install_failed', $activate->get_error_message() );
				}

				delete_transient( 'ppguc_activation_redirect' );
				$logger->log( 'cookiebot/install-ppg', 'not_installed', 'installed_activated' );
				return array( 'was_already_active' => false, 'success' => true, 'admin_url' => $admin_url );
			},
			'permission_callback' => function() {
				return current_user_can( 'manage_options' ) && current_user_can( 'install_plugins' );
			},
			'meta'                => array(
				'annotations'  => array( 'readonly' => false, 'destructive' => false, 'idempotent' => true ),
				'show_in_rest' => true,
			),
		);
	}
}
