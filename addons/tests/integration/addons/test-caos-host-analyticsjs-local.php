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
		$content = file_get_contents( 'http://plugins.svn.wordpress.org/host-analyticsjs-local/trunk/includes/frontend/class-tracking.php' );

		$this->assertNotFalse( strpos( $content,
			'add_action(\'wp_footer\', array($this, \'render_tracking_code\'), CAOS_OPT_ENQUEUE_ORDER);' ) );
			
		$this->assertNotFalse( strpos( $content,
			'switch (CAOS_OPT_SCRIPT_POSITION) {' ) );
	}
}
