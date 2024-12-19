<?php

namespace cybot\cookiebot\settings\pages;

use InvalidArgumentException;
use function cybot\cookiebot\lib\include_view;

class Embeddings_Page implements Settings_Page_Interface {

	/**
	 * @throws InvalidArgumentException
	 */
	public function display() {
		include_view( 'admin/uc_frame/settings/embeddings-page.php' );
	}
}
