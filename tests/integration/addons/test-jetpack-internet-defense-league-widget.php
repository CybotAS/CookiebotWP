<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Jetpack_Internet_Defense_League_Widget extends \WP_UnitTestCase {
	
	public function setUp() {
	
	}
	
	/**
	 * This will validate if the hook "jetpack internet defense league widget" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_internet_defense_league_widget() {
		$content = file_get_contents('http://plugins.svn.wordpress.org/jetpack/trunk/modules/widgets/internet-defense-league.php');
		
		$this->assertNotFalse( strpos( $content, 'add_action( \'wp_footer\', array( $this, \'footer_script\' ) );' ) );
	}
}