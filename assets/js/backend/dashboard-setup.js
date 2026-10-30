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

	$( function () {
		// "Create a new account" is the default on every visit, even if the browser restored another choice.
		$( '[data-cb-path][value="new"]' ).prop( 'checked', true );
		showPath( 'new' );

		$( '[data-cb-path]' ).on( 'change', function () {
			showPath( this.value );
		} );
		$( '[data-cb-video-play]' ).on( 'click', playVideo );
	} );
})( jQuery );
