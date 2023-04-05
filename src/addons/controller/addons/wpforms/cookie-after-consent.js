( function ( $ ) {

	let cookiebot_wpforms = {

		init: function () {
			$( document ).ready( cookiebot_wpforms.update_after_consent );
		},

		/**
		 * Async wpfuuid after the visitor clicked on cookie accept
		 */
		update_after_consent: function () {
			window.addEventListener(
				'CookiebotOnAccept',
				function ( e ) {

					if ( Cookiebot
					&& Cookiebot.consent
					&& Cookiebot.consent.preferences
					&& window.wpforms
					&& ! window.wpforms.getCookie( '_wpfuuid' ) ) {
						window.wpforms.setUserIndentifier();
					}
				},
				false
			);
		}
	};

	/**
	 * Make global hasRequiredConsent function to check cookie consent status
	 *
	 * @return {boolean}
	 */
	window.hasRequiredConsent = function hasRequiredConsent() {
		if ( Cookiebot && Cookiebot.consent ) {
			let cookie_types = cookiebot_wpforms_settings.cookie_types;
			let required     = cookie_types.length;

			const consent_given = cookie_types.filter(
				function(cookie_type){
					return Cookiebot.consent[cookie_type];
				}
			);

			if ( required == consent_given.length ) {
				return true;
			}
		}

		return false;
	};

	cookiebot_wpforms.init();

} )( jQuery );
