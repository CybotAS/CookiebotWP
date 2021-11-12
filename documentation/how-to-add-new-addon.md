# How to add new addon

Steps
---

1. Add new addon to src/addons/addons.php
2. Create a directory in src/addons/controller/addons
3. Create a class in that new directory (copy class from another addon and adjust the namespace, classname and methods.)
4. Edit 'load_addon_configuration' method. That is the only method that needs to be worked on to block the cookies.
5. Update all variables and methods according to the addon plugin.
6. Test
7. Create integration test if you did use dependencies from the addon plugin. (We run daily tests to see if the dependencies from the addons plugin are still valid.)
8. Send a pull-request in github

Example
---
1. New addon to addons.php

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
