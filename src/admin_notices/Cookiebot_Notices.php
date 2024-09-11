<?php

namespace cybot\cookiebot\admin_notices;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Cookiebot_Notices {
	/**
	 * @var array
	 */
	private $notices_list = array();

	public function __construct() {
		$this->load_notices();
		$this->build_notices();
	}

	protected function load_notices() {
		$this->notices_list = self::PLUGIN_NOTICES;
	}

	private function build_notices() {
		foreach ( $this->notices_list as $notice_class ) {
			( new $notice_class() )->register_hooks();
		}
	}

	const PLUGIN_NOTICES = array(
		Cookiebot_Recommendation_Notice::class,
		Cookiebot_Temp_Notice::class,
	);
}
