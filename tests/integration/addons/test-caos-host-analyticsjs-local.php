<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Caos_Host_Analyticsjs_Local extends \WP_UnitTestCase {

	/**
	 * This will validate if the hook "caos_analytics_render_tracking_code" still exists
	 *
	 * @covers \cookiebot_addons\controller\addons\add_to_any\Add_To_Any
	 *
	 * @since 2.1.0
	 */
	public function test_host_analyticsjs_local() {
		$content = file_get_contents( 'http://plugins.svn.wordpress.org/host-analyticsjs-local/trunk/host-analyticsjs-local.php' );

		$this->assertNotFalse( strpos( $content,
			'add_action(\'wp_footer\', \'caos_analytics_render_tracking_code\', $sgal_enqueue_order);' ) );
		$this->assertNotFalse( strpos( $content,
			'add_action(\'wp_head\', \'caos_analytics_render_tracking_code\', $sgal_enqueue_order);' ) );
	}
}
