( function( $ ) {
	const trimComment = ( $commentContent ) => {
		$commentContent
			.children( ':first-child' )
			.addClass( 'p2020-comment-read-more-excerpt' );
		$commentContent
			.children( ':not(.o2-comment-actions):nth-child(n+2)' )
			.hide();
	};

	const untrimComment = ( $commentContent ) => {
		$commentContent
			.children()
			.removeClass( 'p2020-comment-read-more-excerpt' );
		$commentContent.children().show();
	};

	const shouldTrimComment = ( $commentContent ) => {
		const trueHeight = $commentContent.get( 0 ).scrollHeight;
		trimComment( $commentContent );
		const trimmedHeight = $commentContent.get( 0 ).scrollHeight;
		untrimComment( $commentContent );

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

	const addReadMoreToComment = ( $commentContent ) => {
		$commentContent.prepend(
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

	const summarizeCommentThread = ( $commentThread, $summary ) => {
		const commentsCount = 1 + $commentThread.find( '.o2-comment' ).length;
		const avatarsHtml = $( '<div/>' )
			.append(
				$commentThread
					.find( '.avatar' )
					.clone()
					.removeClass( 'grav-hijack' )
			)
			.html();

		// Update count
		const currentCount =
			parseInt(
				$summary.find( '.p2020-comments-summary-count > .count' ).html()
			) || 0;
		$summary
			.find( '.p2020-comments-summary-count' )
			.html(
				`<span class="count">${ currentCount + commentsCount }</span> ${
					window.p2020FilterReadMore.moreComments
				}`
			);

		// Update avatars
		$summary
			.find( '.p2020-comments-summary-avatars' )
			.append( avatarsHtml );
	};

	const insertSummaryContainer = ( insertBeforeElement ) => {
		const summary = $(
			'<button class="p2020-comments-summary">' +
				'<span class="p2020-comments-summary-count"></span>' +
				'<span class="p2020-comments-summary-avatars"></span>' +
				'</button>'
		);
		summary.insertBefore( insertBeforeElement );

		return summary;
	};

	const getSummaryContainer = ( $commentThread ) => {
		const $summary = $commentThread
			.prevUntil( '.p2020-active-thread' )
			.filter( '.p2020-comments-summary' )
			.first();
		if ( ! $summary.html() ) {
			return insertSummaryContainer( $commentThread );
		}

		return $summary;
	};

	const collapseCommentThreads = () => {
		$( '.o2-post-comments > .o2-comment' ).each( function() {
			const hasUnreadComment =
				$( this ).hasClass( 'p2020-unread-comment' ) ||
				$( this ).find( '.p2020-unread-comment' ).length > 0;
			if ( ! hasUnreadComment ) {
				const summary = getSummaryContainer( $( this ) );
				summarizeCommentThread( $( this ), summary );
				$( this ).addClass( 'p2020-inactive-thread' );
			} else {
				$( this ).addClass( 'p2020-active-thread' );
			}
		} );
	};

	$( document ).ready( function() {
		addReadMoreToPosts();
		forceDisplayComments();
		trimSeenComments();
		collapseCommentThreads();
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

	$( document.body ).on( 'click', '.p2020-comments-summary', function() {
		$( this )
			.nextUntil( '.p2020-active-thread' )
			.removeClass( 'p2020-inactive-thread' );

		$( this ).hide();

		return false;
	} );
} )( jQuery );
