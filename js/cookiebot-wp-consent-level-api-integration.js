'use strict';

window.wp_consent_type = 'optin';
window.addEventListener('CookiebotOnAccept', cookiebot_update_consent_level, false);
window.addEventListener('CookiebotOnDecline', cookiebot_update_consent_level, false);
window.addEventListener('load', cookiebot_update_consent_level, false);


function cookiebot_update_consent_level(e) {
	for (var key in cookiebot_category_mapping) {

		if (cookiebot_category_mapping.hasOwnProperty(key)) {           

			if( Cookiebot.consent[cookiebot_category_mapping[key]] != wp_has_consent(key) ) {

				var strValue = (Cookiebot.consent[cookiebot_category_mapping[key]]) ? 'allow' : 'deny';

				wp_set_consent(key,strValue);

			}

		}
		
	}

}
