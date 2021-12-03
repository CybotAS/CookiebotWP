# How to add a new addon

This document explains how the CookieBot plugin can be expanded with addons to block cookies set by specific third-party WordPress Themes and Plugins.

Addon classes
---
Every addon is contained in its own class.
- All addon classes should be located in a subdirectory of [src/addons/controller/addons](../src/addons/controller/addons)
- Addon classes for third party plugins should extend the `Base_Cookiebot_Plugin_Addon` abstract class.
- Addon classes for third party themes should extend the `Base_Cookiebot_Theme_Addon` abstract class.
- There is also a miscellaneous `Base_Cookiebot_Other_Addon` abstract class, which is used for WordPress core features like embedded videos.

Addons with alternative versions
---
Addons can return a different addon class for each incompatible version.
   - The `ALTERNATIVE_ADDON_VERSIONS` class constant should contain an array of strings.
   - Each array key should correspond to a valid semver version number of the plugin or theme.
   - Each array value should point to the classname of the addon for that previous plugin/theme version.
   - One example is the [Custom_Facebook_Feed](../src/addons/controller/addons/custom_facebook_feed/Custom_Facebook_Feed.php) addon, which had to block its cookies in a different manner for [an older version](../src/addons/controller/addons/custom_facebook_feed/Custom_Facebook_Feed_Version_2_17_1.php)

Steps
---

1. Add a new addon to [src/addons/addons.php](../src/addons/addons.php)
2. Create a directory in [src/addons/controller/addons](../src/addons/controller/addons)
3. Create a class in that new directory (copy class from another addon and adjust the namespace, classname, interfaces and methods.)
4. Edit the `load_addon_configuration` method. This is the only method that needs to be worked on in order to block the cookies.
5. Update all variables and methods according to the addon plugin.
6. Test
7. Create integration test if you did use dependencies from the addon plugin. (We run daily tests to see if the dependencies from the addons plugin are still valid.)
8. Send a pull-request in github

Example
---
1. New addon to [src/addons/addons.php](../src/addons/addons.php)

    ```php
    Litespeed_Cache::class,
	matomo::class,
	Instagram_Feed::class,
    Add_To_Any::class,
    ```

2. Create directory 'add_to_any' in src/addons/controller/addons

3. Create a class 'Add_To_Any' in 'add_to_any' directory (copy class from another addon and rename everything accordingly)

5. Go to 'load_addon_configuration' method. Write your cookie-blocking logic in that function. You can find more information about how to block cookies in [how-to-block-cookies](how-to-block-cookies.md).

6. Test if the cookies are blocked.

7. Create integration test for the addon dependencies: https://github.com/CybotAS/CookiebotAddons/blob/develop/tests/integration/addons/test-add-to-any.php

8. Send a pull-request to our github repository.
