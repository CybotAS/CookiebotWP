<?php

namespace cybot\cookiebot\tests\integration\addons;

use WP_UnitTestCase;
use cybot\cookiebot\addons\controller\addons\caos_host_analyticsjs_local\CAOS_Host_Analyticsjs_Local;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use function cybot\cookiebot\tests\remote_get_svn_contents;

class Test_Caos_Host_Analyticsjs_Local extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\caos_host_analyticsjs_local\CAOS_Host_Analyticsjs_Local
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 */
	public function test_is_plugin_compatible() {
		$content = remote_get_svn_contents( CAOS_Host_Analyticsjs_Local::get_svn_url( 'includes/frontend/class-tracking.php' ) );

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
