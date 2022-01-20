# Cookiebot upgrade guide

## version 3 to version 4

### Breaking changes
- The `cookiebot_addons_list` filter hook was renamed to `cybot_cookiebot_addons_list` and the initial/expected value has been restructured.
- The `cookiebot_addons_language` filter hook was renamed to `cybot_cookiebot_addons_language`.
- The `cookiebot_addons_embed_source` filter hook was renamed to `cybot_cookiebot_addons_embed_source`.
- The `cookiebot_addons_embed_placeholder` filter hook was renamed to `cybot_cookiebot_addons_embed_placeholder`.
- The `cookiebot_embed_regex` filter hook was renamed to `cybot_cookiebot_embed_regex`.
- The `cookiebot_embed_default_regex` filter hook was renamed to `cybot_cookiebot_embed_default_regex`.

### Deprecations

### non-namespaced globals
PHP namespaces were not used in the Cookiebot plugin before version 4.
The following globals are now deprecated and will be removed in the next major version update.
Please use the namespaced version instead when supporting the Cookiebot plugin in your own plugin, or when implementing a new add-on.

- class `\Cookiebot_WP` (The plugin's main class)
  - moved to namespace: `cybot\cookiebot\lib\Cookiebot_WP` [src/lib/Cookiebot_WP.php](../src/lib/Cookiebot_WP.php)
  - deprecation: `\Cookiebot_WP` [src/lib/global-deprecations.php](../src/lib/global-deprecations.php)
- function `\cookiebot_assist` (Helper function to update your scripts)
  - moved to namespace: `cybot\cookiebot\lib\cookiebot_assist` [src/lib/helper.php](../src/lib/helper.php)
  - deprecation: `\cookiebot_assist` [src/lib/global-deprecations.php](../src/lib/global-deprecations.php)
- function `\cookiebot_active` (Helper function to check if cookiebot is active. Useful for other plugins adding support for Cookiebot.)
  - moved to namespace: `cybot\cookiebot\lib\cookiebot_active` [src/lib/helper.php](../src/lib/helper.php)
  - deprecation: `\cookiebot_active` [src/lib/global-deprecations.php](../src/lib/global-deprecations.php)
- function `\cookiebot` (Returns the main instance of Cookiebot_WP to prevent the need to use globals.)
  - moved to namespace: `cybot\cookiebot\lib\cookiebot` [src/lib/helper.php](../src/lib/helper.php)
  - deprecation: `\cookiebot` [src/lib/global-deprecations.php](../src/lib/global-deprecations.php)
