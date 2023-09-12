<?php

namespace cybot\cookiebot\settings\pages;

use InvalidArgumentException;
use function cybot\cookiebot\lib\include_view;

class Iab_Page implements Settings_Page_Interface {

	public function menu() {
		add_submenu_page(
			'cookiebot',
			__( 'IAB', 'cookiebot' ),
			__( 'IAB', 'cookiebot' ),
			'manage_options',
			'cookiebot_iab',
			array( $this, 'display' ),
			30
		);
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function display() {
		$args = array(
			'cookiebot_iab'         => get_option( 'cookiebot-iab' ),
			'cookiebot_tcf_version' => empty( get_option( 'cookiebot-tcf-version' ) ) ? 'IAB' : get_option( 'cookiebot-tcf-version' ),
		);

		include_view( 'admin/settings/iab-page.php', $args );
	}
}
