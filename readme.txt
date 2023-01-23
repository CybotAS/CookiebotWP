# Cookiebot CMP by Usercentrics | The reliable, flexible and easy to use consent solution #
* Contributors: cookiebot,phpgeekdk,aytac
* Tags: cookie banner, cookies, GDPR, CCPA, cookie notice, dsgvo, ePrivacy, privacy compliance, cookie law, data privacy, cmp
* Requires at least: 4.4
* Tested up to: 6.1.1
* Stable tag: 4.2.4
* Requires PHP: 5.6
* License: GPLv2 or later

## Description ##

### RELIABLE, FLEXIBLE AND EASY TO USE COOKIE CONSENT SOLUTION FOR GDPR/ePR, CCPA/CPRA and IAB TCF compliance. ###

Cookiebot consent management platform (CMP) provides a **plug-and-play cookie consent solution** that enables compliance with the **GDPR, LGPD, CCPA and other international regulations**. It deploys industry-leading scanning technology and seamless integration with Google Consent Mode to help you **balance data privacy with data-driven business** on your domain.

The Cookiebot CMP delivers:

- highly **customizable cookie consent banner** that enables website visitors to opt in or out of cookie categories

- industry-leading scanning technology that **automatically detects all cookies and other trackers** on your website

- **automatically-generated cookie declaration** that can be used for your privacy policy page

- full **integration with Google Consent Mode** right out of the box

- automatic cookie blocking feature that blocks all cookies and trackers until user consent has been obtained, **enabling compliance with GDPR, ePR, CCPA/CPRA** and other international regulations

- easy to use widget that enables website visitors to **update their consent** at any time

- **secure storage of user consents** in our cloud-based environment

- downloadable user consent information to **comply with Data Subject Requests**

- cookie consent banner and cookie declaration **in over 40 languages**

[Find detailed information on the Cookiebot CMP functions here](https://www.cookiebot.com/en/functions/).

**Please read our FAQ at the bottom of the page for more information.**

**How to install the Cookiebot CMP Wordpress Plugin**

[youtube https://youtube.com/watch?v=eSVFnjoMKFk]

**NEED HELP?** [Visit our Help Center](https://support.cookiebot.com/hc/en-us)

### Cookiebot Addons ####
Add-ons are produced by an open-source community of developers. This is done to help make it easier for Wordpress users to implement ‘prior consent’ for cookies and trackers set by plugins that do not offer this as a built-in functionality.

The add-ons are currently the best alternative to a Wordpress Core framework that can signal the user’s consent state to other plugins (if and when this will be implemented is unknown) and to those plugins who do not yet offer native support for Cookiebot built into the plugin itself.

We do not assume any responsibility for the use of these add-ons. If one of the plugins that the add-ons hook into makes a ‘breaking change’, there may be a period of time where the add-on will not work properly until it has been updated to accommodate the changes in the plugin.

If your favourite plugin isn't supported you're welcome to contribute or request on our [Github development page](https://github.com/CybotAS/CookiebotAddons).


## Installation ##

To easily install the Cookiebot CMP, follow the steps below.


1. Install the plugin on your WordPress website.

2. Make sure that you have a Cookiebot CMP account. Don't have an account yet? Sign up [here](https://manage.cookiebot.com/en/signup/?utm_source=wordpress&utm_medium=organic&utm_campaign=banner).
If your website counts less than 100 subdomains, your Cookiebot CMP account is completely free. All the other subscriptions have a free 30-day-trial period.

3. Link your Cookiebot CMP account to the plugin, go to "Settings" > "General Settings" in the WordPress Dashboard, and add your Domain Group ID.
Need help finding your Domain Group ID? [Learn here](https://support.cookiebot.com/hc/en-us/articles/4405643234194-Your-CBID-or-Domain-group-ID-and-where-to-find-it) where you can find it. Once your Domain Group ID is added in the WordPress Dashboard, the cookie consent banner will be visible on your website.

4. Set the main language of your cookie consent banner

5. Select your cookie-blocking mode. The [auto cookie-blocking mode](https://support.cookiebot.com/hc/en-us/articles/36000906310x0-How-does-Automatic-Cookie-Blocking-work-) will automatically block all cookies (except for ‘strictly necessary’ cookies) until a user has given consent. The [manual cookie-blocking mode](https://support.cookiebot.com/hc/en-us/articles/4405978132242-Manual-cookie-blocking) requests manual adjustments to the cookie-setting scripts.


To find out more about the setup and customizability of the Cookiebot CMP, please visit our [Help Center](https://support.cookiebot.com/).

**Recommended Videos**

How to install the Cookiebot CMP Wordpress Plugin

[youtube https://youtube.com/watch?v=eSVFnjoMKFk]

Implement the Cookie Declaration

[youtube https://youtube.com/watch?v=OCXz2bt4H_w]

## Google Consent Mode ##
Google Consent Mode is enabled by default. It's an open API developed by Google that enables your website to run all Google services, such as Google Analytics, Google Tag Manager and Google Ads, based on the consent status of your end users. With Cookiebot CMP's integration with Google Consent Mode you can respect the privacy choices of end users with minimal impact on your website's ad-based revenue stream, analytics and more. [Learn more about Google Consent Mode](https://support.cookiebot.com/hc/en-us/articles/360016047000-Cookiebot-and-Google-Consent-Mode).

## Frequently Asked Questions ##

### Is Cookiebot free? ###
Cookiebot is a freemium plugin, much like Jetpack and Monsterinsights.

Whether the free plan is sufficient or you need a premium plan depends on two things:

1. The size of your website, i.e. the number of subpages on your website.
You cannot choose whether you want a free plan or a premium plan, as this is determined by the number of subpages on your website. Get a quote to see which plan your site needs.

2. The features you need. The free plan **does not include** all features available in the premium plans. The free plan does not include customization of banner and cookies declaration, multiple languages, email reports, data export, geolocation, bulk consent, consent statistics, internal domain alias for development, test and staging.

If more than 100 subpages are found during the initial website scan, you will be given a free one-month trial of Cookiebot CMP with full functionality.

See all details of Cookiebot CMP [plans and pricing](https://www.cookiebot.com/goto/pricing/).

### What does Cookiebot count as pages? ###
Your pricing plan is dependent on the number of subpages the Cookiebot CMP detects on your website.

Cookiebot does not count image files as subpages, thus setting users to a higher subscription plan. Depending on your theme, WordPress may automatically create real pages for content placed in your media library, called Attachment pages. Because these pages can contain online trackers, Cookiebot CMP includes them in your page count.

To disable the Attachment page feature in WordPress, please see the following guides:

https://themeskills.com/disable-media-attachment-pages-wordpress/
https://www.wpexplorer.com/disable-image-page/

### Cookie declaration ###
Cookiebot CMP also includes an automatically updated cookie declaration for the cookies in use on your site.

By implementing it, you ensure that your cookie declaration is specific and accurate at all times, as required by the GDPR. Also, the declaration automatically provides the mandatory options for the user to change or withdraw consent.

For CCPA and CPRA compliance, businesses will be able to display the required Do Not Sell Or Share My Personal Information link on their cookie declaration.

To display your cookie declaration, create a new page on your website and add the shortcode provided by the plugin to the page: [cookie_declaration]. Alternatively, you can incorporate it into your existing Privacy Policy.

By default, the cookie declaration is displayed in the selected Cookiebot CMP language. You are able to override this setting with a “lang” attribute in the shortcode. E.g.: [cookie_declaration lang=”de”] for a German version. Remember to add all languages used in the [Cookiebot Manager](https://manage.cookiebot.com/en/login).

[youtube https://www.youtube.com/watch?v=OCXz2bt4H_w]

### Cookie-checker: What cookies are in use on my site? ###
If you are in doubt about what cookies are in use on our site, start with our free compliance test:

**[Test my site](https://www.cookiebot.com/)**

The test scans up to five pages of your website and sends you a complete report of the cookies and online tracking on these pages, including information on their provenance, purpose and whether or not they are compliant with EU regulations.

If you want a complete overview of the cookies and online tracking on your website, sign up for the Cookiebot CMP solution.

### How can I set up my cookie banner with Cookiebot CMP? ###

Please find here **[our step by step guide](https://support.cookiebot.com/hc/en-us/articles/4408356523282-Getting-started)** on how to set up your cookie banner.

### How does Cookiebot CMP block cookies and trackers?###
Cookiebot CMP provides two modes to block cookies and trackers before consent has been given.

1. **Auto cookie-blocking mode** will automatically block all cookies (except for ‘strictly necessary’ cookies) until a user has given consent. [Learn more about the auto cookie-blocking mode](https://support.cookiebot.com/hc/en-us/articles/360009074960-Automatic-cookie-blocking).
2. **Manual cookie-blocking mode** requests manual adjustments to the cookie-setting scripts. [Read more about the manual cookie-blocking mode](https://support.cookiebot.com/hc/en-us/articles/4405978132242-Manual-cookie-blocking).

### What is the GDPR and the ePrivacy Directive? ####
The GDPR is the General Data Protection Regulation, an EU law enforced since May 2018. It protects EU citizens’ personal data globally and affects all organizations and websites that handle such data. If you have a website with users from the EU, and if your website uses cookies (it probably does), then you need to make your use of cookies and tracking compliant with the GDPR.

See the [EU GDPR homepage](https://eur-lex.europa.eu/legal-content/EN/TXT/?uri=celex%3A32016R0679) for more information, or their infographic for businesses: [Data Protection: Better rules for small businesses](https://ec.europa.eu/justice/smedataprotect/index_en.htm).

The ePrivacy Directive is another EU legal instrument that specifically aims to protect EU citizens’ online data, such as data from online communication. It is in the process of becoming a full regulation like the GDPR.

### What is the CCPA and CPRA? ###
The California Consumer Privacy Act (CCPA) is a state-wide law that regulates how businesses all over the world are allowed to handle the personal information of California residents. Starting from January 1, 2023 the California Privacy Rights Act (CPRA) comes into effect, amending and extending the CCPA.

You are liable for CCPA compliance if your business:

Sells the personal information of more than 50,000 California residents per year (increased to 100,000 under the CPRA as of January 1st, 2023)
Has an annual gross revenue exceeding $25 million
Derives more than 50 percent of its annual revenue from the selling of personal information of California residents

Under the CPRA, compliance also applies to sharing as well as selling personal information. Cookies and other tracking technologies are classified as unique identifiers that form part of the CCPA’s definition of personal information.

If your business has a website, you must know and disclose to consumers, at or before the point of collection, the data you collect and which third parties you share it with. This can be done through the Cookiebot CMP cookie declaration that also features the required "Do Not Sell Or Share My Personal Information" link, so end users can opt out of having their data sold or shared.

If your website is visited by consumers under the age of 16, you are required by the CCPA and CPRA to first obtain their opt in. This can be done through Cookiebot CMP's CCPA opt-in banner.

Click [here](https://www.cookiebot.com/en/what-is-ccpa/) to find all the details about the CCPA and CPRA and how to achieve compliance.

### How do I make other plugins support Cookiebot? ###

If your favorite plugin doesn’t support Cookiebot CMP you are always welcome to ask the developer to add support for it.

Send an email to the plugin developer you want to support Cookiebot and request that support for Cookiebot CMP be added. Cookiebot provides a helper function to check if there is an active, working version of Cookiebot CMP on the website.

The easiest way for a developer to implement Cookiebot CMP support is following to add a check where tags are outputted to the visitor. This can be done following way:

$scriptTag = ”;
if(function_exists(‘cookiebot_active’) && cookiebot_active()) {
$scriptTag = ‘<script’.cookiebot_assist(‘statistics’).’>’;
}

The developer of the plugin can see more details on our [Github repository](https://github.com/CybotAS/CookiebotWP).

### Can my website become compliant once I install Cookiebot CMP? ###
If you use our plugin version 3.0 or later and choose the automatic implementation, Cookiebot enables full compliance with the ‘prior consent’ requirement of the GDPR, the ePrivacy Directive (ePR) and similar privacy regulations for protection of user data around the world. Cookiebot CMP also enables compliance with the CCPA and CPRA through the implementation of the "Do Not Sell Or Share My Personal Information" link on a website’s cookie declaration, as well as the opt-in banner required if your website targets visitors under the age of 16.

The default consent banner has the strictest settings possible and is suitable for obtaining consent under both GDPR and the ePR. Make sure to adapt the consent banner content to fit your website. Check out our [GDPR checklist](https://support.cookiebot.com/hc/en-us/sections/360000917513-Ready-for-25-May-2018-GDPR-enforcement-date-A-Cookiebot-checklist-) if you have website visitors from the EU. If you are using server-side cookies, please check the [Server-side usage guide](https://www.cookiebot.com/en/developer/).

Be aware that Cookiebot CMP is customizable and therefore true compliance always depends on the settings that you make within the plugin and Cookiebot Manager.

**Are you using the manual implementation?** Please make sure to check if you are using plugins that set cookies which require consent. If so, you have to:
1. Ask the plugin developers if they are planning on becoming GDPR/CCPA compliant, or if they would like to integrate with Cookiebot CMP. Check out our [Github repository](https://github.com/CybotAS/CookiebotAddons), where we are developing integrations for Cookiebot CMP, until the plugin developers choose to do this themselves, or if [WP Core](https://core.trac.wordpress.org/ticket/44043) enables such functionality.
2. Once Cookiebot CMP is installed, you can check the [cookie report](https://manage.cookiebot.com/goto/reports) to identify all cookies being set on your website. If the cookies are coming from content that you have inserted manually, you can mark up that content [as described in our manual implementation guide step 4](https://cookiebot.com/goto/manual-implementation). Embedded videos and iframes which set cookies can be marked up as explained in our [iframe cookie consent with YouTube example](https://support.cookiebot.com/hc/en-us/articles/360003790854-Iframe-cookie-consent-with-YouTube-example).

Disclaimer: These statements do not constitute legal advice, but only serve to support and inform you about the Cookiebot CMP product. Please consult a qualified lawyer should you have any legal questions.

### Can I use Cookiebot with Google Tag Manager? ###
Cookiebot works with GTM. There are two different options for setting up GTM with Cookiebot CMP:

Use the Google Tag Manager option in the plugin settings to enable GTM with Cookiebot. Here you also have the option to enable Google Consent Mode for GTM.
Add the GTM script manually or by using another plugin for your site.
Should you choose one of these methods, Cookiebot CMP must not be implemented using GTM as this would result in Cookiebot being loaded twice.

If you prefer the latter method, you should select ´Hide Cookie Popup´ in the Cookiebot WordPress plugin settings.

[Please see our article on how to deploy Cookiebot with GTM](https://support.cookiebot.com/hc/en-us/articles/360003793854-Google-Tag-Manager-deployment).

### Can I use Cookiebot CMP with Google Consent Mode? ###

Yes the Cookiebot consent management platform (CMP) and Google Consent Mode integrate seamlessly. You can find more information on the implementation of Google Consent Mode [here](https://support.cookiebot.com/hc/en-us/articles/360016047000-Cookiebot-and-Google-Consent-Mode).

### Does Cookiebot integrate with WP Consent API? ###
Cookiebot is fully integrated with the WP Consent API. When your visitors give consent using Cookiebot CMP, the consent will automatically be sent to the [WP Consent API](https://wordpress.org/plugins/wp-consent-api/).

You can define the mapping between Cookiebot and the WP Consent API in the administration interface (Cookiebot WP plugin).

### Does Cookiebot work with translation plugins? ###
Cookiebot CMP is compatible with translation plugins when you set language to “Use WordPress Language”.

## Changelog ##
### 4.2.4 - 2023-01-23 ###
* Add review card on dashboard
* Add settings quick access link on plugins page list
* Add translations German, French, Spanish, Italian and Portuguese
* Update Auto blocking mode guide url
* Fix “Undefined index: tab” warning
* Fix Wrong label on Url passthrough input on first load
* Fix Menu links url on multisite

### 4.2.3 - 2022-12-21 ###
* Allow disabling URL passthrough when Google Consent Mode is activated

### 4.2.2 - 2022-12-05 ###
* Resources delivery on subfolders environments fix Props: [fritzprod](https://profiles.wordpress.org/fritzprod/)
* Fix security issue - remove possible vulnerable files Props: [ajaffri](https://profiles.wordpress.org/ajaffri/)

### 4.2.1 - 2022-12-02 ###
* PHP 5.6 compatibility fix
* Code formatting update

### 4.2.0 - 2022-11-28 ###
* Added new UI design
* Added multiple configurations
* Fixed PHP8 warnings when saving network settings.
* Activate Google Consent Mode by default

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

Cookiebot CMP by Usercentrics is a registered trademark of Usercentrics A/S