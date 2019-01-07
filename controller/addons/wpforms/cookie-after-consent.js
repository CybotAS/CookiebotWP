( function ( $ ) {

    var cookiebot_wpforms = {

        init: function () {
            $( document ).ready( cookiebot_wpforms.update_after_consent );
        },

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
    }

    cookiebot_wpforms.init();


} )( jQuery );
