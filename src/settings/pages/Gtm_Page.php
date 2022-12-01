<?php

namespace cybot\cookiebot\settings\pages;

use cybot\cookiebot\lib\Cookiebot_WP;
use InvalidArgumentException;
use function cybot\cookiebot\lib\asset_url;
use function cybot\cookiebot\lib\include_view;

class Gtm_Page implements Settings_Page_Interface {



	/**
	 * @throws InvalidArgumentException
	 */
	public function display() {
		include_view( 'admin/settings/gtm-page.php', array() );
	}
}
