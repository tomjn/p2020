( function( $ ) {
	$( document ).ready( function() {
		$( '.p2020-filter-read-more-post .o2-post' ).append(
			'<div class="p2020-filter-read-more-mask">' +
				'<button class="p2020-filter-read-more-trigger">' +
				window.p2020FilterReadMore.readMoreMessage +
				'</button>' +
				'</div>'
		);

		// Regardless of Show/Hide comment threads setting, for this view,
		//    we will force display comments threads.
		$( '.o2-post-comments' ).show();
		$( '.o2-display-comments-toggle' ).hide();
	} );

	$( document.body ).on(
		'click',
		'.p2020-filter-read-more-trigger',
		function() {
			$( this )
				.closest( '.post' )
				.removeClass( 'p2020-filter-read-more-post' );

			$( this )
				.closest( '.p2020-filter-read-more-mask' )
				.remove();
		}
	);
} )( jQuery );
