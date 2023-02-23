# Cookiebot CMP WordPress Plugin
Cookiebot Wordpress plugin is a plugin that make other plugins compatible with Cookiebot. 
The addons hook into the original plugin and render the cookie setting tags as advised by the Cookiebot guidelines at https://www.cookiebot.com/en/help/.

Concurrently we are working with WP Core on what we believe is the real solution. A framework in WP Core that can signal the consent state to other plugins,
so that they can handle their cookie setting code without explicit support for Cookiebot, or other cookie plugins. If and when this will be implemented is unknown.

https://core.trac.wordpress.org/ticket/44043 

# Travis CI Status 

[![Build Status](https://travis-ci.com/CybotAS/CookiebotWP.svg?branch=master)](https://app.travis-ci.com/github/CybotAS/CookiebotWP)

# Sonarcloud status

[![Security](https://sonarcloud.io/api/project_badges/measure?project=CybotAS_CookiebotWP&metric=security_rating&token=c3f37bd6b25de5ce675c50dd1bfe06716d3448e3)](https://sonarcloud.io/summary/new_code?id=CybotAS_CookiebotWP)
[![Reliability](https://sonarcloud.io/api/project_badges/measure?project=CybotAS_CookiebotWP&metric=reliability_rating&token=c3f37bd6b25de5ce675c50dd1bfe06716d3448e3)](https://sonarcloud.io/summary/new_code?id=CybotAS_CookiebotWP)
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=CybotAS_CookiebotWP&metric=bugs&token=c3f37bd6b25de5ce675c50dd1bfe06716d3448e3)](https://sonarcloud.io/summary/new_code?id=CybotAS_CookiebotWP)
[![Maintainability](https://sonarcloud.io/api/project_badges/measure?project=CybotAS_CookiebotWP&metric=sqale_rating&token=c3f37bd6b25de5ce675c50dd1bfe06716d3448e3)](https://sonarcloud.io/summary/new_code?id=CybotAS_CookiebotWP)
[![Vulnerabilities](https://sonarcloud.io/api/project_badges/measure?project=CybotAS_CookiebotWP&metric=vulnerabilities&token=c3f37bd6b25de5ce675c50dd1bfe06716d3448e3)](https://sonarcloud.io/summary/new_code?id=CybotAS_CookiebotWP)

Table of contents
=================

<!--ts-->
   * [Installation](#installation)
   * [How do I make my plugin support Cookiebot?](#how-do-i-make-my-plugin-support-cookiebot)
   * [Roadmap](#roadmap)
   * [Contributions](#contributions)
   * [Need to get in touch?](#need-to-get-in-touch)
<!--te-->

# Installation
Regular users should use [Cookiebot WordPress plugin](https://wordpress.org/plugins/cookiebot) which includes Cookiebot Addons.

If you want the most recent changes get the [latest release](https://github.com/CybotAS/CookiebotAddons/releases/latest) of the plugin and upliad it to your WP plugins folder

# How do I make my plugin support Cookiebot?
See [the Cookiebot API readme for more details about the Cookiebot API](documentation/CookiebotAPI.md)

See [How to block cookies](documentation/how-to-block-cookies.md)

See [How to add new addon](documentation/how-to-add-new-addon.md)

See [Admin UI](documentation/admin-ui.md)

# Roadmap

Following plugins have native (built-in) support for Cookiebot:
* [MonsterInsights](https://www.monsterinsights.com/addon/eu-compliance/) (only in the Plus / Pro version)
* [PixelYourSite](https://wordpress.org/plugins/pixelyoursite/)

Released and tested addons:

* Autocorrection of embedded Facebook, Twitter, Youtube and Vimeo videos
* [AddToAny Share Buttons](https://wordpress.org/plugins/add-to-any/)
* [Analytify](https://wordpress.org/plugins/wp-analytify/)
* [Custom Facebook Feed from Smashballoon](https://da.wordpress.org/plugins/custom-facebook-feed/)
* [GA Google Analytics](https://wordpress.org/plugins/ga-google-analytics/)
* [Google Analyticator](https://wordpress.org/plugins/google-analyticator/)
* [Google Analytics +](https://premium.wpmudev.org/project/google-analytics-for-wordpress-mu-sitewide-and-single-blog-solution/)
* [HubSpot Tracking Code](https://wordpress.org/plugins/hubspot-tracking-code/)
* [Jetpack by WordPress.com](https://wordpress.org/plugins/jetpack/)
  * Supported widgets:
    * Google Maps
    * Facebook
    * Google Plus Badge
    * Internet Defense League	
    * Twitter Timeline	
    * Goodreads
* [Pixel Caffeine]( https://wordpress.org/plugins/pixel-caffeine/)
* [WD Google Analytics](https://wordpress.org/plugins/wd-google-analytics/)
* [WP-Matomo](https://wordpress.org/plugins/wp-piwik/)
* [Complete Analytics Optimization Suite (CAOS)](https://wordpress.org/plugins/host-analyticsjs-local/)
* [Facebook for WooCommerce](https://woocommerce.com/products/facebook/)
* [Googleanalytics](https://wordpress.org/plugins/googleanalytics/)
* [HubSpot – Free Marketing Plugin for WordPress](https://wordpress.org/plugins/leadin/)
* [Ninja forms](https://wordpress.org/plugins/ninja-forms/)
* [Popups by OptinMonster](https://wordpress.org/plugins/optinmonster/)
* [Google Analytics Dashboard for WP](https://wordpress.org/plugins/google-analytics-dashboard-for-wp/)

Following addons are in pipeline:
* To be continued..


If you have a plugin that you would like integration for, please submit a request in the [Issues](https://github.com/CybotAS/CookiebotAddons/issues) section.

# Contributions
Everyone is welcome to make a pull request with new addon support, or to fix existing addons.

Shout out to

[@fschaeffler](https://github.com/fschaeffler) - HubSpot Tracking Code 

[@irondan](https://github.com/irondan) - AddToAny

[@Jursdotme](https://github.com/Jursdotme) - Custom Facebook Feed from Smashballoon

[@MarcZijderveld](https://github.com/MarcZijderveld) - Popups by OptinMonster

Way to go!

# Need to get in touch?

There are several ways you can get in touch with us. <br>
We are available on GitHub and [WordPress.org](https://wordpress.org/support/plugin/cookiebot/) <br>
You can also reach us through our helpdesk at www.cookiebot.com/en/helpdesk/
