( function( $ ) {
	const collapseLowerLevels = () => {
		$(
			'ul:not(.children)' +
				' > li.page_item_has_children' +
				' > .widget-p2020-pages-label' +
				' > .widget-p2020-pages-expand > button'
		).addClass( 'btn-expanded' );

		$(
			'ul.children' +
				' > li.page_item_has_children' +
				' > .widget-p2020-pages-label' +
				' > .widget-p2020-pages-expand > button'
		).addClass( 'btn-collapsed' );

		$( '.widget_p2020-pages-widget ' )
			.find( 'ul.children > li.page_item_has_children > ul.children' )
			.addClass( 'subtree-collapsed' );
	};

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
		collapseLowerLevels();

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
