(function ( $ ) {
	"use strict";

	function showPath( path ) {
		$( '[data-cb-path-intro]' ).each( function () {
			this.hidden = $( this ).data( 'cb-path-intro' ) !== path;
		} );
		$( '[data-cb-steps]' ).each( function () {
			this.hidden = $( this ).data( 'cb-steps' ) !== path;
		} );
	}

	function playVideo() {
		var $video = $( this ).closest( '[data-cb-video]' );
		var $iframe = $( '<iframe>', {
			src: 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent( $video.data( 'cb-video' ) ) + '?autoplay=1',
			title: $video.closest( '.cb-dash__video-card' ).find( '.cb-dash__h3' ).text(),
			allow: 'autoplay; encrypted-media; picture-in-picture',
			allowfullscreen: true
		} );
		$video.empty().append( $iframe );
	}

	var i18n = ( window.cookiebot_setup && window.cookiebot_setup.i18n ) || {};

	/**
	 * One connect form: idle → connecting → (redirect | invalid | save_failed | not_set_up).
	 * The server decides validity; the client never short-circuits an empty field.
	 */
	function ConnectForm( $root ) {
		this.$root = $root;
		this.$form = $root.find( '[data-cb-connect-form]' );
		this.$input = $root.find( '[data-cb-connect-input]' );
		this.$button = $root.find( '[data-cb-connect-button]' );
		this.$label = $root.find( '[data-cb-connect-button-label]' );
		this.$spinner = $root.find( '.cb-dash__spinner' );
		this.$message = $root.find( '[data-cb-connect-message]' );
		this.$messageText = $root.find( '[data-cb-connect-message-text]' );
		this.$status = $root.find( '[data-cb-connect-status]' );
		this.force = false;

		this.$form.on( 'submit', this.submit.bind( this ) );
		this.$input.on( 'input', this.reset.bind( this ) );
	}

	ConnectForm.prototype.setButton = function ( label ) {
		this.$label.text( label );
	};

	ConnectForm.prototype.showMessage = function ( kind, text, isHtml ) {
		this.$root.removeClass( 'is-error is-warning' ).addClass( 'is-' + kind );
		if ( isHtml ) {
			this.$messageText.html( text );
		} else {
			this.$messageText.text( text );
		}
		this.$message.prop( 'hidden', false );
	};

	ConnectForm.prototype.reset = function () {
		if ( this.$root.hasClass( 'is-connecting' ) ) {
			return;
		}
		this.force = false;
		this.$root.removeClass( 'is-error is-warning' );
		this.$message.prop( 'hidden', true );
		this.$messageText.empty();
		this.setButton( i18n.connect );
	};

	ConnectForm.prototype.setConnecting = function ( connecting ) {
		this.$root.toggleClass( 'is-connecting', connecting );
		this.$input.prop( 'disabled', connecting );
		this.$button.prop( 'disabled', connecting );
		this.$spinner.prop( 'hidden', ! connecting );
		this.$status.text( connecting ? i18n.connecting : '' );
		if ( connecting ) {
			this.setButton( i18n.connecting );
		}
	};

	ConnectForm.prototype.submit = function ( event ) {
		var self = this;
		event.preventDefault();
		if ( this.$root.hasClass( 'is-connecting' ) ) {
			return;
		}

		this.setConnecting( true );
		$.post( cookiebot_setup.ajax_url, {
			action: 'cookiebot_connect_cbid',
			nonce: cookiebot_setup.nonce,
			cbid: this.$input.val(),
			force: this.force ? '1' : ''
		} )
			.done( function ( response ) {
				if ( response && response.success && response.data && response.data.redirect ) {
					window.location = response.data.redirect;
					return;
				}
				self.setConnecting( false );
				self.handleError( response && response.data ? response.data : {} );
			} )
			.fail( function () {
				self.setConnecting( false );
				self.handleError( { code: 'save_failed' } );
			} );
	};

	ConnectForm.prototype.handleError = function ( data ) {
		if ( data.code === 'invalid' ) {
			this.force = false;
			this.showMessage( 'error', i18n.invalid, false );
			this.setButton( i18n.connect );
		} else if ( data.code === 'not_set_up' ) {
			this.force = true;
			this.showMessage( 'warning', i18n.not_set_up.split( '%1$s' ).join( data.domain || '' ), false );
			this.setButton( i18n.connect_anyway );
		} else {
			this.showMessage( 'error', i18n.save_failed_html, true );
			this.setButton( i18n.try_again );
		}
		this.$input.trigger( 'focus' );
	};

	// "Create account" opens the sign-up in a new tab (default link behaviour) and unlocks step 2.
	function unlockConnectStep() {
		var $steps = $( this ).closest( '[data-cb-steps]' );
		$steps.find( '[data-cb-new-account]' ).attr( 'data-cb-step-done', '1' );
		$steps.find( '[data-cb-connect][data-cb-connect-locked="1"]' ).each( function () {
			$( this ).attr( 'data-cb-connect-locked', '0' )
				.find( '[data-cb-connect-input], [data-cb-connect-button]' ).prop( 'disabled', false );
		} );
		$.post( cookiebot_setup.ajax_url, {
			action: 'cookiebot_signup_clicked',
			nonce: cookiebot_setup.nonce
		} );
	}

	// "Where is it?" help: opens on hover, keyboard focus or tap; stays open while the pointer is over it.
	function initWhereIsIt( $where ) {
		var $trigger = $where.find( '[data-cb-where-trigger]' );
		var $popover = $where.find( '[data-cb-where-popover]' );

		function toggle( open ) {
			$popover.prop( 'hidden', ! open );
			$trigger.attr( 'aria-expanded', open ? 'true' : 'false' );
		}

		$where.on( 'mouseenter', function () {
			toggle( true );
		} );
		$where.on( 'mouseleave', function () {
			toggle( false );
		} );
		$trigger.on( 'focus', function () {
			toggle( true );
		} );
		// Tap on touch devices; a mouse click lands after focus already opened it.
		$trigger.on( 'click', function () {
			toggle( true );
		} );
		$where.on( 'focusout', function ( event ) {
			if ( ! $.contains( $where[ 0 ], event.relatedTarget ) ) {
				toggle( false );
			}
		} );
		$where.on( 'keydown', function ( event ) {
			if ( event.key === 'Escape' && ! $popover.prop( 'hidden' ) ) {
				event.stopPropagation();
				toggle( false );
			}
		} );
	}

	$( function () {
		$( '[data-cb-where]' ).each( function () {
			initWhereIsIt( $( this ) );
		} );

		$( '[data-cb-signup]' ).on( 'click', unlockConnectStep );

		$( '[data-cb-connect]' ).each( function () {
			new ConnectForm( $( this ) );
		} );

		// "Create a new account" is the default on every visit, even if the browser restored another choice.
		$( '[data-cb-path][value="new"]' ).prop( 'checked', true );
		showPath( 'new' );

		$( '[data-cb-path]' ).on( 'change', function () {
			showPath( this.value );
		} );
		$( '[data-cb-video-play]' ).on( 'click', playVideo );
	} );
})( jQuery );
