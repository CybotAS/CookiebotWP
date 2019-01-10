( function ( $ ) {

    var cookiebot_wpforms = {

        init: function () {
            $( document ).ready( cookiebot_wpforms.update_after_consent );
        },

        /**
         * Async wpfuuid after the visitor clicked on cookie accept
         */
        update_after_consent: function () {
            window.addEventListener( 'CookiebotOnAccept', function ( e ) {

                if ( Cookiebot
                    && Cookiebot.consent
                    && Cookiebot.consent.preferences
                    && window.wpforms
                    && !window.wpforms.getCookie( '_wpfuuid' ) ) {
                    window.wpforms.setUserIndentifier();
                }
            }, false );
        }
    };

    /**
     * Make global getGdprConsent function to check cookie consent status
     *
     * @return {boolean}
     */
    window.getGdprConsent = function getGdprConsent() {
        if ( Cookiebot && Cookiebot.consent && Cookiebot.consent.preferences ) {
            return true;
        }

        return false;
    };

    cookiebot_wpforms.init();


} )( jQuery );
