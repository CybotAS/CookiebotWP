<?php

namespace cookiebot_addons_framework\lib;

Interface Cookiebot_Settings_Interface {

	/**
	 * Returns true if the addon is enabled in the backend
	 *
	 * @param $addon
	 *
	 * @return mixed
	 *
	 * @since 1.3.0
	 */
	public function is_addon_enabled( $addon );

}