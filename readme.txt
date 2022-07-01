# Cookiebot | GDPR/CCPA Compliant Cookie Consent and Control #
* Contributors: cookiebot,phpgeekdk,aytac
* Tags: cookie, compliance, eu, gdpr, europe, cookie consent, consent, ccpa
* Requires at least: 4.4
* Tested up to: 6.0.0
* Stable tag: 4.1.1
* Requires PHP: 5.6
* License: GPLv2 or later

Cookiebot is a cloud-driven solution that automatically controls cookies and trackers, enabling full GDPR/ePrivacy and CCPA compliance for websites.

## Description ##

### Trustworthy and simple cookie consent solution for GDPR/ePR and CCPA compliance ###

Cookiebot is a cloud-driven solution that automatically controls cookies and trackers, enabling full GDPR/ePR and CCPA compliance and offering:

* A highly customizable consent banner to handle consents and give the user the possibility to opt in and out of cookie categories, as required by the GDPR/ePR, CCPA and similar legislations around the world.
* An automatically generated and updated cookie policy and cookie declaration, with purpose descriptions and categorization of your cookies (Necessary, Statistic, Preferences, Marketing).
* A Do Not Sell My Personal Information link on the cookie declaration for end-users to opt out of having their data sold to third parties, as required by the CCPA – including the special opt-in banner for sites that have visitors under the age of 16, also in accordance with CCPA.
* Monthly website scans, detecting all first- and third-party cookies and similar tracking on your site (e.g. HTML5 Local Storage, Flash Local Shared Object, Silverlight Isolated Storage, IndexedDB, ultrasound beacons, pixel tags etc.) as well as their provenance, duration and where the data is sent to.
* The unique Cookiebot feature of automatic cookie blocking. All cookies and tracking are automatically held back right from the moment the user lands on the website and until the proper consent has been obtained, enabling compliance with the GDPR and the ePrivacy Directive.
* An easy way to allow the user to change or withdraw their consent.
* Supports 45 languages in consent banner and cookie declaration.
* Secure storage of user consents in our cloud-driven environment. The user consents are downloadable and can be used as proof that the consent was given.
* Execution of cookie-setting scripts without a page reload, if the user gives consent.

Please read our FAQ at the bottom of the page for more information.

https://youtube.com/watch?v=t1LJ6i1i9gA

### Will my website become compliant once I install Cookiebot? ###

If you use our plugin version 3.0 or later and choose the automatic implementation, Cookiebot enables full compliance with the ‘prior consent’ requirement of the GDPR, the ePrivacy Directive (ePR) and similar legal instruments for protection of user data around the world.

Cookiebot also enables compliance with the CCPA through the implementation of the Do Not Sell My Personal Information link on a website’s cookie declaration, as well as the opt-in banner required if your website targets visitors under the age of 16.

The default consent banner has the strictest settings possible and is suitable for obtaining consent under both GDPR and the ePR. Make sure to adapt the consent banner content to fit your website. You should also check out [our GDPR checklist if you have users from the European Union](https://support.cookiebot.com/hc/en-us/sections/360000917513-Ready-for-25-May-2018-GDPR-enforcement-date-A-Cookiebot-checklist-). If you are using server-side cookies, please check the [“Server side usage” guide](https://www.cookiebot.com/en/developer/).

However, being a customizable plugin, true compliance always depends on the website owner choosing a type of banner, wording and settings that are compliant with the local legislation and fits the specific website and company (data controller).

**If you choose the manual implementation…**

Then please make sure of the following:

If you are using plugins that set cookies which require consent (which most do), you have to:

* Ask the plugin developers if they are planning on becoming GDPR/CCPA compliant, or if they would like to integrate with Cookiebot.
* [Check out our Github repository](https://github.com/CybotAS/CookiebotAddons), where we are developing integrations for Cookiebot, until the plugin developers choose to do this themselves, [or if WP Core enables such functionality](https://core.trac.wordpress.org/ticket/44043).

Once Cookiebot is installed and we’ve scanned your site, you can check the [cookie report](https://manage.cookiebot.com/goto/reports) to identify all cookies being set on your website. If the cookies are coming from content that you have inserted manually, you can mark up that content [as described in our manual implementation guide step 4](https://cookiebot.com/goto/manual-implementation).

Embedded videos and iframes which set cookies can be marked up as explained in our [Iframe cookie consent with YouTube example](https://support.cookiebot.com/hc/en-us/articles/360003790854-Iframe-cookie-consent-with-YouTube-example).


### Cookiebot Addons ####
Add-ons are produced by an open-source community of developers. This is done to help make it easier for Wordpress users to implement ‘prior consent’ for cookies and trackers set by plugins that do not offer this as a built-in functionality.

The add-ons are currently the best alternative to a Wordpress Core framework that can signal the user’s consent state to other plugins (if and when this will be implemented is unknown) and to those plugins who do not yet offer native support for Cookiebot built into the plugin itself.

We do not assume any responsibility for the use of these add-ons. If one of the plugins that the add-ons hook into makes a ‘breaking change’, there may be a period of time where the add-on will not work properly until it has been updated to accommodate the changes in the plugin.

If your favourite plugin isn't supported you're welcome to contribute or request on our [Github development page](https://github.com/CybotAS/CookiebotAddons).


## Installation ##
First, install the plugin on your WordPress site.

Then, go to Settings -> Cookiebot and add your Cookiebot ID.

If you haven't created one yet - or if you're not sure how to find it - follow the instructions on the page.

Once your Cookiebot ID is added, the consent dialog will be displayed to the visitors of your site.

[For more help, visit our support section, that contains various articles on how to set up Cookiebot properly](https://support.cookiebot.com/).

**Installation video:**

https://www.youtube.com/watch?v=t1LJ6i1i9gA

**Implementing the cookie declaration:**

https://youtu.be/OCXz2bt4H_w

**Implementing prior consent:**

Below is a video tutorial on how to manually implement prior consent i.e. holding back all cookies and trackers (other than those strictly necessary for the website to function) until the user has given a consent. The manual implementation guide (found in step 11 of the video) can be [found here](https://cookiebot.com/goto/manual-implementation).
Please note that you can make use of our automatic cookie blocking functionality instead of manually having to mark up the cookie-setting scripts. To do so, update the plugin (version 3.0.0 or higher) and choose automatic cookie control in your Admin panel. 

https://youtu.be/MeHycvV2QCQ

## Frequently Asked Questions ##

### Is Cookiebot free? ###
Cookiebot is a freemium plugin, much like [Jetpack](https://wordpress.org/plugins/jetpack/) and [Monsterinsights](https://wordpress.org/plugins/google-analytics-for-wordpress/).

Whether the free plan can suffice or you need a premium plan, depends on two things:

**1. The size of your website, i.e. the number of subpages on your website.**
In other words, you cannot choose whether you want a free plan or a premium plan, as this is determined by the amount of subpages on your website. You can [get a quote](https://www.cookiebot.com/goto/quote-input/) to see what plan you need for your website.

**2. Your necessities, as the free plan does not include all features available in the premium plans.** The free plan does not include customization of banner and cookies declaration, multiple languages, e-mail reports, data-export, geo location, bulk consent, consent statistics, internal domain alias for development, test and staging.

If more than 100 subpages are found during the initial website scan, you will be given a free one-month trial of Cookiebot with full functionality.

See all details of [Cookiebot plans and pricing](https://www.cookiebot.com/goto/pricing/).

### What does Cookiebot count as pages?
Your pricing plan is dependent on the number of subpages we have detected on your website.

Some users mistakenly think that Cookiebot counts their **image files** as subpages, thus setting the users on a higher subscription plan.

Cookiebot does **not count image files as subpages**. Depending on your theme, Wordpress may automatically create real pages for content placed in your media library, called Attachment pages. Because these pages can contain online trackers, Cookiebot includes them in your page count.

To disable the Attachment page feature in Wordpress, please see the following guides:

https://themeskills.com/disable-media-attachment-pages-wordpress/
https://www.wpexplorer.com/disable-image-page/

### Cookie declaration ###
The Cookiebot solution also includes an automatically updated cookie declaration about the cookies in use on your site.

By implementing it, you ensure that your cookie declaration is specific and accurate at all times, as required by the GDPR. Also, the declaration automatically provides the mandatory options for the user to change or withdraw consent.

For CCPA compliance, businesses will be able to display the CCPA required **Do Not Sell My Personal Information** link on their cookie declaration.

To display your cookie declaration, create a new page on your website - and add the shortcode that the plugin provides to the page: [cookie_declaration]. Alternatively, you can incorporate it into e.g. your existing Privacy Policy.

By default, the cookie declaration is displayed in the chosen Cookiebot language. You are able to override this setting with a "lang" attribute in the shortcode. Eg.: [cookie_declaration lang="de"] for a German version. Remember to add all used languages in the [Cookiebot Manager](https://manage.cookiebot.com).

https://youtu.be/OCXz2bt4H_w

### Cookie-checker: What cookies are in use on my site? ###
If you  are in doubt about what cookies are in use on our site, you can start by trying our free compliance test:

**[Test my site](https://www.cookiebot.com/)**

The test scans up to five pages of your website and sends you a complete report of the cookies and online tracking on these pages, including information on their provenance, purpose and whether or not they are compliant with EU-legislation.

If you want a complete overview of the cookies and online tracking going on on all of your website, sign up to the Cookiebot solution.

### What is the GDPR and the ePrivacy Directive? ###
The GDPR is the General Data Protection Regulation, an EU-law enforced since May 2018, that protects EU citizens’ personal data globally and affects all organizations and websites that handle such data. If you have a website with users from the EU, and if your websites uses cookies (it probably does), then you need to make your use of cookies and tracking compliant with the GDPR.

See the [EU homepage of the GDPR](https://eur-lex.europa.eu/legal-content/EN/TXT/?uri=celex%3A32016R0679) for more information, and their infographic for businesses: [Data Protection: Better rules for small businesses](http://ec.europa.eu/justice/smedataprotect/index_en.htm)

The ePrivacy Directive is another legal instrument of the EU that specifically aims to protect EU citizens’ online data such as data from online communication. It is in the process of becoming an actual Regulation such as the GDPR, which will probably happen in 2020.

### What is the CCPA? ###
The California Consumer Privacy Act (CCPA) is a state-wide law that regulates how businesses all over the world are allowed to handle the personal information of California residents.

You are liable for CCPA compliance if your business:

* Sells the personal information of more than 50,000 California residents per year,
* Has an annual gross revenue exceeding $25 million,
* Derives more than 50 percent of its annual revenue from the selling of personal information of California residents.

Cookies and other tracking technologies are classified as unique identifiers that form part of the CCPA’s definition of personal information.

If your business has a website, you must know and disclose to consumers, at or before the point of collection, the data you collect and which third parties you share it with. This can be done through the Cookiebot cookie declaration that also features the required Do Not Sell My Personal Information link, so end-users can opt out of having their data sold.

If your website is visited by consumers under the age of 16, you are required by the CCPA to first obtain their opt-in. This can be done through Cookiebot’s CCPA opt-in banner.

Visit [Cookiebot](https://www.cookiebot.com/en/what-is-ccpa/) to read more about the CCPA, how to achieve CCPA compliance, and what the CCPA says about your website’s use of cookies.

### How do I make other plugins support Cookiebot? ###
If your favourite plugin doesn't support Cookiebot you are always welcome to ask the author to add support for Cookiebot.

Send an e-mail to the author of the plugin you want to support Cookiebot. Ask for adding support for Cookiebot. Cookiebot provides a helper function to check if there is an active, working version of Cookiebot on the website.

The easiest way for a developer to implement Cookiebot support is following to add a check for Cookiebot where <script> tags are outputted to the visitor. This can be done following way

$scriptTag = '<script>';
if(function_exists('cookiebot_active') && cookiebot_active()) {
   $scriptTag = '<script'.cookiebot_assist('statistics').'>';
}

The developer of the plugin can see more details on [our Github repository](https://github.com/CybotAS/CookiebotAddons)

### Can I use Cookiebot with Google Tag Manager? ###
Cookiebot works with GTM. There are two different options setting up GTM with Cookiebot:

1. Use the Google Tag Manager option in the plugin settings to enable GTM together with Cookiebot. Here you also have the option to enable Google Consent Mode for GTM.
2. Add the GTM script manually or by using another plugin to your site.

Should you choose one of these methods, Cookiebot must not be implemented using GTM as this would result in Cookiebot being loaded twice.

If you prefer the latter method, you should select ´Hide Cookie Popup´ in the Cookiebot WordPress plugin settings.

[Please see our article on how to deploy Cookiebot with GTM](https://support.cookiebot.com/hc/en-us/articles/360003793854-Google-Tag-Manager-deployment).

### Does Cookiebot integrate with WP Consent API? ###
Cookiebot is fully integrated with the WP Consent API. When your visitors give consent using Cookiebot, the consent will automatically be sent to the [WP Consent API](https://wordpress.org/plugins/wp-consent-api/).

You are able to define the mapping between Cookiebot and the WP Consent API in the administration interface (Cookiebot WP plugin).

### Does Cookiebot work with translation plugins?
Cookiebot is compatible with translation plugins when you set language to "Use WordPress Language".

## Changelog ##
### 4.1.1 - 2022-07-01 ###
* Fixed undefined variable src when using instagram embed

### 4.1.0 - 2022-06-15 ###
* Added setting to ignore scripts from cookiebot scan
* Fixed PHP8 warnings
* Fixed attribute builder function for scripts
* Fixed blocking cookiebot script when editing theme

### 4.0.3 - 2022-02-23 ###
* Fixed wp-rocket not ignoring cookiebot script
* Fixed including gtm and gtc scripts when the setting was unchecked
* Updated addon to support latest version of CAOS

### 4.0.2 - 2022-01-20 ###
* Fixed missing dir path for require_once

### 4.0.1 - 2022-01-20 ###
* Fixed missing file

### 4.0.0 - 2022-01-20 ###
* Added support for SEOPress
* Updated code structure to improve maintainability
* Replaced filters & function names. Check [GitHub upgrade guide](https://github.com/CybotAS/CookiebotWP/blob/master/documentation/upgrade-guide.md) for more information about deprecations and breaking changes.

### 3.11.3 - 2021-11-25 ###
* Updated tests for add-to-any plugin version >= 1.8.2
* Added support for blocking the new script handles for add-to-any plugin version >= 1.8.2

### 3.11.2 - 2021-11-17 ###
* Updated CookieBot logo on settings page + network settings page

### 3.11.1 - 2021-09-22 ###
* Fixed unescaped PHP output
* Updated some translations

### 3.11.0 - 2021-07-16 ###
* Removed PHP-DI
* Fixed number of arguments in the settings-service-interface.php get_placeholder method signature
* Added custom container class to manage dependencies
* Added support for the Matomo Analytics plugin
# Added support for WP Google Analytics Events

### 3.10.1 - 2021-04-29 ###
* Added support for translating 'marketing', 'statistics', and 'preferences'
* Allow cookies for same domain embedded content

### 3.10.0 - 2021-03-22 ###
* Added support for translating the settings pages
* Added support for Enfold theme
* Added support for ExactMetrics
* Added support for Gutenberg Embed blocks
* Added support for newer version of Custom Facebook Feed
* Added support for newer version of Add To Any
* Fixed prior consent language bug
* Fixed embedding twitter
* Fixed multisite settings
* Prefixed the composer dependencies with Mozart

### 3.9.0 - 2020-10-20 ###
* Added support for Google Tag Manager and Google Consent Mode
* Added gtag TCF support
* Added WooCommerce Google Analytics Pro addon
* Support for enabling Cookiebot in administration

### 3.8.0 - 2020-09-07 ###
* New addon for Official Facebook Pixel plugin
* Fixes and improvements

### 3.7.1 - 2020-07-08 ###
* Fix "wp_enqueue_script was called incorrectly" notice

### 3.7.0 - 2020-07-06 ###
* Adding CCPA feature
* Adding Gutenberg Cookie Declaration block for editor

### 3.6.6 - 2020-06-16 ###
* Fix through addon for Lightspeed Cache
* Added addon for Enchanged Ecommerce for WooCommerce
* Added addon for Simple Share Buttons

### 3.6.5 - 2020-05-19 ###
* Adding fix for SG Optimizer
* Add support for latest version of Facebook for Woocommerce addon

### 3.6.3 - 2020-04-30 ###
* Adding support for default enabled addons
* Added filter tp addon list

### 3.6.2 - 2020-04-22 ###
* Adding WP Rocket addon
* Adding WP Mautic addon

### 3.6.1 - 2020-03-12 ###
* Fix security issue - possible XSS
* Update tests for addons

### 3.6.0 - 2020-02-18 ###
* Adding Debugging submenu
* Update GADWP addon to support newest version of plugin

### 3.5.0 - 2020-02-10 ###
* Adding integration with WP Consent API

### 3.4.2 - 2020-02-06 ###
* Fix for DIVI editor

### 3.4.1 - 2020-01-28 ###
* Removing manual fixes for Cookiebot when in auto
* Adjustments and updates to addons

### 3.4.0 - 2019-12-13 ###
* Removing Cookiebot when in auto mode and the user is logged in and has a edit themes capability
* Adding filter for regexp for fixing embedded media

### 3.3.0 - 2019-11-09 ###
* Fix for conflict with WPBakery Page Builder when Cookie blocking is in auto mode
* Fix for Elementor Extras causing JS errors in frontend when Cookie blocking is in auto mode
* Removing prepending of composer autoloader - causing conflicts with other plugins.

### 3.2.0 - 2019-10-29 ###
* Adding fix for conflict with Elementor Page Builder when Cookie blocking is in auto mode
* Adding fix for conflict with Divi Builder when Cookie blocking is in auto mode (still need to disable Cookiebot on admin pages to work properly).
* Minor adjustments to code style and unit tests

### 3.1.0 - 2019-09-24 ###
* Change option to hide cookie banner in WP Admin to instead disabling Cookiebot
* Set option to disable Cookiebot banner as default for new installs

### 3.0.1 - 2019-09-17 ###
* Clean up setting page
* Fixing failing addons after plugin updates

### 3.0.0 - 2019-09-10 ###
* Adding support for auto cookie blocking mode

### 2.5.0 - 2019-06-12 ###
* Add support for custom regex for embed autocorrect
* Adding Cookie Declaration widget
* Remove support for PHP 5.4

### 2.4.4 - 2019-05-22 ###
* Minor bugfixes in Embed Autocorrect addon

### 2.4.3 - 2019-05-16 ###
* Fix bug in Embed Autocorrect addon.

### 2.4.2 - 2019-05-15 ###
* Adding addthis addon
* Disable default autoupgrade
* Minor fixes

### 2.4.1 - 2019-03-19 ###
* Fix jetpack related warning

### 2.4.0 - 2019-03-19 ###
* Fixed bug resulting in some tags where not tagged
* Change Piwik addon to use output buffering
* Clean up redundant code in addons

### 2.3.0 - 2019-03-13 ###
* Added GADWP addon
* Changes in file structure of plugin

### 2.2.2 - 2019-02-12 ###
* Fix warning non-static call to get_cbid

### 2.2.1 - 2019-02-12 ###
* Adding support for WPForms
* Add plugin deactivation hook for addons

### 2.2.0 - 2019-02-11 ###
* Adding support for network wide settings on Multisite Wordpress setups.

### 2.1.5 - 2019-01-17 ###
* New addon: Custom Facebook Feed Pro
* Adding support for setting none, defer or async to Cookiebot script tags

### 2.1.3 - 2018-11-18 ###
* New addon: Popups by OptinMonster
* Added support for grouping addons for different versions of same plugin

### 2.1.2 - 2018-11-06 ###
* Auto correct addon added support for single quotes in iframe embeds

### 2.1.1 - 2018-11-02 ###
* Updated addons with new tests and better plugin integration

### 2.1.0 - 2018-10-05 ###
* Updated addons improved handling of tags
* Adding Basque as language option
* Remove .git files in addons

### 2.0.6 - 2018-09-26 ###
* Updated addons to support newest version of CAOS
* Minor bugfixes and text adjustments

### 2.0.5 - 2018-09-21 ###
* Added "Leave a review" admin notice

### 2.0.4 - 2018-09-18 ###
* Added [IAB Consent Framework](https://support.cookiebot.com/hc/en-us/articles/360007652694-Cookiebot-and-the-IAB-Consent-Framework) option
* Update Cookiebot Addons
* Minor bugfixes

### 2.0.3 - 2018-08-10 ###
* Removing informative message in admin_notice. Causing parse error for some users.

### 2.0.2 - 2018-08-01 ###
* Quickfix - disable Script Config. Made trouble for some users.

### 2.0.1 - 2018-08-01 ###
* Disable load of Addons if server runs PHP < 5.4.
* Fix placeholder in addons

### 2.0.0 - 2018-07-30 ###
* Merge Cookiebot and Cookiebot Addons plugins. Cookiebot plugin now bundled with addons making other plugins GDPR compliant.

### 1.6.2 - 2018-06-21 ###
* Fix check for WP Rocket.

### 1.6.1 - 2018-06-11 ###
* Fixing shortcode when using WP Rocket

### 1.6.0 - 2018-05-30 ###
* Support for removing cookie consent banner for tag manager users
* Support for multiple consent types in cookie_assist - eg. cookie_assist(['marketing','statistics']);
* Loading Cookiebot JS on admin pages too
* Adjusting help texts and adding links to demonstration videos.

### 1.5.1 - 2018-05-25 ###
* Adjusting readme.txt including new video

### 1.5.0 - 2018-05-22 ###
* Adding autoupdate for Cookiebot

### 1.4.2 - 2018-05-17 ###
* Fix undefined $lang bug in shortcode output

### 1.4.1 - 2018-05-11 ###
* Adjusting readme file

### 1.4.0 - 2018-05-10 ###
* Adding support for specifying language of cookie banner
* Adding optional "lang" attribute to [cookie_declaration] shortcode

### 1.3.0 - 2018-04-29 ###
* Bug fixed: Headers already sent when editing pages using shortcode

### 1.2.0 - 2018-03-28 ###
* Updating readme file with more details
* Adding cookiebot_active helper function

### 1.1.2 - 2018-02-27 ###
* Removing short array syntax to improve compatibility

### 1.1.1 - 2018-02-02 ###
* Adjusting readme.txt

### 1.1.0 - 2018-02-02 ###
* Adding data-culture to cookiebot script tags.

### 1.0.0 ###
* Initial release of the plugin.


## Copyright and Trademarks ##

Cookiebot is a registered trademark of Cybot A/S
