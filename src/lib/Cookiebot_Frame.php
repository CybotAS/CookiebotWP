<?php
namespace cybot\cookiebot\lib;

class Cookiebot_Frame {
	public static function is_cb_frame_type(){
		$cbid = Cookiebot_WP::get_cbid();
		if (empty($cbid)){
			return 'empty';
		}elseif (preg_match(Cookiebot_Frame::CB_FRAME_REGEX, $cbid)) {
			return true;
		}else{
			return false;
		}
	}

	public static function get_view_path($is_common = false){
		if(Cookiebot_Frame::is_cb_frame_type() === 'empty' || $is_common){
			return 'admin/common/';
		}elseif (Cookiebot_Frame::is_cb_frame_type() === true){
			return 'admin/cb_frame/';
		}else{
			return 'admin/uc_frame/';
		}
	}

	const CB_FRAME_REGEX = '/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/';
}