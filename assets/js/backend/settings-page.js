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
    googleConsentModeOptions();
    tcfOptions();
    onAddRestriction();
    showRestrictionPurposes();
    onVendorSelection();
    removeRestriction();
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

function cookie_blocking_mode() {
    let cookieBlockingMode = cookiebot_settings.cookieBlockingMode;

    jQuery( 'input[type=radio][name=cookiebot-cookie-blocking-mode]' ).on( 'change', function () {
        if ( this.value === 'auto' && cookieBlockingMode !== this.value ) {
            jQuery( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 0.4 );
            jQuery( '#declaration-tag, #cookie-popup, #gcm-cookie-categories, #gtm-cookie-categories').addClass('disabled__item');
            jQuery( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', true );
            jQuery( '#cb-settings__gtm__cookie-types input[type=checkbox]' ).prop( 'disabled', true );
            jQuery( '#cb-settings__gcm__cookie-types input[type=checkbox]' ).prop( 'disabled', true );
        }
        if ( this.value === 'manual' && cookieBlockingMode !== this.value ) {
            jQuery( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 1 );
            jQuery( '#declaration-tag, #cookie-popup, #gcm-cookie-categories, #gtm-cookie-categories').removeClass('disabled__item');
            jQuery( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', false );
            jQuery( '#cb-settings__gtm__cookie-types input[type=checkbox]' ).prop( 'disabled', false );
            jQuery( '#cb-settings__gcm__cookie-types input[type=checkbox]' ).prop( 'disabled', false );
        }
        cookieBlockingMode = this.value;
    } )
    if ( cookieBlockingMode === 'auto' ) {
        jQuery( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 0.4 );
        jQuery( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', true );
        jQuery( '#cb-settings__gtm__cookie-types input[type=checkbox]' ).prop( 'disabled', true );
        jQuery( '#cb-settings__gcm__cookie-types input[type=checkbox]' ).prop( 'disabled', true );
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

function googleConsentModeOptions() {
    jQuery('input#gcm').on('change', function () {
        const parent = jQuery(this).parents('#consent-mode');
        parent.find('.cb-settings__config__item:has(input#gcm-url-pasthrough)').toggle();
        parent.find('.cb-settings__config__item:has(ul#cb-settings__gcm__cookie-types)').toggle();
        const passthroughInput = jQuery('input#gcm-url-pasthrough');
        const passthroughLabel = passthroughInput.parents('label.switch-checkbox')[0];
        if (!passthroughLabel || !passthroughLabel.childNodes.length)
            return;
        passthroughLabel.childNodes[passthroughLabel.childNodes.length - 1].textContent = 'URL passthrough' + ' ' +
            (passthroughInput.is(':checked') ? 'enabled' : 'disabled');
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

function tcfOptions() {
    jQuery('input#cookiebot-iab').on('change', function () {
        const parent = jQuery(this).parents('#iab');
        parent.find('.cb-settings__config__item:has(input.tcf-option)').toggle();
    });
}

function onAddRestriction() {
    jQuery('.restriction-vendor-add').on('click', function () {
        const allCurrentVendors = jQuery( '.cb-settings__vendor__restrictions' ).length;
        const baseElement = jQuery( '.cb-settings__vendor__restrictions:last' );
        let newElement = baseElement.clone();
        newElement.find('.vendor-selector').attr('name','');
        newElement.find('option').removeAttr('selected');
        newElement.find('.purpose-item').each(function(index){
            const itemId = 'cookiebot-vendorx'+( allCurrentVendors + 1 )+'-purposes'+(index+1);
            jQuery(this).attr('name','');
            jQuery(this).attr('id',itemId);
            jQuery(this).removeAttr('checked');
            jQuery(this).parent().attr('for',itemId);
        });

        newElement.insertAfter(baseElement);
    });
}

function showRestrictionPurposes() {
    jQuery(document).on('click','.vendor-purposes-show', function () {
        const parent = jQuery(this).parents('.cb-settings__vendor__restrictions');
        parent.find('.vendor-purposes-restrictions').toggle();
    });
}

function onVendorSelection() {
    jQuery(document).on('change', '.cb-settings__selector__container-input', function () {
        const vendorId = jQuery(this).val();
        const parent = jQuery(this).parents('.cb-settings__vendor__restrictions');
        const vendorPurposes = parent.find('.vendor-purposes-restrictions .purpose-item');
        const fieldName = 'cookiebot-tcf-disallowed[$s]';
        const purposeFieldName = '[purposes][]';

        jQuery(this).attr('name', fieldName.replace('$s', vendorId));
        vendorPurposes.each(function(index){
            const purposeAttribute = fieldName.replace('$s', vendorId) + purposeFieldName;
            const itemId = 'cookiebot-vendor' + vendorId + '-purposes' + (index+1);
            jQuery(this).attr('name', purposeAttribute);
            jQuery(this).attr('id', itemId);
            jQuery(this).parent().attr('for',itemId);
        });
    });
}

function removeRestriction(){
    const initialValues = jQuery('form').serialize();
    let submitBtn = jQuery('p.submit #submit');
    jQuery(document).on('click','.cb-settings__vendor__restrictions .remove__restriction', function(){
        const restriction = jQuery(this).closest( '.cb-settings__vendor__restrictions' );
        const allRestrictions = jQuery( '.cb-settings__vendor__restrictions' );
        if(allRestrictions.length === 1){
            const selector = restriction.find('.cb-settings__selector-selector');
            console.log(selector.data('placeholder'));
            selector.text(selector.data('placeholder'));
            restriction.find('.cb-settings__selector__container-input').val('');
            restriction.find('.cb-settings__selector__container-input').attr('name','');
            restriction.find('.cb-settings__selector-list-item.selected').removeClass('selected');
            const vendorPurposes = restriction.find('.purpose-item');
            vendorPurposes.each(function(){
                jQuery(this).prop( 'checked', false );
                jQuery(this).attr( 'name', '' );
            });
        }else{
            restriction.remove();
        }
        let newValues = jQuery('form').serialize();
        if(newValues !== initialValues) {
            submitBtn.addClass('enabled');
        }else{
            submitBtn.removeClass('enabled');
        }
    });
}