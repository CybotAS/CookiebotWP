'use strict';
(window => {
    let ucConsentMappingKey = {};
    window.addEventListener('UC_CONSENT', (event)=> {cookiebot_update_consent_level(event)}, false);
    window.addEventListener('load', set_wp_consent_status, false);

    function set_wp_consent_status(){
        if (typeof wp_set_consent === 'function') {
            wp_set_consent('functional', 'allow'); //always allow functional cookies
            Object.values(cookiebot_category_mapping).forEach(category => {
                const strValue = ucConsentMappingKey[category.toString()];
                wp_set_consent(category.toString(), strValue);
            });
        }
    }

    function cookiebot_update_consent_level(event) {
        const ucConsentDetails = typeof event.detail !== 'undefined' ? event.detail.consent.setting.type : false;
        const ucConsentCategories = typeof event.detail !== 'undefined' ? event.detail.categories : false;
        window.wp_consent_type = ucConsentDetails === 'US' ? 'optout' : 'optin';

        const consents = new Map([['preferences', 'allow'], ['marketing', 'allow']]);

        consents.set('preferences', check_category_state( ucConsentCategories.functional ) );
        consents.set('marketing', check_category_state( ucConsentCategories.marketing ) );

        Array.from(consents.entries()).map(([key, value]) => {ucConsentMappingKey[key] = value});

        set_wp_consent_status()
    }

    function check_category_state(category) {
        if(!category)
            return window.wp_consent_type === 'optin' ? 'deny' : 'allow';
        return category.state === 'ALL_DENIED' ? 'deny' : 'allow';
    }

})(window);