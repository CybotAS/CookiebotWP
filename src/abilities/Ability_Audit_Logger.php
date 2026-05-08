<?php

namespace cybot\cookiebot\abilities;

/**
 * Class Ability_Audit_Logger
 *
 * Logs AI ability executions for audit trails, capping at 50 most recent entries.
 *
 * @package cybot\cookiebot\abilities
 */
class Ability_Audit_Logger {

	const OPTION_NAME = 'cookiebot-ai-action-log';
	const MAX_ENTRIES = 50;

	/**
	 * Log an ability execution.
	 *
	 * @param string $ability_name e.g. 'cookiebot/set-cbid'
	 * @param mixed  $old_value The value before the change
	 * @param mixed  $new_value The value after the change
	 *
	 * @return void
	 *
	 * @since 4.8.0
	 */
	public function log( $ability_name, $old_value, $new_value ) {
		$log = get_option( self::OPTION_NAME, array() );
		if ( ! is_array( $log ) ) {
			$log = array();
		}
		array_unshift(
			$log,
			array(
				'ts'      => time(),
				'ability' => $ability_name,
				'user_id' => get_current_user_id(),
				'old'     => $old_value,
				'new'     => $new_value,
			)
		);
		$log = array_slice( $log, 0, self::MAX_ENTRIES );
		update_option( self::OPTION_NAME, $log, false );
	}
}
