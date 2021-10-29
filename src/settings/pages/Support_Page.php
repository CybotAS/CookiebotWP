<?php

namespace cybot\cookiebot\settings\pages;

use InvalidArgumentException;
use function cybot\cookiebot\lib\include_view;

class Support_Page implements Settings_Page_Interface {

	public function menu() {
		add_submenu_page(
			'cookiebot',
			__( 'Cookiebot Support', 'cookiebot' ),
			__( 'Support', 'cookiebot' ),
			'manage_options',
			'cookiebot_support',
			array( $this, 'display' ),
			20
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function display() {
		include_view( 'admin/settings/support-page.php', array() );
	}
}
