'use strict';

window.wp_consent_type = 'optin';
window.addEventListener('CookiebotOnAccept', cookiebot_update_consent_level, false);
window.addEventListener('CookiebotOnDecline', cookiebot_update_consent_level, false);
window.addEventListener('load', cookiebot_update_consent_level, false);

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

    if ((wp_has_consent(key) && strValue !== 'allow') || (!wp_has_consent(key) && strValue === 'allow')) {
      wp_set_consent(key, strValue);
    }
  }
}
