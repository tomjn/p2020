( function( $ ) {
	const showSubtree = ( $element ) => {
		$element
			.closest( '.page_item' )
			.find( 'ul.children:first' )
			.removeClass( 'subtree-collapsed' );
	};

	const hideSubtree = ( $element ) => {
		$element
			.closest( '.page_item' )
			.find( 'ul.children:first' )
			.addClass( 'subtree-collapsed' );
	};

	const toggleClass = ( $element ) => {
		if ( $element.hasClass( 'btn-collapsed' ) ) {
			$element.addClass( 'btn-expanded' ).removeClass( 'btn-collapsed' );
		} else {
			$element.addClass( 'btn-collapsed' ).removeClass( 'btn-expanded' );
		}
	};

	$( function() {
		$( document.body ).on( 'click', '.btn-collapsed', function() {
			showSubtree( $( this ) );
			toggleClass( $( this ) );
		} );

		$( document.body ).on( 'click', '.btn-expanded', function() {
			hideSubtree( $( this ) );
			toggleClass( $( this ) );
		} );
	} );
} )( jQuery );
