/**
 * Load init function when the page is ready
 *
 * @since 1.8.0
 */
jQuery( document ).ready( init );

/**
 * Init settings
 *
 * @since 1.8.0
 */
function init() {
	placeholder_select_language();
	placeholder_toggle();
	button_add_placeholder_language();
	button_delete_language();
	tooltip();
	show_advanced_options();
	edit_embed_regex();
	set_default_embed_regex();
	closeSubmitMsg();
	submitEnable();
}

/**
 * Enable/disable placeholder
 *
 * @since 1.8.0
 */
function placeholder_toggle() {
	jQuery( document ).on(
		'change',
		'.placeholder_enable',
		function () {
			const status = jQuery( this ).is( ':checked' );
			const addon  = jQuery( this ).data( 'addon' );

			if ( status ) {
				placeholder_enable( addon );
			} else {
				placeholder_disable( addon );
			}
		}
	);
}

/**
 * Placeholder disable
 *
 * @param addon
 *
 * @since 1.8.0
 */
function placeholder_disable( addon ) {
	jQuery( '.placeholder[data-addon="' + addon + '"]' ).hide();
}

/**
 * Placeholder enable
 *
 * @param addon
 *
 * @since 1.8.0
 */
function placeholder_enable( addon ) {
	jQuery( '.placeholder[data-addon="' + addon + '"]' ).show();
}

/**
 * Add language for placeholder
 *
 * @since 1.8.0
 */
function button_add_placeholder_language() {
    const initialValues = jQuery('form').serialize();
	jQuery( '.btn_add_language' ).on(
		'click',
		function ( e ) {
			e.preventDefault();

			const addon = jQuery( this ).data( 'addon' );

			add_placeholder_language_content( addon );

            const newValues = jQuery('form').serialize();
            if(newValues!==initialValues)
                jQuery('p.submit #submit').addClass('enabled');

			return false;
		}
	);
}

/**
 * Add placeholder language div
 *
 * @param addon
 *
 * @since 1.8.0
 */
function add_placeholder_language_content( addon ) {
	const data = jQuery( '.placeholder[data-addon="' + addon + '"] .placeholder_content:first' )[ 0 ].outerHTML;

	jQuery( '.placeholder[data-addon="' + addon + '"] .add_placeholder_language' ).before( data );

	jQuery( '.placeholder[data-addon="' + addon + '"] .placeholder_content:last select' ).after( php.remove_link );

	tooltip();
}

/**
 * Replace select and textarea name
 *
 * @since 1.8.0
 */
function placeholder_select_language() {
	jQuery( document ).on(
		'change',
		'.placeholder_select_language',
		function () {
			const new_value   = jQuery( this ).val();
			let select_name = jQuery( this ).attr( 'name' );

			// get new name
			select_name  = select_name.substr( 0, select_name.lastIndexOf( '[' ) );
			select_name += '[' + new_value + ']';

			// rename select field
			jQuery( this ).attr( 'name', select_name );

			// rename textarea
			jQuery( this ).parent().next().find( 'textarea' ).attr( 'name', select_name );
		}
	)
}

/**
 * Delete language
 *
 * @since 1.8.0
 */
function button_delete_language() {
    const initialValues = jQuery('form').serialize();
	jQuery( document ).on(
		'click',
		'.submitdelete',
		function ( e ) {
			e.preventDefault();

			jQuery( this ).parent().parent().remove();

            let newValues = jQuery('form').serialize();
            if(newValues===initialValues)
                jQuery('p.submit #submit').removeClass('enabled');

			return false;
		}
	);
}

/**
 * Show tooltip
 *
 * @since 1.8.0
 */
function tooltip() {
	jQuery( '.help-tip' ).tipTip(
		{
			'maxWidth': 300,
			'fadeIn': 50,
			'fadeOut': 50,
			'delay': 200
		}
	);
}

/**
 * Show advanced options
 *
 * @since 2.4.5
 */
function show_advanced_options() {
	jQuery( document ).on(
		'click',
		'.show_advanced_options',
		function( e ) {
			e.preventDefault();

			/** Toggle displaying advanced options **/
			jQuery( this ).next().toggle();

			return false;
		}
	);
}

function edit_embed_regex() {
	jQuery( document ).on(
		'click',
		'#edit_embed_regex',
		function(e) {
			e.preventDefault();

			/** Get the textarea for the embed regex **/
			let embed_regex = document.getElementById( "embed_regex" );

			/** Remove the disable attribute in the textarea **/
			embed_regex.disabled = false;

			/** Make the Reset default button back visible **/
			let default_button = document.getElementById( 'btn_default_embed_regex' );
			default_button.classList.remove( 'hidden' );

			jQuery( this ).hide();

			return false;
		}
	);
}

/**
 * Set default embed regex
 *
 * @since 2.4.5
 */
function set_default_embed_regex() {
	jQuery( document ).on(
		'click',
		'#btn_default_embed_regex',
		function( e ) {
			e.preventDefault();

			/** get the value of the default embed regex **/
			let default_regex = jQuery( "#default_embed_regex" ).val();

			/** Update the textarea of the embed regex **/
			jQuery( '#embed_regex' ).val( default_regex );

			return false;
		}
	);
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
