/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );
	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a, .site-description' ).css( 'color', to );
		} );
	} );
	// Alternate color.
	wp.customize( 'p2020_theme_options[alternate_color]', function( value ) {
		value.bind( function( to ) {
			$(
				'.o2 .o2-app-page-title, .o2 .o2-app-new-post h2, #o2-expand-editor'
			).css( 'background-color', to );
		} );
	} );
	// Link color.
	wp.customize( 'p2020_theme_options[link_color]', function( value ) {
		value.bind( function( to ) {
			$( 'a, a:hover, a:visited' ).css( 'color', to );
		} );
	} );
} )( jQuery );
