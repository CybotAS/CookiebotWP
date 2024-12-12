'use strict';
(window => {
    let ucConsentMappingKey;
    window.addEventListener('UC_CONSENT', (event)=> {cookiebot_update_consent_level(event)}, false);
    window.addEventListener('load', set_wp_consent_status, false);

    function set_wp_consent_status(){
        if (typeof wp_set_consent === 'function') {
            wp_set_consent('functional', 'allow'); //always allow functional cookies
            for (let key in cookiebot_category_mapping[ucConsentMappingKey]) {
                const strValue = cookiebot_category_mapping[ucConsentMappingKey][key] ? 'allow' : 'deny';
                wp_set_consent(key, strValue);
            }
        }
    }

    function cookiebot_update_consent_level(event) {
        const ucConsentDetails = event.detail.consent.setting.type;
        const ucConsentCategories = event.detail.categories;
        window.wp_consent_type = ucConsentDetails === 'US' ? 'optout' : 'optin';

        const consents = new Map([['n', 1], ['p', 1], ['s', 1], ['m', 1],]);

        if (typeof ucConsentCategories !== 'undefined') {
            consents.set('p', check_category_state( ucConsentCategories.functional ) );
            consents.set('s', check_category_state( ucConsentCategories.functional ) );
            consents.set('m', check_category_state( ucConsentCategories.marketing ) );
        }

        ucConsentMappingKey = Array.from(consents.entries())
            .map(([key, value]) => `${key}=${value}`)
            .join(';');

        set_wp_consent_status()
    }

    function check_category_state(category) {
        if(!category)
            return window.wp_consent_type === 'optin' ? 0 : 1;
        return category.state === 'ALL_DENIED' ? 0 : 1;
    }

})(window);