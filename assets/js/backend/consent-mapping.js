'use strict';
(window => {
    jQuery('#cb-consent-api-reset-defaults').on('click',resetConsentMapping);
function resetConsentMapping() {
    if ( confirm( 'Are you sure you want to reset to default consent mapping?' ) ) {
        const resetButton = jQuery(this);
        if(resetButton.hasClass('uc-consent')){
            const categorySelectors = jQuery('.cb-category-selectors');
            categorySelectors.each(function(){
                const defaultCategory = jQuery(this).attr('data-default');
                jQuery(this).val(defaultCategory).change();
            });
        } else {
            jQuery('.cb-settings__consent__mapping-table input[type=checkbox]').each(function () {
                if (!jQuery(this).prop('disabled')) {
                    jQuery(this).prop('checked', jQuery(this).data('default-value'));
                }
            })
        }
        jQuery('p.submit #submit').addClass('enabled');
    }
}
})(window);