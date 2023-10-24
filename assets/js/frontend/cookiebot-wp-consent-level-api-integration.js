'use strict';
(window => {
    window.wp_consent_type = 'optin';
    window.addEventListener('CookiebotOnConsentReady', cookiebot_update_consent_level, false);
    window.addEventListener('load', cookiebot_on_load_consent_level, false);

    function cookiebot_on_load_consent_level() {
        if (typeof Cookiebot !== 'undefined' && Cookiebot.consented === true) {
            cookiebot_update_consent_level();
        }
    }

    function cookiebot_update_consent_level() {
        wp_set_consent('functional', 'allow'); //always allow functional cookies

        const consents = new Map([['n', 1], ['p', 1], ['s', 1], ['m', 1],]);

        if (typeof Cookiebot !== 'undefined') {
            consents.set('p', Cookiebot.consent.preferences ? 1 : 0);
            consents.set('s', Cookiebot.consent.statistics ? 1 : 0);
            consents.set('m', Cookiebot.consent.marketing ? 1 : 0);
        }

        let consentMappingKey = Array.from(consents.entries())
            .map(([key, value]) => `${key}=${value}`)
            .join(';');

        for (let key in cookiebot_category_mapping[consentMappingKey]) {
            const strValue = cookiebot_category_mapping[consentMappingKey][key] ? 'allow' : 'deny';
            wp_set_consent(key, strValue);
        }
    }

})(window);
