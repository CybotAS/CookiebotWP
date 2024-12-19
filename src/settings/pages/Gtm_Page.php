<?php

namespace cybot\cookiebot\settings\pages;

use cybot\cookiebot\lib\Cookiebot_Frame;
use cybot\cookiebot\lib\Cookiebot_WP;
use InvalidArgumentException;
use function cybot\cookiebot\lib\include_view;

class Gtm_Page implements Settings_Page_Interface {

	const OPTION_NAME = 'cookiebot-gtm-cookies';

	/**
	 * @throws InvalidArgumentException
	 */
	public function display() {
		$args = array(
			'cookie_categories_disabled' => Cookiebot_WP::get_cookie_categories_status(),
			'auto_disabled'              => Cookiebot_WP::get_cookie_blocking_mode() === 'auto' ? ' disabled__item' : '',
			'is_preferences'             => Cookiebot_WP::is_cookie_category_selected( self::OPTION_NAME, 'preferences' ),
			'is_statistics'              => Cookiebot_WP::is_cookie_category_selected( self::OPTION_NAME, 'statistics' ),
			'is_marketing'               => Cookiebot_WP::is_cookie_category_selected( self::OPTION_NAME, 'marketing' ),
		);

		include_view( Cookiebot_Frame::get_view_path() . 'settings/gtm-page.php', $args );
	}
}
