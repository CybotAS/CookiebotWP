<?php
/**
 * Get_Status_Ability class.
 *
 * @package cookiebot
 * @subpackage abilities
 */

namespace cybot\cookiebot\abilities;

use cybot\cookiebot\lib\Cookiebot_WP;

/**
 * Ability to get Cookiebot CMP status.
 *
 * @since 4.8.0
 */
class Get_Status_Ability implements Cookiebot_Ability_Interface {

	/**
	 * Returns the ability name.
	 *
	 * @return string
	 *
	 * @since 4.8.0
	 */
	public function get_name() {
		return 'cookiebot/get-status';
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
			'label'               => __( 'Get Cookiebot Status', 'cookiebot' ),
			'description'         => __( 'Returns the current Cookiebot CMP plugin configuration: whether a Domain Group ID is set, GCM v2 status, cookie blocking mode, banner state, and plugin version.', 'cookiebot' ),
			'category'            => 'cookiebot',
			'output_schema'       => array(
				'type'                 => 'object',
				'properties'           => array(
					'cbid_set'       => array( 'type' => 'boolean', 'description' => 'Whether a Domain Group ID has been configured.' ),
					'gcm_enabled'    => array( 'type' => 'boolean', 'description' => 'Whether Google Consent Mode v2 is enabled.' ),
					'blocking_mode'  => array( 'type' => 'string',  'description' => 'Cookie blocking mode: auto or manual.' ),
					'banner_enabled' => array( 'type' => 'boolean', 'description' => 'Whether the consent banner is enabled.' ),
					'plugin_version' => array( 'type' => 'string',  'description' => 'Installed plugin version.' ),
				),
				'additionalProperties' => false,
			),
			'execute_callback'    => function() {
				$cbid = Cookiebot_WP::get_cbid();
				return array(
					'cbid_set'       => ! empty( $cbid ),
					'gcm_enabled'    => Cookiebot_WP::get_gcm_enabled() === '1',
					'blocking_mode'  => Cookiebot_WP::get_cookie_blocking_mode(),
					'banner_enabled' => Cookiebot_WP::get_banner_enabled() === '1',
					'plugin_version' => Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
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
