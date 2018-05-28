# Cookiebot Wordpress Addons


Cookiebot Addons are plugins for Wordpress that make other plugins compatible with Cookiebot. 
The addons hook into the original plugin and render the cookie setting tags as advised by the Cookiebot guidelines at https://www.cookiebot.com/goto/help/.

Concurrently we are working with WP Core on what we believe is the real solution. A framework in WP Core that can signal the consent state to other plugins,
so that they can handle their cookie setting code without explicit support for Cookiebot, or other cookie plugins. If and when this will be implemented is unknown.

https://core.trac.wordpress.org/ticket/44043 

# News on GADWP
GADWP is about to release a GDPR compliance addon, which supports Cookiebot. We'll provide a link here once the addon is released. 

# Installation
1. Copy the framework plugin to your WP plugins folder
2. Go to the admin page of your WP installation and activate the plugin
3. You are done, verify that it works

# Roadmap

Following plugins have native (built-in) support for Cookiebot:
* [MonsterInsights](https://www.monsterinsights.com/addon/eu-compliance/)
* [PixelYourSite](https://wordpress.org/plugins/pixelyoursite/)

Released and tested addons:

* [GA Google Analytics](https://wordpress.org/plugins/ga-google-analytics/)
* [Google Analyticator](https://wordpress.org/plugins/google-analyticator/)
* [Jetpack by Wordpress.com](https://wordpress.org/plugins/jetpack/)
* [HubSpot Tracking Code](https://wordpress.org/plugins/hubspot-tracking-code/)
* [AddToAny Share Buttons](https://wordpress.org/plugins/add-to-any/)

Following addons are in pipeline:

* Autocorrection of embedded YouTube and Vimeo videos
* [Analytify](https://wordpress.org/plugins/wp-analytify/)
* [Custom Facebook Feed from Smashballoon](https://da.wordpress.org/plugins/custom-facebook-feed/)
* [Facebook for WooCommerce](https://woocommerce.com/products/facebook/)
* [WD Google Analytics](https://wordpress.org/plugins/wd-google-analytics/)
* [WP-Matomo](https://nl.wordpress.org/plugins/wp-piwik/)
* [Google Analytics +](https://premium.wpmudev.org/project/google-analytics-for-wordpress-mu-sitewide-and-single-blog-solution/)
* To be continued..


If you have a plugin that you would like integration for, please submit a request in the [Issues](https://github.com/CybotAS/CookiebotAddons/issues) section.

# Contributions
Everyone is welcome to make a pull request with new addon support, or to fix existing addons.

Shout out to

[@fschaeffler](https://github.com/fschaeffler) for the HubSpot Tracking Code integration. 

[@irondan](https://github.com/irondan) for the AddToAny integration.

Way to go!


# How do I make my plugin support Cookiebot?
See [the Cookiebot API readme for more details about the Cookiebot API](CookiebotAPI.md)

# Need to get in touch?

There are several ways you can get in touch with us. <br>
We are available on the Making Wordpress Slack workspace. <br>
Username: Kenan <br>
You can also reach us through our helpdesk at www.cookiebot.com/goto/helpdesk/