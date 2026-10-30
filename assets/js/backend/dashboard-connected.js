(function ( $ ) {
	"use strict";

	var TOAST_MS = 5000;

	// Same option as "Show banner on site" on Configure settings (cookiebot-banner-enabled).
	function initBannerToggle() {
		var $toggle = $( '[data-cb-banner-toggle]' );
		var $notice = $( '[data-cb-banner-off-notice]' );

		$toggle.on( 'change', function () {
			var enabled = this.checked;
			$notice.prop( 'hidden', enabled );

			$.post( cookiebot_connected.ajax_url, {
				action: 'cookiebot_set_banner_enabled',
				nonce: cookiebot_connected.account_nonce,
				value: enabled ? '1' : '0'
			} ).fail( function () {
				$toggle.prop( 'checked', ! enabled );
				$notice.prop( 'hidden', ! enabled );
			} );
		} );
	}

	function initSuccessMoment() {
		var $toast = $( '[data-cb-toast]' );
		if ( ! $toast.length ) {
			return;
		}

		var close = function () {
			$toast.prop( 'hidden', true );
		};
		var timer = window.setTimeout( close, TOAST_MS );
		$toast.find( '[data-cb-toast-close]' ).on( 'click', function () {
			window.clearTimeout( timer );
			close();
		} );

		var reduceMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
		if ( typeof window.confetti === 'function' && ! reduceMotion ) {
			window.confetti( {
				particleCount: 160,
				spread: 100,
				origin: { y: 0.6 },
				zIndex: 99999
			} );
		}
	}

	// Confirm dialog: Cancel, close icon, backdrop click and Esc all cancel; focus returns to "Disconnect".
	function initDisconnect() {
		var $trigger = $( '[data-cb-disconnect]' );
		var dialog = $( '[data-cb-disconnect-dialog]' ).get( 0 );
		if ( ! $trigger.length || ! dialog || typeof dialog.showModal !== 'function' ) {
			return;
		}
		var $confirm = $( dialog ).find( '[data-cb-dialog-confirm]' );

		$trigger.on( 'click', function () {
			dialog.showModal();
		} );
		$( dialog ).find( '[data-cb-dialog-cancel]' ).on( 'click', function () {
			dialog.close();
		} );
		// A click on the dialog element itself (not its content) is a click on the backdrop.
		$( dialog ).on( 'click', function ( event ) {
			if ( event.target === dialog ) {
				dialog.close();
			}
		} );
		$( dialog ).on( 'close', function () {
			$trigger.trigger( 'focus' );
		} );

		$confirm.on( 'click', function () {
			$confirm.prop( 'disabled', true );
			$.post( cookiebot_connected.ajax_url, {
				action: 'cookiebot_disconnect_cbid',
				nonce: cookiebot_connected.nonce
			} )
				.done( function ( response ) {
					if ( response && response.success && response.data && response.data.redirect ) {
						window.location = response.data.redirect;
						return;
					}
					$confirm.prop( 'disabled', false );
				} )
				.fail( function () {
					$confirm.prop( 'disabled', false );
				} );
		} );
	}

	$( function () {
		initBannerToggle();
		initSuccessMoment();
		initDisconnect();
	} );
})( jQuery );
