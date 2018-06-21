# How to add new addon

Cookiebot Addons Framework uses a specific way to implement new addons. In this documentation you will find the instructions to add a new addon to the framework.

The steps you need to take to add new addon
---

1. Add new addon to addons.json
2. Create a directory in controller/addons
3. Create a class in that new directory
4. Copy class from another addon and adjust the namespace, classname and methods.
5. Edit 'load_configuration' method. That is the only method that needs to be worked on to block the cookies. You can create your own method from there.
6. Test
7. Send a pull-request in github

For example
---
1. New addon to addons.json

    ```json
    "Add_To_Any": {
        "class": "cookiebot_addons_framework\\controller\\addons\\add_to_any\\Add_To_Any"
      },
    ```

2. Create directory 'add_to_any' in controller/addons

3. Create a class 'Add_To_Any' in 'add_to_any' directory

4. Copy class from another addon and rename everything accordingly

5. Go to 'load_configuration' method and rename the callback function to make it more sense. Then work in that function to block the cookies. You can find more information about how to block cookies in [this documentation](how-to-block-cookies.md).

6. Test if the cookies are blocked.

7. Send a pull-request to our github repository.
