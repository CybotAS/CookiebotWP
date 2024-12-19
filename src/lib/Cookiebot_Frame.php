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

	const CB_FRAME_REGEX = '/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/';
}
