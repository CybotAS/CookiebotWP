# How do I make my plugin support Cookiebot?
If you favourite plugins doesn’t support Cookiebot you are always welcome to ask the author to add support for Cookiebot.
Cookiebot provides a helper function to check if there is an active, working version of Cookiebot on the website.
The easiest way for at developer to implement Cookiebot support is to add a check for Cookiebot where tags are outputted to the visitor. 

This can be done following way:

```php
$scriptTag = ";
if(function_exists('\cybot\cookiebot\lib\cookiebot_active') && \cybot\cookiebot\lib\cookiebot_active()) {
$scriptTag = '<script'.\cybot\cookiebot\lib\cookiebot_assist('statistics').'>';
}
```

A users consent state can be be aquired through Cookiebots JS API.

The following properties are available on the Cookiebot object:

| Name                | Type | Default | Description                                                                                                                                                                                                            |
|---------------------|:----:|:-------:|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------:|
| consent.necessary   | bool | true    | True if current user has accepted necessary cookies. <br> The property is read only.                                                                                                                                        |
| consent.preferences | bool | false   | True if current user has accepted preference cookies. <br> The property is read only.                                                                                                                                       |
| consent.statistics  | bool | false   | True if current user has accepted statistics cookies. <br> The property is read only.                                                                                                                                       |
| consent.marketing   | bool | false   | True if current user has accepted marketing cookies. <br> The property is read only.                                                                                                                                        |
| consented           | bool | false   | True if the user has accepted cookies. <br> The property is read only.                                                                                                                                                      |
| declined            | bool | false   | True if the user has declined the use of cookies. <br> The property is read only.                                                                                                                                           |
| hasResponse         | bool | false   | True if the user has responded to the dialog with either 'accept' or 'decline'.                                                                                                                                        |
| doNotTrack          | bool | false   | True if the user has enabled the web browser's 'Do not track' (DNT) setting. <br> If DNT is enabled Cookiebot will not set the third party cookie CookieConsentBulkTicket used for bulk consent. <br> The property is read only. |

Callbacks

| Name                			| Description                                                                                                                                                                                                            |
|-------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------:|
| CookiebotCallback_OnLoad   	| The asynchronous callback is triggered when the cookie banner has loaded to get the user's consent.                                                                                                                                        |
| CookiebotCallback_OnAccept	| The asynchronous callback is triggered when the user clicks the accept-button of the cookie consent dialog and whenever a consented user loads a page.                                                                                     |                                                 |
| CookiebotCallback_OnDecline	| The asynchronous callback is triggered when the user declines the use of cookies by clicking the decline-button in the cookie consent dialog. The callback is also triggered whenever a user that has declined the use of cookies loads a page. |                                                                                                                                      |


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
More details are available at https://www.cookiebot.com/en/developer/