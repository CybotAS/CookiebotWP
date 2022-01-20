/* globals cookieTypes, Cookiebot, jQuery */
window.addEventListener(
	'CookiebotOnTagsExecuted',
	function (e) {
		if (cookieTypes.every( (cookieType) => Cookiebot.consent[cookieType] )) {
			jQuery( '.wp-video-shortcode__disabled' ).addClass( 'wp-video-shortcode' ).removeClass( 'wp-video-shortcode__disabled' )
			jQuery( '.wp-audio-shortcode__disabled' ).addClass( 'wp-audio-shortcode' ).removeClass( 'wp-audio-shortcode__disabled' )
			if (window.wp && window.wp.mediaelement && window.wp.mediaelement.initialize) {
				window.wp.mediaelement.initialize()
			}
		}
	},
	false
)
