<?php
/**
 * Get_Compliance_Summary_Ability class.
 *
 * @package cookiebot
 * @subpackage abilities
 */

namespace cybot\cookiebot\abilities;

use cybot\cookiebot\lib\Cookiebot_WP;

/**
 * Ability to get compliance summary.
 *
 * @since 4.8.0
 */
class Get_Compliance_Summary_Ability implements Cookiebot_Ability_Interface {

	/**
	 * Returns the ability name.
	 *
	 * @return string
	 *
	 * @since 4.8.0
	 */
	public function get_name() {
		return 'cookiebot/get-compliance-summary';
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
			'label'               => __( 'Get Compliance Summary', 'cookiebot' ),
			'description'         => __( 'Returns the privacy regulations covered by Cookiebot CMP on this site, plus the current scan status and plan type.', 'cookiebot' ),
			'category'            => 'cookiebot',
			'output_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'regulations_covered'   => array( 'type' => 'array', 'items' => array( 'type' => 'string' ), 'description' => 'Privacy regulations covered by Cookiebot CMP.' ),
					'scan_status'           => array( 'type' => 'string',  'description' => 'Current cookie scan status.' ),
					'consent_log_available' => array( 'type' => 'boolean', 'description' => 'Whether a consent log exists (requires CBID to be configured).' ),
					'plan_type'             => array( 'type' => 'string',  'description' => 'Detected plan type: free, premium, or unknown.' ),
				),
				'additionalProperties' => false,
			),
			'execute_callback'    => function() {
				$cbid      = Cookiebot_WP::get_cbid();
				$user_data = Cookiebot_WP::get_user_data();
				$plan_type = 'unknown';

				if ( ! empty( $user_data ) ) {
					// get_user_data() may return a string (raw JSON) or an array (already-decoded option value).
					$data = is_string( $user_data ) ? json_decode( $user_data, true ) : $user_data;
					if ( is_array( $data ) && isset( $data['subscription']['plan'] ) ) {
						$plan = strtolower( $data['subscription']['plan'] );
						if ( false !== strpos( $plan, 'free' ) ) {
							$plan_type = 'free';
						} elseif ( false !== strpos( $plan, 'premium' ) || false !== strpos( $plan, 'pro' ) ) {
							$plan_type = 'premium';
						}
					}
				}

				return array(
					'regulations_covered'   => array( 'GDPR', 'ePrivacy', 'CCPA/CPRA', 'LGPD', 'PDPA', 'PIPEDA', 'IAB TCF 2.2', 'Google Consent Mode v2' ),
					'scan_status'           => (string) get_option( 'cookiebot-scan-status', '' ),
					'consent_log_available' => ! empty( $cbid ),
					'plan_type'             => $plan_type,
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
