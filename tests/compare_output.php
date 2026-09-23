<?php

require_once __DIR__ . '/mock_wp_functions.php';
require_once __DIR__ . '/../src/lib/script_loader_tag/Script_Loader_Tag_Interface.php';
require_once __DIR__ . '/../src/lib/script_loader_tag/Script_Loader_Tag.php';
require_once __DIR__ . '/../src/lib/helper.php';
require_once __DIR__ . '/old_logic.php';

use cybot\cookiebot\lib\script_loader_tag\Script_Loader_Tag;
use cybot\cookiebot\lib\script_loader_tag\Script_Loader_Tag_Old;
use function cybot\cookiebot\lib\cookiebot_addons_manipulate_script;
use function cybot\cookiebot\lib\cookiebot_addons_manipulate_script_old;

echo "Running Comparison Tests (New vs Old Logic)...\n\n";

$passes = 0;
$fails = 0;

function normalize_html($html) {
    // Normalize whitespace
    $html = preg_replace('/\s+/', ' ', trim($html));
    // Normalize quotes (DOMDocument uses double quotes)
    $html = str_replace("'", '"', $html);
    // Normalize self-closing tags (DOMDocument expands <script /> to <script></script>)
    $html = preg_replace('/<script([^>]*)>\s*<\/script>/', '<script$1></script>', $html);
    // Remove spaces around =
    $html = preg_replace('/\s*=\s*/', '=', $html);
    
    // Sort attributes to handle order differences
    // This is a naive implementation but should work for simple cases
    $html = preg_replace_callback('/<script([^>]*)>/', function($matches) {
        $attrs = $matches[1];
        preg_match_all('/(\w+)(?:="([^"]*)")?/', $attrs, $attr_matches, PREG_SET_ORDER);
        $sorted_attrs = [];
        foreach ($attr_matches as $m) {
            $name = $m[1];
            $value = isset($m[2]) ? $m[2] : '';
            $sorted_attrs[$name] = $value;
        }
        ksort($sorted_attrs);
        $new_attrs = '';
        foreach ($sorted_attrs as $k => $v) {
            if ($v === '') {
                $new_attrs .= " $k";
            } else {
                $new_attrs .= " $k=\"$v\"";
            }
        }
        return "<script$new_attrs>";
    }, $html);

    return $html;
}

function assert_equivalent($new, $old, $test_name) {
    global $passes, $fails;
    
    $new_norm = normalize_html($new);
    $old_norm = normalize_html($old);
    
    if ($new_norm === $old_norm) {
        echo "[PASS] $test_name\n";
        $passes++;
    } else {
        echo "[FAIL] $test_name\n";
        echo "  Old (Norm): $old_norm\n";
        echo "  New (Norm): $new_norm\n";
        echo "  Old (Raw):  $old\n";
        echo "  New (Raw):  $new\n";
        $fails++;
    }
}

// --- Test Suite 1: helper.php cookiebot_addons_manipulate_script ---

echo "Testing cookiebot_addons_manipulate_script (helper.php)...\n";

$keywords = ['tracking.js' => 'marketing'];

// Case 1: Simple Script
$input = '<script src="tracking.js"></script>';
$new = cookiebot_addons_manipulate_script($input, $keywords);
$old = cookiebot_addons_manipulate_script_old($input, $keywords);
assert_equivalent($new, $old, 'Simple Script');

// Case 2: Script with existing attributes
$input = '<script async defer src="tracking.js"></script>';
$new = cookiebot_addons_manipulate_script($input, $keywords);
$old = cookiebot_addons_manipulate_script_old($input, $keywords);
assert_equivalent($new, $old, 'Attributes');

// Case 3: Script with existing type
$input = '<script type="text/javascript" src="tracking.js"></script>';
$new = cookiebot_addons_manipulate_script($input, $keywords);
$old = cookiebot_addons_manipulate_script_old($input, $keywords);
assert_equivalent($new, $old, 'Existing Type');

// Case 4: Multiple scripts in buffer
$input = '<div></div><script src="other.js"></script><script src="tracking.js"></script>';
$new = cookiebot_addons_manipulate_script($input, $keywords);
$old = cookiebot_addons_manipulate_script_old($input, $keywords);
assert_equivalent($new, $old, 'Multiple Scripts');

// Case 5: Fragment (no html/body)
$input = '<script src="tracking.js"></script>';
$new = cookiebot_addons_manipulate_script($input, $keywords);
$old = cookiebot_addons_manipulate_script_old($input, $keywords);
assert_equivalent($new, $old, 'Fragment');

// --- Test Suite 2: Script_Loader_Tag ---

echo "\nTesting Script_Loader_Tag...\n";

$loader_new = new Script_Loader_Tag();
$loader_new->ignore_script('ignore-me.js');

$loader_old = new Script_Loader_Tag_Old();
$loader_old->ignore_script('ignore-me.js');

// Case 6: Ignored Script
$tag = '<script id="ignore-me-js" src="https://example.com/ignore-me.js"></script>';
$handle = 'ignore-me';
$src = 'https://example.com/ignore-me.js';

$new = $loader_new->cookiebot_add_consent_attribute_to_tag($tag, $handle, $src);
$old = $loader_old->cookiebot_add_consent_attribute_to_tag($tag, $handle, $src);
assert_equivalent($new, $old, 'Ignored Script');

// Case 7: Non-ignored script
$tag = '<script src="https://example.com/normal.js"></script>';
$handle = 'normal';
$src = 'https://example.com/normal.js';

$new = $loader_new->cookiebot_add_consent_attribute_to_tag($tag, $handle, $src);
$old = $loader_old->cookiebot_add_consent_attribute_to_tag($tag, $handle, $src);
assert_equivalent($new, $old, 'Non-ignored script');

echo "\nSummary: $passes Passed, $fails Failed.\n";
if ($fails > 0) {
    exit(1);
}
