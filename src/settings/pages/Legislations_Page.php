<?php

namespace cybot\cookiebot\settings\pages;

use InvalidArgumentException;
use function cybot\cookiebot\lib\include_view;

class Legislations_Page implements Settings_Page_Interface {

	public function menu() {
		add_submenu_page(
			'cookiebot',
			__( 'Legislations', 'cookiebot' ),
			__( 'Legislations', 'cookiebot' ),
			'manage_options',
			'cookiebot-legislations',
			array( $this, 'display' ),
			50
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function display() {
		include_view( 'admin/settings/legislations-page.php', array() );
	}
}
