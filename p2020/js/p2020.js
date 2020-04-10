( function( $ ) {
	$( document ).ready( function() {
		// Only do this once
		if ( $( 'body' ).hasClass( 'p2020-js-loaded' ) ) {
			return;
		}
		$( 'body' ).addClass( 'p2020-js-loaded' );

		$( '[data-mobile-menu-toggle]' ).click( function() {
			$( 'body' ).toggleClass( 'mobile-menu-is-visible' );
		} );

		// Is home page and not displaying O2 filtered content
		if (
			window.location.pathname === '/' &&
			window.location.search === ''
		) {
			$( '.o2-app-page-title' ).addClass( 'is-unfiltered-home' );
		}

		function moveNavIntoSidebar() {
			const $menu = $( 'nav#site-navigation' );
			if ( $menu.length && $menu.parents( 'header#masthead' ).length ) {
				$menu.prependTo( '#sidebar' );
				$menu.wrap(
					'<aside class="widget" id="o2-responsive-nav"></aside>'
				);
			}
		}

		function moveNavOutOfSidebar() {
			const $menu = $( 'nav#site-navigation' );
			if (
				$menu.length &&
				0 === $menu.parents( 'header#masthead' ).length
			) {
				$( 'nav#site-navigation' ).appendTo( 'header#masthead' );
				$( '#o2-responsive-nav' ).remove();
			}
		}

		if ( 'undefined' !== typeof enquire ) {
			// "Tablet" max-width also defined in inc/scss/partials/ui/_responsive.scss
			enquire.register( 'screen and (max-width:876px)', {
				match() {
					moveNavIntoSidebar();
				},

				unmatch() {
					moveNavOutOfSidebar();
				},
			} );
		}
	} );
} )( jQuery );
