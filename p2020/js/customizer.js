/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '[data-customizer-blogname]' ).text( to );
		} );
	} );
} )( jQuery );
