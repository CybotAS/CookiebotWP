# Cookiebot Wordpress Addons


Cookiebot Addons are plugins for Wordpress that make other plugins, such as MonsterInsights or GADWP, compatible with Cookiebot. 
The addons hook into the original plugin and render the cookie setting tags as advised by the Cookiebot guidelines at https://www.cookiebot.com/goto/help/.

# Installation
1. Copy the plugin of your choice to your WP plugins folder
2. Go to the admin page of your WP installation and activate the plugin
3. You are done, verify that it works

# How do I make other plugins support Cookiebot?
If you favourite plugins doesn’t support Cookiebot you are always welcome to ask the author to add support for Cookiebot.
Cookiebot provides a helper function to check if there is an active, working version of Cookiebot on the website.
The easiest way for at developer to implement Cookiebot support is to add a check for Cookiebot where tags are outputted to the visitor. 

This can be done following way;

```php
$scriptTag = ";
if(function_exists('cookiebot_active') && cookiebot_active()) {
$scriptTag = '<script'.cookiebot_assist('statistics').'>';
}
```

The developer of the plugin can see more details on [Cookiebot Help Step 3](https://www.cookiebot.com/goto/help/).