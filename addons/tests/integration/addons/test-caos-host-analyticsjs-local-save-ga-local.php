<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Caos_Host_Analyticsjs_Local_Save_Ga_Local extends Addons_Base {

	/**
	 * This will validate if the hook "caos_analytics_render_tracking_code" still exists
	 *
	 * @covers \cookiebot_addons\controller\addons\add_to_any\Add_To_Any
	 *
	 * @since 2.1.0
	 */
	public function test_host_analyticsjs_local() {
		$content = $this->curl_get_content( 'http://plugins.svn.wordpress.org/host-analyticsjs-local/tags/1.97/save-ga-local.php' );

		$this->assertNotFalse( strpos( $content,
			'add_action(\'wp_footer\', \'caos_analytics_render_tracking_code\', $sgal_enqueue_order);' ) );
		$this->assertNotFalse( strpos( $content,
			'add_action(\'wp_head\', \'caos_analytics_render_tracking_code\', $sgal_enqueue_order);' ) );
	}
}
