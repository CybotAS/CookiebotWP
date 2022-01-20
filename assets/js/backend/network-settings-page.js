jQuery( document ).ready( function ( $ ) {
    var cookieBlockingMode = cookiebotNetworkSettings.cbm
    $( 'input[type=radio][name=cookiebot-cookie-blocking-mode]' ).on( 'change', function () {
        if ( this.value === 'auto' && cookieBlockingMode !== this.value ) {
            $( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 0.4 )
            $( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', true )
        }
        if ( this.value === 'manual' && cookieBlockingMode !== this.value ) {
            $( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 1 )
            $( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', false )
        }
        cookieBlockingMode = this.value
    } )
    if ( cookieBlockingMode === 'auto' ) {
        $( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 0.4 )
        $( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', true )
    }
} )