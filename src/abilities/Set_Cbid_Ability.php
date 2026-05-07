<?php
/**
 * Set_Cbid_Ability class.
 *
 * @package cookiebot
 * @subpackage abilities
 */

namespace cybot\cookiebot\abilities;

use cybot\cookiebot\lib\Cookiebot_WP;

/**
 * Ability to set the Cookiebot Domain Group ID.
 *
 * @since 4.8.0
 */
class Set_Cbid_Ability implements Cookiebot_Ability_Interface {

	const CBID_PATTERN = '^([0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}|[a-zA-Z0-9]{9}|[a-zA-Z0-9]{14})$';

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
		return 'cookiebot/set-cbid';
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
			'label'               => __( 'Set Domain Group ID', 'cookiebot' ),
			'description'         => __( 'Sets the Domain Group ID (CBID). For Cookiebot accounts this is a UUID (XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX) found under Implementation in the Cookiebot Admin. For Usercentrics accounts this is a Settings ID (9 or 14 characters) found in the Usercentrics Admin. Changes take effect immediately.', 'cookiebot' ),
			'category'            => 'cookiebot',
			'input_schema'        => array(
				'type'                 => 'object',
				'properties'           => array(
					'cbid' => array(
						'type'        => 'string',
						'pattern'     => self::CBID_PATTERN,
						'description' => __( 'The Domain Group ID: a UUID for Cookiebot accounts (XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX) or a Settings ID for Usercentrics accounts (9 or 14 alphanumeric characters).', 'cookiebot' ),
					),
				),
				'required'             => array( 'cbid' ),
				'additionalProperties' => false,
			),
			'output_schema'       => array(
				'type'                 => 'object',
				'properties'           => array(
					'old_cbid_set' => array(
						'type'        => 'boolean',
						'description' => 'Whether a Domain Group ID was configured before this call.',
					),
					'new_cbid_set' => array(
						'type'        => 'boolean',
						'description' => 'Whether a Domain Group ID is now configured.',
					),
					'success'      => array(
						'type'        => 'boolean',
						'description' => 'Whether the update succeeded.',
					),
				),
				'additionalProperties' => false,
			),
			'execute_callback'    => function( $input ) use ( $logger ) {
				$cbid = isset( $input['cbid'] ) ? $input['cbid'] : '';

				if ( empty( $cbid ) || ! preg_match( '/' . self::CBID_PATTERN . '/', $cbid ) ) {
					return new \WP_Error(
						'cookiebot_invalid_cbid',
						__( 'Invalid ID. Provide a Cookiebot Domain Group ID (UUID: XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX) or a Usercentrics Settings ID (9 or 14 alphanumeric characters).', 'cookiebot' )
					);
				}

				$old_cbid = (string) Cookiebot_WP::get_cbid();
				update_option( 'cookiebot-cbid', $cbid );
				// Log only whether a value existed before/after — never log the CBID value itself.
				$logger->log( 'cookiebot/set-cbid', $old_cbid !== '' ? 'was_set' : 'was_unset', 'set' );

				return array(
					'old_cbid_set' => $old_cbid !== '',
					'new_cbid_set' => true,
					'success'      => true,
				);
			},
			'permission_callback' => function() {
				return current_user_can( 'manage_options' );
			},
			'meta'                => array(
				'annotations'  => array(
					'readonly'    => false,
					'destructive' => false,
					'idempotent'  => true,
				),
				'show_in_rest' => true,
			),
		);
	}
}
