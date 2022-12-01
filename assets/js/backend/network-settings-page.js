jQuery( document ).ready( function ( $ ) {
    var cookieBlockingMode = cookiebotNetworkSettings.cbm
    $( 'input[type=radio][name=cookiebot-cookie-blocking-mode]' ).on( 'change', function () {
        if ( this.value === 'auto' && cookieBlockingMode !== this.value ) {
            $( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 0.4 )
            $( '#declaration-tag').addClass('disabled__item');
            $( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', true )
        }
        if ( this.value === 'manual' && cookieBlockingMode !== this.value ) {
            $( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 1 )
            $( '#declaration-tag, #cookie-popup').removeClass('disabled__item');
            $( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', false )
        }
        cookieBlockingMode = this.value
    } )
    if ( cookieBlockingMode === 'auto' ) {
        $( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 0.4 )
        $( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', true )
    }

    jQuery('.cb-submit__msg').on('click',function(){
        jQuery(this).addClass('hidden');
    });

    jQuery(':input').change(
        function(){
            jQuery('p.submit #submit').addClass('enabled');
        }
    );
} )