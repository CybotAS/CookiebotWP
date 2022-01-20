<?php

namespace cybot\cookiebot\addons\controller\addons;

abstract class Base_Cookiebot_Other_Addon extends Base_Cookiebot_Addon {

	/**
	 * @return bool
	 */
	final public function is_addon_installed() {
		return true;
	}

	/**
	 * @return bool
	 */
	final public function is_addon_activated() {
		return true;
	}
}
