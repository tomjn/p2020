( function ( $ ) {
	$( document ).ready( function () {
		$( '.o2-no-posts-post > .o2-post > p' ).append(
			'<div class="p2020-no-posts-return-home">' +
				`<a href="${ window.p2020FilterNoPosts.homeUrl }">` +
				window.p2020FilterNoPosts.homeMessage +
				'</a>' +
				'</div>'
		);

		if ( $( '.o2-no-posts-post' ).length ) {
			$( '.o2-app-controls' ).hide();
		}
	} );
} )( jQuery );
