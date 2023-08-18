function resetConsentMapping() {
    if ( confirm( 'Are you sure you want to reset to default consent mapping?' ) ) {
        jQuery( '.cb-settings__consent__mapping-table input[type=checkbox]' ).each( function () {
            if ( !jQuery( this ).prop('disabled') ) {
                jQuery( this ).prop('checked', jQuery( this ).data( 'default-value' ) );
            }
        } )
        jQuery('p.submit #submit').addClass('enabled');
    }
    return false
}