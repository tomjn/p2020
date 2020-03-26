( function( $ ) {
	$( document ).ready( function() {
		// Only do this once
		if ( $( 'body' ).hasClass( 'breathe-js-loaded' ) ) {
			return;
		}
		$( 'body' ).addClass( 'breathe-js-loaded' );

		$( '#secondary-toggle, #primary-modal' ).click( function() {
			$( 'body' ).toggleClass( 'responsive-show' );
		} );

		function moveNavIntoSidebar() {
			const $menu = $( 'nav#site-navigation' );
			if ( $menu.length && $menu.parents( 'header#masthead' ).length ) {
				$menu.prependTo( '#secondary-content' );
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
