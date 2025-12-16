/**
 * Load init function when the page is ready
 *
 * @since 4.2.10
 */
jQuery( document ).ready( cbInit );

function cbInit() {
    jQuery( document ).on( 'click', 'tr[data-slug="cookiebot"] .cb-deactivate-action', event => deactivateCookiebot( event ) );
    jQuery( document ).on( 'click', '#cb-review__close', event => closeSurveyPopup( event ) );
    jQuery( document ).on( 'submit', '#cb-review__form', event => submitSurveyPopup( event ) );
    hideSubmitMessage();
    selectorListeners();
}

/**
 * Displays popup form.
 */
function deactivateCookiebot( e ) {
    e.preventDefault();

    let deactivationLink = e.target.href;

    populatePopup();
    jQuery( '#cb-review__skip' ).attr( 'href', deactivationLink );
    jQuery( '#cookiebot-review-popup-container' ).addClass( 'cb-opened cb-filled' );
}

function populatePopup(){
    if(jQuery( '#cookiebot-review-popup-container' ).hasClass( 'cb-filled' )){
        return;
    }

    let popupFormContainer = document.createElement('div');
    const header = renderPopupHeader();
    const form = renderPopupForm();
    popupFormContainer.setAttribute('id','cookiebot-popup');
    popupFormContainer.appendChild(header);
    popupFormContainer.appendChild(form);

    jQuery('#cookiebot-review-popup-container').append(popupFormContainer);
}

function renderPopupHeader() {
    let popupHeader = document.createElement('div');
    let headerLogoContainer = document.createElement('div');
    let headerLogo = document.createElement('img');
    let headerTitle = document.createElement('h2');
    let headerClose = document.createElement('div');
    popupHeader.classList.add('cb-review__header');
    headerLogoContainer.classList.add('cb-review__logo');
    headerLogo.setAttribute('src', cb_survey.logo);
    headerLogo.setAttribute('alt', 'Cookiebot by Usercentrics');
    headerTitle.setAttribute('id', 'cb-review__title');
    headerTitle.innerHTML = cb_survey.popup_header_title;
    headerClose.setAttribute('id', 'cb-review__close');
    headerClose.classList.add('dashicons','dashicons-dismiss');
    popupHeader.appendChild(headerLogoContainer).appendChild(headerLogo);
    popupHeader.appendChild(headerTitle);
    popupHeader.appendChild(headerClose);
    return popupHeader;
}

function renderPopupForm(){
    let popupForm = document.createElement('form');
    popupForm.setAttribute('id', 'cb-review__form');
    popupForm.setAttribute('method',  'POST');
    let firstMsg = document.createElement('p');
    firstMsg.innerText = cb_survey.first_msg;
    const labelContainer = renderFormLabels();
    const consentLabel = renderConsentLabel();
    const consentActions = renderPopupActions();
    popupForm.appendChild(firstMsg);
    popupForm.appendChild(labelContainer);
    popupForm.appendChild(consentLabel);
    popupForm.appendChild(consentActions);
    return popupForm
}

function renderFormLabels(){
    let labelsContainer = document.createElement('div');
    let options = cb_survey.options;
    options.forEach((option)=>{
        let labelContainer = document.createElement('div');
        let labelChild = document.createElement('label');
        let labelInput = document.createElement('input');
        labelChild.classList.add('cb-review__form--item');
        labelInput.setAttribute('type', 'radio');
        labelInput.setAttribute('name', 'cookiebot-review-option');
        labelInput.setAttribute('value', option.value);
        let labelText = document.createElement('span');
        labelText.innerText = option.text;
        labelChild.appendChild(labelInput);
        labelChild.appendChild(labelText);
        labelContainer.appendChild(labelChild);

        if(option.value === '8'){
            let extraContainer = document.createElement('div');
            let labelChild = document.createElement('label');
            let labelText = document.createElement('span');
            labelText.innerText = 'Tell us more (optional)...';
            labelChild.appendChild(labelText);
            extraContainer.appendChild(labelChild);

            let extraText = document.createElement('textarea');
            extraContainer.classList.add('cb-review__form--item__custom');
            extraText.setAttribute('id','cb-review__other-description');
            extraText.setAttribute('name','other-description');
            extraText.setAttribute('placeholder',option.extra);
            extraText.setAttribute('row','1');
            labelContainer.appendChild(extraContainer).appendChild(extraText);
        }
        labelsContainer.appendChild(labelContainer);
    })
    return labelsContainer;
}

function renderConsentLabel(){
    let labelContainer = document.createElement('div');
    labelContainer.classList.add('consent-item');
    labelContainer.classList.add('show-consent');
    let consentLabel = document.createElement('label');
    consentLabel.classList.add('cb-review__form--item');
    let consentInput = document.createElement('input');
    consentInput.setAttribute('id','cb-review__debug-reason');
    consentInput.setAttribute('type','checkbox');
    consentInput.setAttribute('name','cookiebot-review-debug');
    consentInput.setAttribute('value','true');
    consentInput.setAttribute('data-custom-field','true');
    let consentDescription = document.createElement('span');
    let consentOpt = document.createElement('b');
    consentOpt.innerText = cb_survey.consent.optional;
    let consentLink = document.createElement('a');
    consentLink.setAttribute('href', 'mailto:unsubscribe@usercentrics.com');
    consentLink.innerText = 'unsubscribe@usercentrics.com';
    consentDescription.appendChild(consentOpt);
    let firstText = document.createElement('span');
    firstText.innerText = cb_survey.consent.first;
    consentDescription.appendChild(firstText);
    consentDescription.appendChild(document.createElement('br'));
    let secondText = document.createElement('span');
    secondText.innerText = cb_survey.consent.second;
    consentDescription.appendChild(secondText);
    consentDescription.appendChild(consentLink);
    consentLabel.appendChild(consentInput);
    consentLabel.appendChild(consentDescription);
    labelContainer.appendChild(consentLabel);
    return labelContainer;
}

function renderPopupActions(){
    let reviewContainer = document.createElement('div');
    let reviewAlert = document.createElement('div');
    reviewAlert.setAttribute('id','cb-review__alert');
    reviewAlert.innerText = cb_survey.alert;
    let reviewActionContainer = document.createElement('div');
    reviewActionContainer.classList.add('cb-review__actions');
    let skipCta = document.createElement('a');
    skipCta.setAttribute('id','cb-review__skip');
    skipCta.setAttribute('href','#');
    skipCta.innerText = cb_survey.actions.skip;
    let submitCta = document.createElement('input');
    submitCta.setAttribute('type', 'submit');
    submitCta.setAttribute('id', 'cb-review__submit');
    submitCta.setAttribute('value', cb_survey.actions.submit);
    let reviewPolicy = document.createElement('p');
    reviewPolicy.classList.add('cb-review__policy');
    reviewPolicy.innerText = 'See our ';
    let policyLink = document.createElement('a');
    policyLink.setAttribute('href', 'https://www.cookiebot.com/en/privacy-policy/?utm_source=wordpress&utm_medium=referral&utm_campaign=banner');
    policyLink.setAttribute('target', '_blank');
    policyLink.setAttribute('rel', 'noopener');
    policyLink.innerText = 'Privacy Policy';
    reviewPolicy.appendChild(policyLink);
    let reviewInput = document.createElement('input');
    reviewInput.setAttribute('type','hidden');
    reviewInput.setAttribute('name','cookiebot-review-send');
    reviewInput.setAttribute('value','Cookiebot_Review_Send');
    reviewActionContainer.appendChild(skipCta);
    reviewActionContainer.appendChild(submitCta);

    reviewContainer.appendChild(reviewAlert);
    reviewContainer.appendChild(reviewActionContainer);
    reviewContainer.appendChild(reviewPolicy);
    reviewContainer.appendChild(reviewInput);
    return reviewContainer;
}

/**
 * Close popup form.
 */

function closeSurveyPopup(e) {
    const popup = jQuery(e.target).closest('.cookiebot-popup-container');
    popup.removeClass('cb-opened');
    jQuery('#cb-review__alert').removeClass('show-alert');
    document.getElementById('cb-review__form').reset();
}

/**
 * Popup submit
 */
function submitSurveyPopup(e){
    e.preventDefault();
    const deactivateLink = jQuery( '#cb-review__skip' ).attr( 'href' );
    const button = jQuery('#cb-review__submit', '#cb-review__form');
    if (button.hasClass('disabled')) {
        return;
    }
    
    const option = jQuery('input[type="radio"]:checked', '#cb-review__form');
    if(0 === option.length){
        jQuery('#cb-review__alert').addClass('show-alert');
        return;
    }

    // Add loading state
    const loadingOverlay = document.createElement('div');
    loadingOverlay.className = 'loading-overlay';
    loadingOverlay.innerHTML = `
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <h2>Thank you for being part of our journey.</h2>
            <p>We\'re deactivating the plugin and saving your feedback.</p>
        </div>
    `;
    document.body.classList.add('has-loading-overlay');
    document.body.appendChild(loadingOverlay);

    const otherReason = jQuery('#cb-review__other-description');
    const debugReason = jQuery('#cb-review__debug-reason');


    // Load Amplitude SDK and track deactivation
    const script = document.createElement('script');
    script.src = 'https://cdn.eu.amplitude.com/script/3573fa11b8c5b4bcf577ec4c8e9d5cb6.js';
    script.async = true;
    script.onload = function() {
        const amplitude = window.amplitude;
        amplitude.init('3573fa11b8c5b4bcf577ec4c8e9d5cb6', {
            serverZone: 'EU',
            fetchRemoteConfig: true,
            defaultTracking: false
        });

        // Track deactivation event
        amplitude.track('Plugin Deactivated', {
            reason: option.closest('label').text().trim(),
            additional_info: otherReason.val() ? otherReason.val().trim() : '',
        });
    };
    document.head.appendChild(script);

    jQuery.ajax({
        url: cb_ajax.ajax_url,
        type: 'POST',
        data: {
            action: 'cb_submit_survey',
            reason_id: (0 === option.length) ? null : option.val(),
            reason_text: (0 === option.length) ? 'none' : option.closest('label').text(),
            reason_info: (0 !== otherReason.length) ? otherReason.val().trim() : '',
            reason_debug: (debugReason?.length > 0) ? debugReason[0].checked : 'false',
            survey_nonce: cb_survey.survey_nonce,
            survey_check: 'ODUwODA1'
        },
        beforeSend: function() {
            button.addClass('disabled');
            button.attr('value','Please wait...');
        },
        complete: function(response) {
            const code = JSON.parse(response.responseText).code;
            const msg = JSON.parse(response.responseText).data;

            if(code===400||code===401){
                jQuery('#cb-review__alert').text(msg).addClass('show-alert');
                button.removeClass('disabled');
                return;
            }
            window.location.href = deactivateLink;
        }
    });
}

function hideSubmitMessage(){
    let submitMsg = jQuery('.cb-submit__msg');
    if(submitMsg){
        setTimeout(function(){
            submitMsg.fadeOut();
        },2000)
    }
}

function selectorListeners(){
    openItemList();
    closeitemList();
    selectListItem();
    searchListItem();
}

function openItemList() {
    jQuery(document).on('click','.cb-settings__selector__container .cb-settings__selector-selector',function(){
        jQuery('.cb-settings__selector-list-container').addClass('hidden');
        jQuery(this).siblings('.cb-settings__selector-list-container').removeClass('hidden');
    });
}

function closeitemList() {
    jQuery(document).on('click','.cb-settings__selector__container .cb-settings__selector-veil',function(){
        jQuery(this).parent('.cb-settings__selector-list-container').addClass('hidden');
        jQuery(this).siblings('.cb-settings__selector-search').val('').trigger('keyup');
        jQuery(this).siblings('.cb-settings__selector-list').scrollTop(0);
    });
}

function selectListItem() {
    jQuery(document).on('click','.cb-settings__selector__container .cb-settings__selector-list-item',function(){
        const item = jQuery(this);
        const mainParent = item.closest('.cb-settings__selector__container');
        const itemList = item.parent('.cb-settings__selector-list');
        const itemValue = item.data('value');
        const itemAttr = 'cookiebot-tcf-disallowed['+itemValue+']';
        const itemName = item.text();

        if(!itemList.data('multiple')){
            itemList.find('.selected').removeClass('selected');
        }

        item.addClass('selected');
        mainParent.find('.cb-settings__selector-selector').text(itemName);
        mainParent.find('.cb-settings__selector__container-input').val(itemValue).attr('name',itemAttr).trigger('change');
        mainParent.find('.cb-settings__selector-search').val('').trigger('keyup');
        itemList.scrollTop(0);

        item.closest('.cb-settings__selector-list-container').addClass('hidden');
    });
}

function searchListItem() {
    jQuery(document).on('keyup','.cb-settings__selector-search',function(){
        const searchName = jQuery(this).val().toLowerCase();
        const itemList = jQuery(this).siblings('.search-list');

        itemList.children().each(function(){
            const item = jQuery(this);
            if(searchName.length>0) {
                const itemName = item.text().trim().toLowerCase();
                if(itemName.indexOf(searchName) != -1){
                    item.removeClass('hidden');
                }else{
                    item.addClass('hidden');
                }
            }else{
                item.removeClass('hidden');
            }
        });
    });
}