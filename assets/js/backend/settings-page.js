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
    activeSettingsTab();
    closeSubmitMsg();
    submitEnable();
    googleConsentModeUrlPassthrough();
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
    let cookieBlockingMode = cookiebot_settings.cookieBlockingMode;

    jQuery( 'input[type=radio][name=cookiebot-cookie-blocking-mode]' ).on( 'change', function () {
        if ( this.value === 'auto' && cookieBlockingMode !== this.value ) {
            jQuery( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 0.4 );
            jQuery( '#declaration-tag, #cookie-popup').addClass('disabled__item');
            jQuery( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', true )
        }
        if ( this.value === 'manual' && cookieBlockingMode !== this.value ) {
            jQuery( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 1 );
            jQuery( '#declaration-tag, #cookie-popup').removeClass('disabled__item');
            jQuery( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', false )
        }
        cookieBlockingMode = this.value
    } )
    if ( cookieBlockingMode === 'auto' ) {
        jQuery( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 0.4 )
        jQuery( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', true )
    }
}

function activeSettingsTab() {
    jQuery('.cb-settings__tabs__item').on('click', function(){
        let currentTab = jQuery('.cb-settings__tabs__item.active-item').data('tab');
        let tab = jQuery(this).data('tab');
        let tabSelector = '#'+tab;
        jQuery('.cb-settings__tabs__item.active-item, .cb-settings__tabs__content--item.active-item').removeClass('active-item');
        jQuery(this).addClass('active-item');
        jQuery(tabSelector).addClass('active-item');

        window.history.replaceState(null, null, '?page=cookiebot_settings&tab='+tab );
        let referrer = jQuery('input[name="_wp_http_referer"]');
        let referrerVal = referrer.val();
        if(referrerVal.indexOf('tab=')!==-1) {
            referrerVal = referrerVal.replace(currentTab,tab);
        }else{
            referrerVal += '&tab=' + tab;
        }
        referrer.val(referrerVal);
    });
}

function closeSubmitMsg() {
    jQuery('.cb-submit__msg').on('click',function(){
        jQuery(this).addClass('hidden');
    });
}

function submitEnable() {
    const initialValues = jQuery('form').serialize();
    const events = {
        change: 'input:not([type=text]), select',
        input: 'input[type="text"], textarea'
    };

    Object.entries(events).forEach(entry => {
        const [eventName, elements] = entry;
        jQuery(document).on(eventName,elements,{initialValues: initialValues},function(event){
            checkValues(event.data.initialValues)
        });
    });
}

function checkValues(initialValues){
    let submitBtn = jQuery('p.submit #submit');
    let newValues = jQuery('form').serialize();
    if(newValues !== initialValues) {
        submitBtn.addClass('enabled');
    }else{
        submitBtn.removeClass('enabled');
    }
}

function googleConsentModeUrlPassthrough() {
    jQuery('input#gcm').on('change', function () {
        jQuery(this)
          .parents('#consent-mode')
          .find('.cb-settings__config__item:has(input#gcm-url-pasthrough)')
          .toggle()
        const input = jQuery('input#gcm-url-pasthrough');
        const label = input.parents('label.switch-checkbox')[0];
        if (!label || !label.childNodes.length)
            return;
        label.childNodes[label.childNodes.length - 1].textContent = 'URL passthrough' + ' ' +
            (input.is(':checked') ? 'enabled' : 'disabled');
    });
    jQuery('input#gcm, input#gcm-url-pasthrough').on('change', function () {
        const input = jQuery(this);
        const label = input.parents('label.switch-checkbox')[0];
        if (!label || !label.childNodes.length)
            return;
        label.childNodes[label.childNodes.length - 1].textContent = (
          (input.attr('id') === 'gcm' ? 'Google Consent Mode' : 'URL passthrough') + ' ' +
          (input.is(':checked') ? 'enabled' : 'disabled')
        );
    });
}