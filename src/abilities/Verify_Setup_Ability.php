<?php
/**
 * Verify_Setup_Ability class.
 *
 * @package cookiebot
 * @subpackage abilities
 */

namespace cybot\cookiebot\abilities;

use cybot\cookiebot\lib\Cookiebot_WP;

/**
 * Ability to verify Cookiebot CMP setup.
 *
 * @since 4.8.0
 */
class Verify_Setup_Ability implements Cookiebot_Ability_Interface {

	/**
	 * Returns the ability name.
	 *
	 * @return string
	 *
	 * @since 4.8.0
	 */
	public function get_name() {
		return 'cookiebot/verify-setup';
	}

	/**
	 * Returns the ability arguments.
	 *
	 * @return array
	 *
	 * @since 4.8.0
	 */
	public function get_args() {
		return array(
			'label'               => __( 'Verify Cookiebot Setup', 'cookiebot' ),
			'description'         => __( 'Checks that the Domain Group ID is configured, Google Consent Mode v2 is enabled, and cookie blocking mode is set to auto. Returns an issues[] list — empty means all checks passed.', 'cookiebot' ),
			'category'            => 'cookiebot',
			'output_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'cbid_configured'         => array( 'type' => 'boolean', 'description' => 'Whether a Domain Group ID is configured.' ),
					'configured_domain'       => array( 'type' => 'string',  'description' => 'The WordPress site home URL.' ),
					'gcm_enabled'             => array( 'type' => 'boolean', 'description' => 'Whether Google Consent Mode v2 is enabled.' ),
					'blocking_mode_automatic' => array( 'type' => 'boolean', 'description' => 'Whether cookie blocking mode is set to auto.' ),
					'issues'                  => array( 'type' => 'array', 'items' => array( 'type' => 'string' ), 'description' => 'Configuration issues found. Empty = setup complete.' ),
				),
				'additionalProperties' => false,
			),
			'execute_callback'    => function() {
				$cbid            = Cookiebot_WP::get_cbid();
				$gcm_enabled     = Cookiebot_WP::get_gcm_enabled() === '1';
				$is_automatic    = Cookiebot_WP::get_cookie_blocking_mode() === 'auto';
				$cbid_configured = ! empty( $cbid );
				$issues          = array();

				if ( ! $cbid_configured ) {
					$issues[] = 'Domain Group ID is not configured. Go to Cookiebot > Settings > General and paste your Domain Group ID.';
				}
				if ( ! $gcm_enabled ) {
					$issues[] = 'Google Consent Mode v2 is disabled. Go to Cookiebot > Settings > Google Consent Mode and enable the toggle.';
				}
				if ( ! $is_automatic ) {
					$issues[] = 'Cookie blocking mode is manual. Go to Cookiebot > Settings > General and switch to automatic mode for GDPR compliance.';
				}

				return array(
					'cbid_configured'         => $cbid_configured,
					'configured_domain'       => home_url(),
					'gcm_enabled'             => $gcm_enabled,
					'blocking_mode_automatic' => $is_automatic,
					'issues'                  => $issues,
				);
			},
			'permission_callback' => function() {
				return current_user_can( 'manage_options' );
			},
			'meta'                => array(
				'annotations'  => array( 'readonly' => true, 'destructive' => false, 'idempotent' => true ),
				'show_in_rest' => true,
			),
		);
	}
}
