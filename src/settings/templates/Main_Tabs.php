<?php

namespace cybot\cookiebot\settings\templates;

use InvalidArgumentException;
use function cybot\cookiebot\lib\asset_url;
use function cybot\cookiebot\lib\include_view;

class Main_Tabs {

	/**
	 * @throws InvalidArgumentException
	 */
	public function display( $active ) {
		$args = array(
			'active_tab' => $active,
		);

		include_view( 'admin/templates/main-tabs.php', $args );
	}
}
