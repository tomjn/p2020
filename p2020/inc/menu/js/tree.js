( function ( $ ) {
	const menuContainer = '.p2020-sidebar-menu';

	const itemHasChildren = ( $item ) => {
		return (
			$item.hasClass( 'page-item-has-children' ) ||
			$item.hasClass( 'menu-item-has-children' )
		);
	};

	const itemIsCurrent = ( $item ) => {
		return (
			$item.hasClass( 'current-page-ancestor' ) ||
			$item.hasClass( 'current-page-item' ) ||
			$item.hasClass( 'current-menu-ancestor' ) ||
			$item.hasClass( 'current-menu-item' )
		);
	};

	const addToggleSubtreeIcons = () => {
		const toggleBtn = '.menu-item-toggle';

		$( `${ menuContainer } li` ).each( function () {
			if ( itemHasChildren( $( this ) ) ) {
				if ( itemIsCurrent( $( this ) ) ) {
					$( this )
						.find( `> ${ toggleBtn }` )
						.addClass( 'is-expanded' );
				} else {
					$( this )
						.find( `> ${ toggleBtn }` )
						.addClass( 'is-collapsed' );
				}
			}
		} );
	};

	const collapseNoncurrentSubtree = () => {
		$( `${ menuContainer } li` ).each( function () {
			if ( ! itemIsCurrent( $( this ) ) ) {
				$( this ).find( '> ul' ).addClass( 'is-collapsed' );
			}
		} );
	};

	const showSubtree = ( $element ) => {
		$element
			.closest( 'li' )
			.find( 'ul.children:first, ul.sub-menu:first' )
			.removeClass( 'is-collapsed' );
	};

	const hideSubtree = ( $element ) => {
		$element
			.closest( 'li' )
			.find( 'ul.children:first, ul.sub-menu:first' )
			.addClass( 'is-collapsed' );
	};

	const toggleClass = ( $element ) => {
		if ( $element.hasClass( 'is-collapsed' ) ) {
			$element.addClass( 'is-expanded' ).removeClass( 'is-collapsed' );
		} else {
			$element.addClass( 'is-collapsed' ).removeClass( 'is-expanded' );
		}
	};

	const updateARIA = ( $element, isExpanded ) => {
		if ( isExpanded ) {
			$element.attr( 'aria-expanded', 'true' );
			$element.attr( 'aria-label', 'Collapse' );
		} else {
			$element.attr( 'aria-expanded', 'false' );
			$element.attr( 'aria-label', 'Expand' );
		}
	};

	$( function () {
		addToggleSubtreeIcons();
		collapseNoncurrentSubtree();

		$( document.body ).on( 'click', '.is-collapsed', function () {
			showSubtree( $( this ) );
			toggleClass( $( this ) );
			updateARIA( $( this ), true );
		} );

		$( document.body ).on( 'click', '.is-expanded', function () {
			hideSubtree( $( this ) );
			toggleClass( $( this ) );
			updateARIA( $( this ), false );
		} );
	} );
} )( jQuery );
