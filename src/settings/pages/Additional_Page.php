<?php

namespace cybot\cookiebot\settings\pages;

use cybot\cookiebot\lib\Cookiebot_Frame;
use cybot\cookiebot\lib\Cookiebot_WP;
use cybot\cookiebot\lib\Supported_Languages;
use function cybot\cookiebot\lib\asset_url;
use function cybot\cookiebot\lib\cookiebot_get_language_from_setting;
use function cybot\cookiebot\lib\include_view;

class Additional_Page implements Settings_Page_Interface {

	public function display() {
		$args = array(
			'is_ms'                     => is_multisite(),
			'network_scrip_tag_cd_attr' => get_site_option( 'cookiebot-script-tag-cd-attribute', 'custom' ),

		);

		include_view( Cookiebot_Frame::get_view_path() . 'settings/additional-page.php', $args );
	}
}
