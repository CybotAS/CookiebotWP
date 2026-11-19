<?php

namespace cybot\cookiebot\lib;

class Cookiebot_Frame {

	public static function is_cb_frame_type( $multisite = false ) {
		$cbid = $multisite ? Cookiebot_WP::get_network_cbid() : Cookiebot_WP::get_cbid();
		if ( empty( $cbid ) ) {
			return 'empty';
		} elseif ( preg_match( self::CB_FRAME_REGEX, $cbid ) ) {
			return true;
		} else {
			return false;
		}
	}

	public static function get_view_path( $multisite = false, $is_common = false ) {
		if ( self::is_cb_frame_type( $multisite ) === 'empty' || $is_common ) {
			return 'admin/common/';
		} elseif ( self::is_cb_frame_type( $multisite ) === true ) {
			return 'admin/cb_frame/';
		} else {
			return 'admin/uc_frame/';
		}
	}

	/**
	 * Whether the dashboard renders the connected view for this ID: a
	 * Cookiebot ID, or any ID without UC account data. Used by
	 * Dashboard_Page::display() for routing and by Account_Service to skip
	 * the account script there.
	 *
	 * @param string       $cbid      The active ID.
	 * @param array|string $user_data Stored UC account data.
	 * @return bool
	 */
	public static function shows_connected_view( $cbid, $user_data ) {
		if ( empty( $cbid ) ) {
			return false;
		}

		return 1 === preg_match( self::CB_FRAME_REGEX, $cbid ) || empty( $user_data );
	}

	const CB_FRAME_REGEX = '/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/';
}
