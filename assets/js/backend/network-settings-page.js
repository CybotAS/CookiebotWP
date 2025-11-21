jQuery( document ).ready( function ( $ ) {
    let cookieBlockingMode = cookiebotNetworkSettings.cbm
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

    ruleset_id();
    remove_account();
} )

function check_id_frame(){
    const cbFrameReg = /[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/;
    return cbFrameReg.test(jQuery( '#cookiebot-cbid' ).val())
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
    const cbidSubmit = jQuery('.cookiebot-cbid-container p.submit #submit');
    const cbidError = jQuery('.cookiebot-cbid-error');

    cbidCheck.removeClass('check-progress');
    cbidField.removeClass('check-progress');

    if(!cbidField.val()){
        cbidCheck.removeClass('check-pass');
        cbidRulesetSelector.addClass('hidden');
        cbidSubmit.addClass('disabled');
        cbidError.addClass('hidden');
        return;
    }

    // Validate that the field has exactly 14 or 36 characters
    const fieldLength = cbidField.val().length;
    if(fieldLength !== 14 && fieldLength !== 36){
        cbidCheck.removeClass('check-pass');
        cbidRulesetSelector.addClass('hidden');
        cbidSubmit.addClass('disabled');
        cbidError.removeClass('hidden');
        return;
    }

    // Valid input - hide error message
    cbidError.addClass('hidden');

    !check_id_frame() ? cbidRulesetSelector.removeClass('hidden') : cbidRulesetSelector.addClass('hidden');

    cbidSubmit.removeClass('disabled');
}

function remove_account(){
    const removeCta = jQuery('#cookiebot-cbid-reset-dialog');
    const cbidAlert = jQuery('.cb-cbid-alert__msg');
    const confirmCta = jQuery('#cookiebot-cbid-reset');
    const cancelCta = jQuery('#cookiebot-cbid-cancel');
    removeCta.on('click', function(){    
        cbidAlert.removeClass('hidden');
        removeCta.addClass('disabled');
    });
    confirmCta.on('click', function(){
        jQuery('#cookiebot-cbid').val('').removeClass('cbid-active');

        // If the account was disconnected set blocking mode to 'auto' and 'Hide cookie popup' to false.
        jQuery('input[type=radio][name=cookiebot-cookie-blocking-mode][value=auto]').prop('checked', true);
        jQuery('input[name=cookiebot-nooutput]').prop( 'checked', false )
        
        jQuery('.cb-settings__header p.submit #submit').click();
    });
    cancelCta.on('click', function(){
        cbidAlert.addClass('hidden');
        removeCta.removeClass('disabled');
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