( function( $ ) {
	const addToggleSubtreeIcons = () => {
		const toggleBtn =
			'.widget-p2020-pages-label > .widget-p2020-pages-expand > button';

		$(
			`li.page_item_has_children.current_page_ancestor > ${ toggleBtn },
			li.page_item_has_children.current_page_item > ${ toggleBtn }`
		).addClass( 'btn-expanded' );

		$(
			`li.page_item_has_children:not(.current_page_ancestor, .current_page_item) > ${ toggleBtn }`
		).addClass( 'btn-collapsed' );
	};

	const collapseNoncurrentSubtree = () => {
		$(
			'li.page_item:not(.current_page_ancestor, .current_page_item) > ul'
		).addClass( 'subtree-collapsed' );
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
		addToggleSubtreeIcons();
		collapseNoncurrentSubtree();

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
