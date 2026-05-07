<?php
/**
 * Toggle_Gcm_Ability class.
 *
 * @package cookiebot
 * @subpackage abilities
 */

namespace cybot\cookiebot\abilities;

/**
 * Ability to toggle Google Consent Mode v2.
 *
 * @since 4.8.0
 */
class Toggle_Gcm_Ability implements Cookiebot_Ability_Interface {

	/**
	 * Audit logger instance.
	 *
	 * @var Ability_Audit_Logger
	 */
	private $logger;

	/**
	 * Constructor.
	 *
	 * @param Ability_Audit_Logger $logger Audit logger instance.
	 *
	 * @since 4.8.0
	 */
	public function __construct( Ability_Audit_Logger $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Returns the ability name.
	 *
	 * @return string
	 *
	 * @since 4.8.0
	 */
	public function get_name() {
		return 'cookiebot/toggle-gcm';
	}

	/**
	 * Returns the ability arguments.
	 *
	 * @return array
	 *
	 * @since 4.8.0
	 */
	public function get_args() {
		$logger = $this->logger;

		return array(
			'label'               => __( 'Toggle Google Consent Mode v2', 'cookiebot' ),
			'description'         => __( 'Enables or disables Google Consent Mode v2. GCM v2 is required for Google Ads and Analytics in the EU/EEA and is enabled by default. Only disable if explicitly requested by the user.', 'cookiebot' ),
			'category'            => 'cookiebot',
			'input_schema'        => array(
				'type'                 => 'object',
				'properties'           => array(
					'enabled' => array(
						'type'        => 'boolean',
						'description' => __( 'True to enable Google Consent Mode v2, false to disable.', 'cookiebot' ),
					),
				),
				'required'             => array( 'enabled' ),
				'additionalProperties' => false,
			),
			'output_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'old_value' => array( 'type' => 'boolean', 'description' => 'Previous GCM enabled state.' ),
					'new_value' => array( 'type' => 'boolean', 'description' => 'New GCM enabled state.' ),
					'success'   => array( 'type' => 'boolean', 'description' => 'Whether the update succeeded.' ),
				),
				'additionalProperties' => false,
			),
			'execute_callback'    => function( $input ) use ( $logger ) {
				$enabled   = isset( $input['enabled'] ) ? (bool) $input['enabled'] : true;
				$old_value = get_option( 'cookiebot-gcm', '1' ) === '1';
				update_option( 'cookiebot-gcm', $enabled ? '1' : '0' );
				$logger->log( 'cookiebot/toggle-gcm', $old_value, $enabled );
				return array(
					'old_value' => $old_value,
					'new_value' => $enabled,
					'success'   => true,
				);
			},
			'permission_callback' => function() {
				return current_user_can( 'manage_options' );
			},
			'meta'                => array(
				'annotations'  => array( 'readonly' => false, 'destructive' => false, 'idempotent' => true ),
				'show_in_rest' => true,
			),
		);
	}
}
