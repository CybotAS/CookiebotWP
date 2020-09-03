<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Caos_Host_Analyticsjs_Local extends Addons_Base {

	/**
	 * This will validate if the hook "caos_analytics_render_tracking_code" still exists
	 *
	 * @covers \cookiebot_addons\controller\addons\add_to_any\Add_To_Any
	 *
	 * @since 2.1.0
	 */
	public function test_host_analyticsjs_local() {
		$content = $this->curl_get_content( 'http://plugins.svn.wordpress.org/host-analyticsjs-local/trunk/includes/frontend/class-tracking.php' );

		$this->assertNotFalse( strpos( $content,
			'add_filter(\'woocommerce_google_analytics_script_src\'' ) );
			
		$this->assertNotFalse( strpos( $content,
			'\'render_tracking_code\']' ) );
	}
}
