<?php

namespace cybot\cookiebot\settings\pages;

use cybot\cookiebot\lib\Cookiebot_Frame;
use cybot\cookiebot\lib\Cookiebot_WP;
use function cybot\cookiebot\lib\include_view;

class General_Page implements Settings_Page_Interface {
	public function display() {
		$args = array(
		);

		include_view( Cookiebot_Frame::get_view_path() . 'settings/general-page.php', $args );
	}
}