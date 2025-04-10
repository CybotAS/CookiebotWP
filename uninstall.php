<?php // exit if uninstall constant is not defined
if (!defined('WP_UNINSTALL_PLUGIN')) exit;

// All new and legacy config params (except param: 'cookiebot-uc-onboarded-via-signup')
$options = array(
    'cookiebot-cbid',
    'cookiebot-auth-token',
    'cookiebot-user-data',
    'cookiebot-configuration',
    'cookiebot-scan-id',
    'cookiebot-scan-status',
    'cookiebot-banner-enabled',
    'cookiebot_banner_live_dismissed',
    'cookiebot-uc-auto-blocking-mode',

    'cookiebot-ruleset-id',
    'cookiebot-language',
    'cookiebot-front-language',
    'cookiebot-nooutput',
    'cookiebot-nooutput-admin',
    'cookiebot-output-logged-in',
    'cookiebot-ignore-scripts',
    'cookiebot-autoupdate',
    'cookiebot-script-tag-uc-attribute',
    'cookiebot-script-tag-cd-attribute',
    'cookiebot-cookie-blocking-mode',
    'cookiebot-iab',
    'cookiebot-tcf-version',
    'cookiebot-tcf-purposes',
    'cookiebot-tcf-special-purposes',
    'cookiebot-tcf-features',
    'cookiebot-tcf-special-features',
    'cookiebot-tcf-vendors',
    'cookiebot-tcf-disallowed',
    'cookiebot-tcf-ac-vendors',
    'cookiebot-ccpa',
    'cookiebot-ccpa-domain-group-id',
    'cookiebot-gtm',
    'cookiebot-gtm-id',
    'cookiebot-gtm-cookies',
    'cookiebot-data-layer',
    'cookiebot-gcm',
    'cookiebot-gcm-first-run',
    'cookiebot-gcm-url-passthrough',
    'cookiebot-gcm-cookies',
    'cookiebot-multiple-config',
    'cookiebot-second-banner-regions',
    'cookiebot-second-banner-id',
    'cookiebot-multiple-banners',
);

// Delete all config params
foreach ( $options as $option ) {
    delete_option( $option );
}
