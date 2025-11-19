/**
 * Load init function when the page is ready
 *
 * @since 1.8.0
 */
jQuery( document ).ready( init );

function init() {
    ruleset_id();
    show_ruleset_selector();
    remove_account();
    network_id_override();
    language_toggle();
    advanced_settings_toggle();
    cookie_blocking_mode();
    activeSettingsTab();
    initialCheckActiveVendors();
    closeSubmitMsg();
    submitEnable();
    googleConsentModeOptions();
    tcfOptions();
    vendorListRequire();
    selectAllListItem();
    deselectAllListItem();
    onAddRestriction();
    showRestrictionPurposes();
    onVendorSelection();
    removeRestriction();
    generate_shortcode();
}

function ruleset_id(){
    const cbidField = jQuery( '#cookiebot-cbid' );
    const cbidCheck = jQuery( '.cookiebot-cbid-check' );
    let fieldTimer;
    let fieldInterval = 3000;

    cbidField.on('keyup', function () {
        clearTimeout(fieldTimer);
        cbidField.addClass('check-progress');
        cbidCheck.removeClass('check-pass').addClass('check-progress');
        fieldTimer = setTimeout(show_ruleset_selector, fieldInterval);
    });

    cbidField.on('keydown', function () {
        clearTimeout(fieldTimer);
    });
}

function show_ruleset_selector() {
    const cbidField = jQuery( '#cookiebot-cbid' );
    const cbidCheck = jQuery( '.cookiebot-cbid-check' );
    const cbidRulesetSelector = jQuery('#cookiebot-ruleset-id-selector');
    const cbidError = jQuery('.cookiebot-cbid-error');

    cbidCheck.removeClass('check-progress');
    cbidField.removeClass('check-progress');

    if(!cbidField.val()){
        cbidCheck.removeClass('check-pass');
        cbidRulesetSelector.addClass('hidden');
        cbidError.addClass('hidden');
        jQuery('.cookiebot-cbid-container p.submit #submit').addClass('disabled');
        return;
    }

    // Validate that the field has exactly 14 or 36 characters
    const fieldLength = cbidField.val().length;
    if(fieldLength !== 14 && fieldLength !== 36){
        cbidCheck.removeClass('check-pass');
        cbidRulesetSelector.addClass('hidden');
        cbidError.removeClass('hidden');
        jQuery('.cookiebot-cbid-container p.submit #submit').addClass('disabled');
        return;
    }

    // Valid input - hide error message
    cbidError.addClass('hidden');

    !check_id_frame() ? cbidRulesetSelector.removeClass('hidden') : cbidRulesetSelector.addClass('hidden');

    jQuery('.cookiebot-cbid-container p.submit #submit').removeClass('disabled');
}

function check_id_frame(){
    const cbFrameReg = /[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/;
    return cbFrameReg.test(jQuery( '#cookiebot-cbid' ).val())
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
            if(jQuery( '#gcm-cookie-categories').is(':visible')){ jQuery( '#gcm-cookie-categories').hide() };
            jQuery( 'input[type=radio][name=cookiebot-script-tag-uc-attribute], input[name=cookiebot-nooutput]' ).prop( 'disabled', true );
            jQuery( '#cb-settings__gtm__cookie-types input[type=checkbox]' ).prop( 'disabled', true );
            jQuery( '#cb-settings__gcm__cookie-types input[type=checkbox]' ).prop( 'disabled', true );
        }
        if ( this.value === 'manual' && cookieBlockingMode !== this.value ) {
            jQuery( '#cookiebot-setting-async, #cookiebot-setting-hide-popup' ).css( 'opacity', 1 );
            jQuery( '#declaration-tag, #cookie-popup, #gcm-cookie-categories, #gtm-cookie-categories').removeClass('disabled__item');
            if(!jQuery( '#gcm-cookie-categories').is(':visible') && jQuery('input#gcm').is(':checked')){ jQuery( '#gcm-cookie-categories').show() };
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
    const initialValues = jQuery('input[name!=_wp_http_referer]','form').serialize();
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
    let submitBtn = jQuery('.cb-settings__header p.submit #submit');
    let newValues = jQuery('input[name!=_wp_http_referer]','form').serialize();
    if(newValues !== initialValues) {
        submitBtn.addClass('enabled');
    }else{
        submitBtn.removeClass('enabled');
    }
    checkActiveVendors();
}

function googleConsentModeOptions() {
    jQuery('input#gcm').on('change', function () {
        const parent = jQuery(this).parents('#consent-mode');
        parent.find('.cb-settings__config__item:has(input#gcm-url-pasthrough)').toggle();
        const gcmCookiesCategoriesContainer = parent.find('.cb-settings__config__item:has(ul#cb-settings__gcm__cookie-types)');
        if(!gcmCookiesCategoriesContainer.hasClass('disabled__item')){
            gcmCookiesCategoriesContainer.is(':visible') ? gcmCookiesCategoriesContainer.hide() : gcmCookiesCategoriesContainer.show();
        }else{
            gcmCookiesCategoriesContainer.hide();
        }
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

function checkIabActive() {
    return jQuery('input#cookiebot-iab:checked').length;
}

function initialCheckActiveVendors() {
    if(!checkIabActive()){
        return;
    }

    const allVendorInputChecked = jQuery('input[name^="cookiebot-tcf-vendors"]:checked').length;
    if(!allVendorInputChecked && check_id_frame()) {
        jQuery('.vendor-selected-items-message').removeClass('hidden');
        jQuery('.cb-vendor-alert__msg').removeClass('hidden');
    }
}

function checkActiveVendors() {
    if(!checkIabActive()){
        return;
    }

    if(!check_id_frame()) {
        return;
    }

    let submitBtn = jQuery('.cb-settings__header p.submit #submit');
    const allVendorInputChecked = jQuery('input[name^="cookiebot-tcf-vendors"]:checked').length;
    if(!allVendorInputChecked) {
        jQuery('.vendor-selected-items-message').removeClass('hidden');
        jQuery('.cb-vendor-alert__msg').removeClass('hidden');
        submitBtn.removeClass('enabled');
    }else{
        jQuery('.vendor-selected-items-message').addClass('hidden');
        jQuery('.cb-vendor-alert__msg').addClass('hidden');
        if(!submitBtn.hasClass('enabled')) {
            submitBtn.addClass('enabled');
        }
    }
}

function selectAllListItem(){
    jQuery(document).on('click','.cb-settings__selector-all',function(){
        const itemList = jQuery(this).siblings('.search-list');
        itemList.children().each(function(){
            jQuery(this).find('input').prop('checked', true);
        })
        checkActiveVendors();
    })
}

function deselectAllListItem(){
    jQuery(document).on('click','.cb-settings__selector-none',function(){
        const itemList = jQuery(this).siblings('.search-list');
        itemList.children().each(function(){
            jQuery(this).find('input').prop('checked', false);
        })
        checkActiveVendors();
    })
}

function vendorListRequire(){
    jQuery( document ).on('change', 'input[name^="cookiebot-tcf-vendors"]', function () {
        checkActiveVendors();
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
    const initialValues = jQuery('input[name!=_wp_http_referer]','form').serialize();
    let submitBtn = jQuery('.cb-settings__header p.submit #submit');
    jQuery(document).on('click','.cb-settings__vendor__restrictions .remove__restriction', function(){
        const restriction = jQuery(this).closest( '.cb-settings__vendor__restrictions' );
        const allRestrictions = jQuery( '.cb-settings__vendor__restrictions' );
        if(allRestrictions.length === 1){
            const selector = restriction.find('.cb-settings__selector-selector');
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
        let newValues = jQuery('input[name!=_wp_http_referer]','form').serialize();
        if(newValues !== initialValues) {
            submitBtn.addClass('enabled');
        }else{
            submitBtn.removeClass('enabled');
        }
    });
}

function generate_shortcode(){
    jQuery('#cookiebot-embedding').on('change', function(){
        const typeOptions = jQuery('#cookiebot-embedding-type option');
        const className = jQuery(this).val();
        typeOptions.each(function(){
            if(jQuery(this).hasClass(className)){
                jQuery(this).removeClass('hide-option');
            }else{
                jQuery(this).addClass('hide-option');
            }
        });
        jQuery('#cookiebot-embedding-type option:not(.hide-option)').each(function(index){
            if(index>0)
                return;
            jQuery(this).prop('selected', true);
        });
    });

    jQuery('#cookiebot-embedding-type').on('change', function(){
        const typeName = jQuery(this).val();
        if(typeName==='service-specific'){
            jQuery( '#cookiebot-embedding-single-service-container' ).removeClass('hide-container');
        }else{
            jQuery( '#cookiebot-embedding-single-service-container' ).addClass('hide-container');
            jQuery( '#cookiebot-embedding-single-service' ).val('');
        }
    });

    const embedInputs = jQuery('#cookiebot-tcf-toggle, #cookiebot-embedding, #cookiebot-embedding-type, #cookiebot-embedding-single-service');
    embedInputs.each(function (){
        jQuery(this).on('change keyup', function(){
            let shortcode = '[uc_embedding';
            const className = jQuery('#cookiebot-embedding').val();
            const toggleEnabled = jQuery('#cookiebot-tcf-toggle');
            const typeName = jQuery('#cookiebot-embedding-type').val();
            const serviceName = jQuery('#cookiebot-embedding-single-service').val();


            switch (className) {
                case 'tcf' : shortcode += ' class="tcf"'; break;
                default : shortcode += ' class="gdpr"';
            }
            switch (toggleEnabled.is(':checked')) {
                case true : shortcode += ' show-toggle="true"'; break;
                default : shortcode += ' show-toggle="false"';
            }
            switch (typeName) {
                case 'category' : shortcode += ' type="category"'; break;
                case 'category-only' : shortcode += ' type="category-only"'; break;
                case 'service-specific' : shortcode += ' type="service-specific"'; break;
                case 'purposes' : shortcode += ' type="purposes"'; break;
                case 'vendors' : shortcode += ' type="vendors"'; break;
                default : shortcode += ' type="all"';
            }
            if(serviceName.length > 0){
                shortcode += ' service="' + serviceName + '"';
            }
            shortcode += ']';
            jQuery('#embedding-shortcode').attr('value',shortcode);
        });
    });
}

function remove_account(){
    const removeCta = jQuery('#cookiebot-cbid-reset-dialog');
    const cbidAlert = jQuery('.cb-cbid-alert__msg');
    const cbidOverrideAlert = jQuery('.cb-cbid-subsite-alert__msg');
    const confirmCta = jQuery('#cookiebot-cbid-reset, #cookiebot-subsite-cbid-reset');
    const cancelCta = jQuery('#cookiebot-cbid-cancel, #cookiebot-subsite-cbid-cancel');
    removeCta.on('click', function(){
        if(cbidOverrideAlert.length !== 0){
            cbidOverrideAlert.removeClass('hidden');
        }else{
            cbidAlert.removeClass('hidden');
        }
        removeCta.addClass('disabled');
    });
    confirmCta.on('click', function(){
        jQuery('#cookiebot-cbid').val('').removeClass('cbid-active');
        jQuery('#cookiebot-cbid-override').prop('checked',false);
        
        // If the account was disconnected set blocking mode to 'auto' and 'Hide cookie popup' to false.
        jQuery('input[type=radio][name=cookiebot-cookie-blocking-mode][value=auto]').prop('checked', true);
        jQuery('input[name=cookiebot-nooutput]').prop( 'checked', false )
    
        jQuery('.cb-settings__header p.submit #submit').click();
    });
    cancelCta.on('click', function(){
        cbidAlert.addClass('hidden');
        cbidOverrideAlert.addClass('hidden');
        jQuery('#cookiebot-cbid-override').prop('checked',true);
        removeCta.removeClass('disabled');
    });
}

function network_id_override() {
    const overrideCheck = jQuery('#cookiebot-cbid-override');
    overrideCheck.on('change', function(){
        jQuery('.cb-settings__header p.submit #submit').addClass('disabled');
        if(overrideCheck.is(':checked') && overrideCheck.hasClass('cb-no-network')){
            jQuery('#cookiebot-cbid-network-dialog').addClass('hidden');
            jQuery('.cookiebot-cbid-container p.submit #submit').addClass('disabled').removeClass('hidden');
            jQuery('.cookiebot-cbid-container #cookiebot-cbid').removeClass('cbid-active').attr('placeholder', '').val('');
        }
        if(!overrideCheck.is(':checked')){
            if(!overrideCheck.hasClass('cb-no-network')){
                jQuery('.cb-cbid-subsite-alert__msg').removeClass('hidden');
            }else{
                jQuery('#cookiebot-cbid-network-dialog').removeClass('hidden');
                jQuery('.cookiebot-cbid-container p.submit #submit').addClass('hidden');
                jQuery('.cookiebot-cbid-container #cookiebot-cbid').addClass('cbid-active').val('').attr('placeholder', jQuery('.cookiebot-cbid-container #cookiebot-cbid').attr('data-network'));
            }
        }
    });
}

function copyEmbedShortcode() {
    const t = document.getElementById( 'embedding-shortcode' )
    t.select()
    t.setSelectionRange( 0, 99999 )
    document.execCommand( 'copy' )
}

// Event Listeners
document.getElementById('banner-close-btn')?.addEventListener('click', async () => {
    const banner = document.getElementById('banner-live-notice')
    if (banner) banner.remove();
});
