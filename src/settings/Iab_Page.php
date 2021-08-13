<?php

namespace cybot\cookiebot\settings;

use function cybot\cookiebot\addons\lib\include_view;

class Iab_Page implements Page_Interface {

	public function display() {
		include_view( 'admin/settings/iab-page.php', array() );
	}
}