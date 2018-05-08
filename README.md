# Cookiebot Wordpress Addons


Cookiebot Addons are plugins for Wordpress that make other plugins compatible with Cookiebot. 
The addons hook into the original plugin and render the cookie setting tags as advised by the Cookiebot guidelines at https://www.cookiebot.com/goto/help/.

# News on MonsterInsights and GADWP
MonsterInsights and GADWP are about to release a GDPR compliance addon, which supports Cookiebot. We'll provide a link here once the addon is released. 

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

A users consent state can be be aquired through Cookiebots JS API:

The following properties are available on the CookieConsent object:

| Name                | Type | Default | Description                                                                                                                                                                                                            |
|---------------------|:----:|:-------:|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------:|
| consent.necessary   | bool | true    | True if current user has accepted necessary cookies. The property is read only.                                                                                                                                        |
| consent.preferences | bool | false   | True if current user has accepted preference cookies. The property is read only.                                                                                                                                       |
| consent.statistics  | bool | false   | True if current user has accepted statistics cookies. The property is read only.                                                                                                                                       |
| consent.marketing   | bool | false   | True if current user has accepted marketing cookies. The property is read only.                                                                                                                                        |
| consented           | bool | false   | True if the user has accepted cookies. The property is read only.                                                                                                                                                      |
| declined            | bool | false   | True if the user has declined the use of cookies. The property is read only.                                                                                                                                           |
| hasResponse         | bool | false   | True if the user has responded to the dialog with either 'accept' or 'decline'.                                                                                                                                        |
| doNotTrack          | bool | false   | True if the user has enabled the web browser's 'Do not track' (DNT) setting. If DNT is enabled Cookiebot will not set the third party cookie CookieConsentBulkTicket used for bulk consent. The property is read only. |

And through PHP:

```php
if (isset($_COOKIE["CookieConsent"]))
{
    switch ($_COOKIE["CookieConsent"])
    {
        case "0":
            //The user has not accepted cookies - set strictly necessary cookies only
            break;

        case "-1":
            //The user is not within a region that requires consent - all cookies are accepted
            break;

        default: //The user has accepted one or more type of cookies
            
            //Read current user consent in encoded JavaScript format
            $valid_php_json = preg_replace('/\s*:\s*([a-zA-Z0-9_]+?)([}\[,])/', ':"$1"$2', preg_replace('/([{\[,])\s*([a-zA-Z0-9_]+?):/', '$1"$2":', str_replace("'", '"',stripslashes($_COOKIE["CookieConsent"]))));
            $CookieConsent = json_decode($valid_php_json);

            if (filter_var($CookieConsent->preferences, FILTER_VALIDATE_BOOLEAN))
            {
                //Current user accepts preference cookies
            }
            else
            {
                //Current user does NOT accept preference cookies
            }

            if (filter_var($CookieConsent->statistics, FILTER_VALIDATE_BOOLEAN))
            {
                //Current user accepts statistics cookies
            }
            else
            {
                //Current user does NOT accept statistics cookies
            }

            if (filter_var($CookieConsent->marketing, FILTER_VALIDATE_BOOLEAN))
            {
                //Current user accepts marketing cookies
            }
            else
            {
                //Current user does NOT accept marketing cookies
            }   
    }
}
else
{
    //The user has not accepted cookies - set strictly necessary cookies only
}
```
The developer of the plugin can see more details on https://www.cookiebot.com/goto/developer/