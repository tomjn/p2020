( function ( $ ) {
	const $followBtn = $( '.p2020-follow' );

	const isLoadingClass = 'is-loading';

	// Make Follow/Unfollow requests
	const sendRequest = function ( request ) {
		request.callback.before();
		$.post( window.ajaxurl, {
			action: request.action,
			_wpnonce: window.p2020FollowData.nonce,
			source: 'p2020_sidebar',
			blog_id: window._currentSiteId,
		} )
			.done( function () {
				request.callback.success();
			} )
			.fail( function () {
				return false;
			} )
			.always( function () {
				request.callback.after();
			} );
	};

	const showLoader = () => {
		$followBtn.addClass( isLoadingClass );
	};

	const removeLoader = () => {
		$followBtn.removeClass( isLoadingClass );
	};

	const updateButton = () => {
		if ( $followBtn.hasClass( 'follow' ) ) {
			$followBtn.html(
				'<span>' + window.p2020FollowData.followingText + '</span>'
			);
			$followBtn.removeClass( 'follow' );
			$followBtn.addClass( 'unfollow' );
		} else if ( $followBtn.hasClass( 'unfollow' ) ) {
			$followBtn.html(
				'<span>' + window.p2020FollowData.followText + '</span>'
			);
			$followBtn.addClass( 'follow' );
			$followBtn.removeClass( 'unfollow' );
		}
	};

	// Follow Site
	$followBtn.on( 'click', function ( e ) {
		e.preventDefault();

		if ( window.p2020FollowData.isUserLoggedIn ) {
			let action = '';
			if ( $( this ).hasClass( 'follow' ) ) {
				action = 'ab_subscribe_to_blog';
			} else if ( $( this ).hasClass( 'unfollow' ) ) {
				action = 'ab_unsubscribe_from_blog';
			}

			if ( action === '' ) {
				return;
			}

			const request = {
				action,
				callback: {
					before: showLoader,
					after: removeLoader,
					success: updateButton,
				},
			};

			sendRequest( request );
		}
	} );

	// Show only when fully loaded
	$followBtn.show();
} )( jQuery );
