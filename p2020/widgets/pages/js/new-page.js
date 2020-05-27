( function ( $ ) {
	const selectorParentInput = '.editor-page-attributes__parent select';

	const setParentPage = () => {
		if ( $( selectorParentInput ).length > 0 ) {
			const selectElement = document.querySelector( selectorParentInput );
			selectElement.value = window.p2020Pages.parent;
			selectElement.dispatchEvent(
				new Event( 'change', { bubbles: true } )
			);
		}
	};

	const openPageAttrSection = () => {
		$( '.components-panel__body:last-child button' ).each( function () {
			if (
				! $( this )
					.closest( '.components-panel__body' )
					.hasClass( 'is-opened' )
			) {
				$( this ).click();
			}
		} );
	};

	const onLoadSetParent = () => {
		const targetNode = document.querySelector( '#editor' );
		const config = { childList: true, subtree: true };
		const callback = function ( mutationsList, observer ) {
			for ( const mutation of mutationsList ) {
				if ( mutation.addedNodes.length > 0 ) {
					if (
						mutation.target.classList.contains(
							'components-panel'
						) &&
						mutation.nextSibling === null
					) {
						openPageAttrSection();
					} else if (
						mutation.addedNodes[ 0 ].classList.contains(
							'editor-page-attributes__parent'
						)
					) {
						setParentPage();
						observer.disconnect();
					}
				}
			}
		};
		const observer = new MutationObserver( callback );
		observer.observe( targetNode, config );
	};

	$( function () {
		onLoadSetParent();

		$( document.body ).on(
			'click',
			'.page-template-modal__buttons button, button.page-template-modal__close-button',
			function () {
				openPageAttrSection();
				setParentPage();
			}
		);
	} );
} )( jQuery );
