<?php

namespace cybot\cookiebot\tests\integration\addons;

use WP_UnitTestCase;
use cybot\cookiebot\addons\controller\addons\caos_host_analyticsjs_local\CAOS_Host_Analyticsjs_Local;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class Test_Caos_Host_Analyticsjs_Local extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\caos_host_analyticsjs_local\CAOS_Host_Analyticsjs_Local
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_is_plugin_compatible() {
		$content = CAOS_Host_Analyticsjs_Local::get_svn_file_content( 'includes/frontend/class-tracking.php' );

		$this->assertNotFalse(
			strpos(
				$content,
				'add_filter(\'woocommerce_google_analytics_script_src\''
			)
		);

		$this->assertNotFalse(
			strpos(
				$content,
				'\'render_tracking_code\']'
			)
		);
	}
}
