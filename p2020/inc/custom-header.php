<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * You can add an optional custom header image to header.php like so ...

	<?php $header_image = get_header_image();
	if ( ! empty( $header_image ) ) { ?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
			<img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" />
		</a>
	<?php } // if ( ! empty( $header_image ) ) ?>

 *
 * @package p2020
 */

namespace P2020;

/**
 * Setup the WordPress core custom header feature.
 *
 * @package p2020
 */
function custom_header_setup() {
	$args = [
		'default-text-color' => '000',
		'width' => 304 * 2, /* 2x resolution */
		'height' => 152 * 2, /* 2x resolution */
		'flex-width' => true,
		'flex-height' => true,
		'header-text' => false,
	];

	add_theme_support( 'custom-header', apply_filters( 'p2020_custom_header_args', $args ) );
}
add_action( 'after_setup_theme', 'P2020\custom_header_setup' );
