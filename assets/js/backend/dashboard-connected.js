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

	$( function () {
		initBannerToggle();
		initSuccessMoment();
	} );
})( jQuery );
