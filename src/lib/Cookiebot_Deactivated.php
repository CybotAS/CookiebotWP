<?php
namespace cybot\cookiebot\lib;

use cybot\cookiebot\addons\Cookiebot_Addons;
use Exception;

class Cookiebot_Deactivated {

	/**
	 * @throws Exception
	 */
	public function run() {
		$this->run_addons_deactivation_hooks();
	}

	/**
	 * @throws Exception
	 */
	private function run_addons_deactivation_hooks() {
		$cookiebot_addons = Cookiebot_Addons::instance();
		$cookiebot_addons->cookiebot_deactivated();
	}
}
