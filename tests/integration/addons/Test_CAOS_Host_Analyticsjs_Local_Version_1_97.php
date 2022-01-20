<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\caos_host_analyticsjs_local\CAOS_Host_Analyticsjs_Local_Version_1_97;
use WP_UnitTestCase;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class Test_CAOS_Host_Analyticsjs_Local_Version_1_97 extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\caos_host_analyticsjs_local\CAOS_Host_Analyticsjs_Local_Version_1_97
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_is_plugin_compatible() {
		$content = CAOS_Host_Analyticsjs_Local_Version_1_97::get_svn_file_content();

		$this->assertNotFalse(
			strpos(
				$content,
				'add_action(\'wp_footer\', \'caos_analytics_render_tracking_code\', $sgal_enqueue_order);'
			)
		);
		$this->assertNotFalse(
			strpos(
				$content,
				'add_action(\'wp_head\', \'caos_analytics_render_tracking_code\', $sgal_enqueue_order);'
			)
		);
	}
}
