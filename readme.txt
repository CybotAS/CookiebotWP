# Cookiebot | GDPR Compliant Cookie Consent and Notice #
* Contributors: cookiebot,phpgeekdk,aytac
* Tags: cookie, compliance, eu, gdpr, europe, cookie consent, consent
* Requires at least: 4.4
* Tested up to: 4.9
* Stable tag: 2.1.0
* Requires PHP: 5.4
* License: GPLv2 or later

Cookiebot is a fully GDPR & ePrivacy compliant cookie consent solution supporting prior consent, cookie declaration, and documentation of consents. Easy to install, implement and configure.

## Description ##

### What Cookiebot Offers ###

Cookiebot is a cloud-driven solution that offers:

* A highly customizable consent banner to handle user consents and give the users the required possibility to opt-in and -out of cookie categories.
* A cookie policy and declaration, with purpose descriptions and automatic categorization of your cookies (strictly necessary, preference, statistics, marketing).
* Full monthly scans to detect all tracking in place on the website as well as detection of where data is being sent to and where in the source code the cookie can be found.
* A scanner that detects various online trackers such as Cookies, HTML5 Local Storage, Flash Local Shared Object, Silverlight Isolated Storage, IndexedDB, ultrasound beacons, pixel tags etc.
* An easy way to allow the users to change or withdraw their consent.
* Translations for 44 languages and the ability to change the text on the banner and declaration for any language.
* Storage of user-consents in our cloud-driven environment, which are downloadable and can be used as proof.
* Execution of cookie-setting scripts without a page reload, if the user gives consent.

Please read our FAQ at the bottom of the page for more information.

https://youtube.com/watch?v=t1LJ6i1i9gA

### Will my website become compliant once I install Cookiebot? ###
The short answer is; no.

Although Cookiebot enables your website to become fully compliant, [especially obeying the prior consent rule](https://support.cookiebot.com/hc/en-us/articles/360004104033-What-does-prior-consent-mean-and-how-do-I-implement-it-), some manual work is required to achieve compliance.

* If you are using plugins that set cookies which require consent (which most do), you have to:
  * Ask the plugin developers if they are planning on becoming GDPR compliant, or if they would like to integrate with Cookiebot.
  * Check if there already exists an addon for your plugin in the "Prior consent" submenu.
  * [Check out our Github repository](https://github.com/CybotAS/CookiebotAddons), where we are developing integrations for Cookiebot, until the plugin developers choose to do this themselves, [or WP Core enables such functionality](https://core.trac.wordpress.org/ticket/44043)

Once Cookiebot is installed, and we've scanned your site, you can check the [cookie report](https://manage.cookiebot.com/goto/reports), to identify all cookies being set on your website. If the cookies are coming from content that you have inserted manually, then you can mark up that content [as described in our help section step 3](https://www.cookiebot.com/goto/help/).

Embedded videos and iframes, which set cookies, can be marked up as explain in our [Iframe cookie consent with YouTube example](https://support.cookiebot.com/hc/en-us/articles/360003790854-Iframe-cookie-consent-with-YouTube-example).


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

https://youtu.be/MeHycvV2QCQ

## Frequently Asked Questions ##

### Is Cookiebot free? ###
Cookiebot is a freemium plugin, much like [Jetpack](https://wordpress.org/plugins/jetpack/) and [Monsterinsights](https://wordpress.org/plugins/google-analytics-for-wordpress/).

Whether the free plan can suffice or you need a premium plan, depends on two things:

**1. The size of your website, i.e. the number of subpages on your website.**
In other words, you cannot choose whether you want a free plan or a premium plan, as this is determined by the amount of subpages on your website. You can [get a quote](https://www.cookiebot.com/en/quote-input/) to see what plan you need for your website.

**2. Your necessities, as the free plan does not include all features available in the premium plans.** The free plan does not include customization of banner and cookies declaration, multiple languages, e-mail reports, data-export, geo location, bulk consent, consent statistics, internal domain alias for development, test and staging.

No matter the size of your website, you do have the right to a one month trial of Cookiebot for free.

See all details of [Cookiebot plans and pricing](https://www.cookiebot.com/en/pricing/).

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

To display your cookie declaration, create a new page on your website - and add the shortcode that the plugin provides to the page: [cookie_declaration]. Alternatively, you can incorporate it into e.g. your existing Privacy Policy.

By default the cookie declaration is displayed in the chosen Cookiebot language. You are able to override this setting with a "lang" attribute in the shortcode. Eg.: [cookie_declaration lang="de"] for a German version. Remember to add all used languages in the Cookiebot administration tool.

https://youtu.be/OCXz2bt4H_w

### Cookie-checker: What cookies are in use on my site? ###
If you  are in doubt about what cookies are in use on our site, you can start by trying our free compliance test:

**[Test my site](https://www.cookiebot.com/en/)**

The test scans five pages of your website and sends you a complete report of the cookies and online tracking on these pages, including information on their provenance, purpose and whether or not they are compliant with the EU-regulations.

If you want a complete overview of the cookies and online tracking going on on all of your website, sign up to the Cookiebot solution.

### What is GDPR? ###
The GDPR is the General Data Protection Regulation, a EU-law that is enforced on the 25. May 2018, and affects all organizations and websites that handle data of EU-citizens.

See the [EU homepage of the GDPR](https://www.eugdpr.org/) for more information, and their infographic for businesses: [Data Protection: Better rules for small businesses](http://ec.europa.eu/justice/smedataprotect/index_en.htm)

### How do I make other plugins support Cookiebot? ###
If you favourite plugins doesn't support Cookiebot you always welcome to ask the author to add support for Cookiebot.

Send an e-mail to the author of the plugin you want to support Cookiebot. Ask for adding support for Cookiebot. Cookiebot provides a helper function to check if there is an active, working version of Cookiebot on the website.

The easiest way for at developer to implement Cookiebot support is following to add a check for Cookiebot where <script> tags are outputted to the visitor. This can be done following way

$scriptTag = '<script>';
if(function_exists('cookiebot_active') && cookiebot_active()) {
   $scriptTag = '<script'.cookiebot_assist('statistics').'>';
}

The developer of the plugin can see more details on [our Github repository](https://github.com/CybotAS/CookiebotAddons)

### Can I use Cookiebot with GTM? ###
Cookiebot works with GTM, however you need to enable the "Hide Cookie Popup" option on the Cookiebot plugin settings page.

[Please see our article on how to depoloy Cookiebot with GTM](https://support.cookiebot.com/hc/en-us/articles/360003793854-Google-Tag-Manager-deployment).


## Changelog ##

### 2.1.0 - 2019-10-05 ###
* Updated addons improved handling of tags
* Adding Basque as language option
* Remove .git files in addons

### 2.0.6 - 2019-09-26 ###
* Updated addons to support newest version of CAOS
* Minor bugfixes and text adjustments

### 2.0.5 - 2019-09-21 ###
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
