<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Custom_Facebook_Feed extends Addons_Base {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hook "caos_analytics_render_tracking_code" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_host_analyticsjs_local() {
		$inc_Custom_Facebook_Feed_content = $this->curl_get_content( 'http://plugins.svn.wordpress.org/custom-facebook-feed/trunk/custom-facebook-feed.php' );

		$this->assertNotFalse( strpos( $inc_Custom_Facebook_Feed_content, 'echo \'var cfflinkhashtags = "\' . $cff_link_hashtags . \'";\';') );
		$this->assertNotFalse( strpos( $content, <<<EOD
	    wp_register_script(
	    	'cffscripts',
	    	CFF_PLUGIN_URL . 'assets/js/cff-scripts'.$cff_min.'.js' ,
	    	array('jquery'),
	    	CFFVER,
	    	true
	    );
EOD
) );
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_footer\', [ self::$instance, \'cff_js\' ] );') );
	}
}