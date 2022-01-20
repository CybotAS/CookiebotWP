/**
 * Load init function when the page is ready
 *
 * @since 1.8.0
 */
jQuery( document ).ready( init );

function init() {
    language_toggle();
    advanced_settings_toggle();
    cookie_blocking_mode();
}

function language_toggle() {
    jQuery( '#show_add_language_guide' ).on( 'click', function ( e ) {
        e.preventDefault()
        jQuery( '#add_language_guide' ).slideDown()
        jQuery( this ).hide()
    } )
    jQuery( '#hide_add_language_guide' ).on( 'click', function ( e ) {
        e.preventDefault()
        jQuery( '#add_language_guide' ).slideUp()
        jQuery( '#show_add_language_guide' ).show()
    } )

    jQuery( '#cookiebot-language' ).on( 'change', function () {
        if ( this.value === '' ) {
            jQuery( '#info_lang_autodetect' ).show()
            jQuery( '#info_lang_specified' ).hide()
        } else {
            jQuery( '#info_lang_autodetect' ).hide()
            jQuery( '#info_lang_specified' ).show()
        }
    } )
}

function advanced_settings_toggle() {
    jQuery( '.cookiebot_fieldset_header' ).on( 'click', function ( e ) {
        e.preventDefault()
        jQuery( this ).next().slideToggle()
        jQuery( this ).toggleClass( 'active' )
    } )
}

function resetConsentMapping() {
    if ( confirm( 'Are you sure you want to reset to default consent mapping?' ) ) {
        jQuery( '.consent_mapping_table input[type=checkbox]' ).each( function () {
            if ( !this.disabled ) {
                this.checked = ( jQuery( this ).data( 'default-value' ) === '1' )
            }
        } )
    }
    return false
}

function cookie_blocking_mode() {
    var cookieBlockingMode = cookiebot_settings.cookieBlockingMode;

    jQuery( 'input[type=radio][name=cookiebot-cookie-blocking-mode]' ).on( 'change', function () {
        if ( this.value === 'auto' && cookieBlockingMode !== this.value ) {
            jQuery( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 0.4 )
            jQuery( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', true )
        }
        if ( this.value === 'manual' && cookieBlockingMode !== this.value ) {
            jQuery( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 1 )
            jQuery( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', false )
        }
        cookieBlockingMode = this.value
    } )
    if ( cookieBlockingMode === 'auto' ) {
        jQuery( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 0.4 )
        jQuery( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', true )
    }
}