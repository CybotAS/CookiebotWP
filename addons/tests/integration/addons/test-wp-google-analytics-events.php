<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Wp_Google_Analytics_Events extends Addons_Base {

	public function setUp() {}

	/**
	 * This will validate if the hooks for "wp_google_analytics_events" still exists
	 *
	 * @since 2.1.0
	 */
	public function test_wd_google_analytics() {
		$content = $this->curl_get_content( 'https://plugins.svn.wordpress.org/wp-google-analytics-events/trunk/ga-scroll-event.php' );

		$include_snippet = <<<TEXT
add_action('wp_head',
        array('GAESnippets', 'add_snippet_to_header'),
        0);
TEXT;

		$required_code_snippets = array(
			"wp_register_script('ga_events_frontend_bundle',",
			"wp_localize_script('ga_events_frontend_bundle',",
			"wp_enqueue_script('ga_events_frontend_bundle');",
			"wp_register_script('ga_events_main_script',",
			"wp_localize_script('ga_events_main_script',",
			"wp_enqueue_script('ga_events_main_script');",
			$include_snippet,
		);

		foreach ( $required_code_snippets as $required_code_snippet ) {
			$this->assertNotFalse( strpos( $content, $required_code_snippet ) );
		}
	}
}
