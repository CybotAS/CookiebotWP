<?php

namespace cybot\cookiebot\tests\integration\addons;

use cybot\cookiebot\addons\controller\addons\wp_google_analytics_events\Wp_Google_Analytics_Events;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use WP_UnitTestCase;

class Test_Wp_Google_Analytics_Events extends WP_UnitTestCase {

	/**
	 * @covers \cybot\cookiebot\addons\controller\addons\wp_google_analytics_events\Wp_Google_Analytics_Events
	 * @throws ExpectationFailedException
	 * @throws InvalidArgumentException
	 * @throws \Exception
	 */
	public function test_is_plugin_compatible() {
		$content = Wp_Google_Analytics_Events::get_svn_file_content();

		$wp_head_snippet = <<<TEXT
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
			$wp_head_snippet,
		);

		foreach ( $required_code_snippets as $required_code_snippet ) {
			$this->assertNotFalse( strpos( $content, $required_code_snippet ) );
		}
	}
}
