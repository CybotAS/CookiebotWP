<?php
namespace cybot\cookiebot\lib;

use cybot\cookiebot\addons\Cookiebot_Addons;

class Cookiebot_Deactivated {

	public function run() {
		$this->run_addons_deactivation_hooks();
	}

	private function run_addons_deactivation_hooks() {
		$cookiebot_addons = Cookiebot_Addons::instance();
		$cookiebot_addons->cookiebot_deactivated();
	}
}