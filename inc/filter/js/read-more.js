( function ( $ ) {
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

	const addReadMoreToPost = ( $post ) => {
		if (
			$post.find( '.p2020-post-read-more-mask' ).length < 1 &&
			! $post.closest( 'article' ).hasClass( 'tag-p2-xpost' )
		) {
			$post.append(
				'<div class="p2020-post-read-more-mask">' +
					'<button class="p2020-post-read-more-trigger">' +
					window.p2020FilterReadMore.readPost +
					'</button>' +
					'</div>'
			);
		}
	};

	const addReadMoreToPosts = () => {
		$( '.p2020-post-read-more .o2-post' ).each( function () {
			addReadMoreToPost( $( this ) );
		} );
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

	const trimSeenComments = ( articleId = '' ) => {
		let selector = '.p2020-comment-read-more > .comment-content';
		if ( articleId !== '' ) selector = '#' + articleId + ' ' + selector;
		$( selector ).each( function () {
			if ( shouldTrimComment( $( this ) ) ) {
				trimComment( $( this ) );
				addReadMoreToComment( $( this ) );
			}
		} );
	};

	const summarizeCommentThread = ( $commentThread, $summary ) => {
		// Update count
		const commentsCount = 1 + $commentThread.find( '.o2-comment' ).length;
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

		const $avatars = $commentThread.find( '.avatar' );
		$avatars.each( function () {
			const $avatar = $( this ).clone().removeClass( 'grav-hijack' );

			let gravatarHash = '';
			const match = $avatar
				.attr( 'src' )
				.match( /.*avatar\/([a-zA-Z0-9]{8})/ ) || [ null, null ];
			if ( match[ 0 ] && match[ 1 ] ) {
				gravatarHash = match[ 1 ];
				$avatar.addClass( `gravatar-${ gravatarHash }` );
			}

			const avatarHtml = $( '<div/>' ).append( $avatar ).html();

			// Update avatars
			const $commentSummaryAvatars = $summary.find(
				'.p2020-comments-summary-avatars'
			);

			if (
				gravatarHash === '' ||
				$commentSummaryAvatars.find( `img.gravatar-${ gravatarHash }` )
					.length < 1
			) {
				$commentSummaryAvatars.append( avatarHtml );
			}
		} );
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

	const collapseCommentThreads = ( articleId = '' ) => {
		let selector = '.o2-post-comments > .o2-comment';
		if ( articleId !== '' ) selector = '#' + articleId + ' ' + selector;
		$( selector ).each( function () {
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

	const processNewPosts = () => {
		const targetNode = document.querySelector( '#content' );
		const config = { childList: true, subtree: true };
		const callback = function ( mutationsList ) {
			for ( const mutation of mutationsList ) {
				if (
					mutation.addedNodes.length > 0 &&
					mutation.target.className === 'o2-posts'
				) {
					const articleId = mutation.addedNodes[ 0 ].id;
					addReadMoreToPost(
						$( '#' + articleId ).find( '.o2-post' )
					);
					trimSeenComments( articleId );
					collapseCommentThreads( articleId );
				} else if (
					mutation.addedNodes.length > 0 &&
					mutation.removedNodes.length > 0 &&
					mutation.target.className === 'o2-post'
				) {
					// Content changed (e.g. p2tenberg opening/closing), expand post
					untrimPost( $( mutation.target ) );
				}
			}
		};
		const observer = new MutationObserver( callback );
		observer.observe( targetNode, config );
	};

	const untrimPost = ( $post ) => {
		$post.closest( 'article' ).removeClass( 'p2020-post-read-more' );
		$post.find( '.p2020-post-read-more-mask' ).remove();
	};

	$( function () {
		addReadMoreToPosts();
		forceDisplayComments();
		trimSeenComments();
		collapseCommentThreads();

		processNewPosts();
	} );

	$( document.body ).on(
		'click',
		'.p2020-post-read-more-trigger',
		function () {
			untrimPost( $( this ).closest( '.o2-post' ) );
		}
	);

	$( document.body ).on(
		'click',
		'.p2020-comment-read-more-trigger',
		function () {
			untrimComment( $( this ).closest( '.comment-content' ) );
			$( this ).closest( '.p2020-comment-read-more-mask' ).remove();

			return false;
		}
	);

	$( document.body ).on( 'click', '.p2020-comments-summary', function () {
		$( this )
			.nextUntil( '.p2020-active-thread' )
			.removeClass( 'p2020-inactive-thread' );

		$( this ).hide();

		return false;
	} );
} )( jQuery );
