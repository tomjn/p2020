( function( $ ) {
	const selectorParentInput = '.editor-page-attributes__parent select';
	const selectorParentOption =
		'.editor-page-attributes__parent select option[value=' +
		window.p2020Pages.parent +
		']';

	const unbindSetParentPage = () => {
		$( document.body ).off( 'focus', '#editor' );
	};

	const setParentPage = () => {
		if ( $( selectorParentInput ).length > 0 ) {
			$( selectorParentInput ).val( window.p2020Pages.parent );
			$( selectorParentOption ).attr( 'selected', 'selected' );

			const e = new Event( 'change', { bubbles: true } );
			const input = document.querySelector( selectorParentInput );
			const prototypeValueSetter = Object.getOwnPropertyDescriptor(
				Object.getPrototypeOf( input ),
				'value'
			).set;
			prototypeValueSetter.call( input, window.p2020Pages.parent );
			input.dispatchEvent( e );
		}
	};

	const openPageAttrSection = () => {
		$( 'button:contains("Page Attributes")' ).each( function() {
			if (
				! $( this )
					.closest( '.components-panel__body' )
					.hasClass( 'is-opened' )
			) {
				$( this ).click();
			}
		} );
	};

	$( function() {
		$( document.body ).on(
			'click',
			'.page-template-modal__buttons button, button.page-template-modal__close-button',
			function() {
				openPageAttrSection();
				setParentPage();
			}
		);

		$( document.body ).on(
			'DOMSubtreeModified',
			'.components-panel__body:contains("Page Attributes")',
			function() {
				setParentPage();
			}
		);

		$( document.body ).on(
			'change',
			selectorParentInput,
			unbindSetParentPage
		);
	} );
} )( jQuery );
