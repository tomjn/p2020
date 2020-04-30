( function( $ ) {
	const trimComment = ( commentContent ) => {
		$( commentContent )
			.children( ':first-child' )
			.addClass( 'p2020-comment-read-more-excerpt' );
		$( commentContent )
			.children( ':not(.o2-comment-actions):nth-child(n+2)' )
			.hide();
	};

	const untrimComment = ( commentContent ) => {
		$( commentContent )
			.children()
			.removeClass( 'p2020-comment-read-more-excerpt' );
		$( commentContent )
			.children()
			.show();
	};

	const shouldTrimComment = ( commentContent ) => {
		const trueHeight = $( commentContent ).get( 0 ).scrollHeight;
		trimComment( $( commentContent ) );
		const trimmedHeight = $( commentContent ).get( 0 ).scrollHeight;
		untrimComment( $( commentContent ) );

		return trueHeight > trimmedHeight;
	};

	const addReadMoreToPosts = () => {
		$( '.p2020-post-read-more .o2-post' ).append(
			'<div class="p2020-post-read-more-mask">' +
				'<button class="p2020-post-read-more-trigger">' +
				window.p2020FilterReadMore.readPost +
				'</button>' +
				'</div>'
		);
	};

	const forceDisplayComments = () => {
		$( '.o2-post-comments' ).show();
		$( '.o2-display-comments-toggle' ).hide();
	};

	const addReadMoreToComment = ( commentContent ) => {
		$( commentContent ).prepend(
			'<div class="p2020-comment-read-more-mask">' +
				'<a href="/" class="p2020-comment-read-more-trigger">' +
				window.p2020FilterReadMore.readComment +
				'</a>' +
				'</div>'
		);
	};

	const trimSeenComments = () => {
		$( '.p2020-comment-read-more > .comment-content' ).each( function() {
			if ( shouldTrimComment( $( this ) ) ) {
				trimComment( $( this ) );
				addReadMoreToComment( $( this ) );
			}
		} );
	};

	$( document ).ready( function() {
		addReadMoreToPosts();
		forceDisplayComments();
		trimSeenComments();
	} );

	$( document.body ).on(
		'click',
		'.p2020-post-read-more-trigger',
		function() {
			$( this )
				.closest( '.post' )
				.removeClass( 'p2020-post-read-more' );

			$( this )
				.closest( '.p2020-post-read-more-mask' )
				.remove();
		}
	);

	$( document.body ).on(
		'click',
		'.p2020-comment-read-more-trigger',
		function() {
			untrimComment( $( this ).closest( '.comment-content' ) );
			$( this )
				.closest( '.p2020-comment-read-more-mask' )
				.remove();

			return false;
		}
	);
} )( jQuery );
