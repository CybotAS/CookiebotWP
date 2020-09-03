<?php

namespace cookiebot_addons\tests\integration\addons;

class Test_Google_Analytics_Async extends Addons_Base
{

    public function setUp()
    {

    }

    /**
     * This will validate if the hook for "google_analytics_async" still exists
     *
     * @since 2.1.0
     */
    public function test_hooks()
    {
        $file = WP_PLUGIN_DIR . '/google-analytics-async/google-analytics-async.php';

        $needle = $content = 'add_action( \'wp_head\', array( &$this, \'tracking_code_output\' ) );';
        if (file_exists($file)) {
            $content = file_get_contents($file);
        }
        $this->assertNotFalse(strpos($content, $needle));
    }
}