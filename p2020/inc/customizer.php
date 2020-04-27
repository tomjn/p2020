<?php
/**
 * Theme Customizer
 *
 * @package p2020
 */

namespace P2020;

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
}

add_action( 'customize_register', 'P2020\customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function customize_preview_js() {
	wp_enqueue_script( 'p2020_customizer', get_template_directory_uri() . '/js/customizer.js', [ 'customize-preview' ], '20130304', true );
}

add_action( 'customize_preview_init', 'P2020\customize_preview_js' );

function disable_nonrelevant_sections( $wp_customize ) {
	// Remove "Homepage Settings".
	$wp_customize->remove_section( 'static_front_page' );

	// Remove "Additional CSS" (WP Core).
	$wp_customize->remove_section( 'custom_css' );

	// Remove "Additional CSS" upgrade nudge when on Free plan (Jetpack_Custom_CSS_Customizer).
	// https://opengrok.a8c.com/source/xref/trunk/wp-content/mu-plugins/custom-css-in-customizer.php#285
	$wp_customize->remove_section( 'css_nudge' );

	// Remove O2 Theme Options: Enable front-end editing
	// https://opengrok.a8c.com/source/xref/trunk/wp-content/mu-plugins/theme-plugins.php#233
	$wp_customize->remove_control( 'o2_options[o2_enabled]' );

	// Remove O2 Theme Options: Enable "To Do" Module, Mark New Posts "To Do"
	// https://opengrok.a8c.com/source/xref/trunk/wp-content/plugins/o2/o2.php#689
	$wp_customize->remove_control( 'o2_options[enable_resolved_posts]' );
	$wp_customize->remove_control( 'o2_options[mark_posts_unresolved]' );

	// Remove Footer Credit option
	// https://opengrok.a8c.com/source/xref/trunk/wp-content/mu-plugins/footer-credit/customizer.php#80
	$wp_customize->remove_control( 'footercredit' );
}

// Keep the priority high enough so our callback is ran after everything else.
add_action( 'customize_register', 'P2020\disable_nonrelevant_sections', 1000 );

/**
 * Disable "Additional CSS" added in Jetpack_Custom_CSS_Customizer.
 *
 * https://opengrok.a8c.com/source/xref/trunk/wp-content/mu-plugins/custom-css-in-customizer.php#15
 */
function enable_custom_customizer() {
	return false;
}

add_filter( 'enable_custom_customizer', 'P2020\enable_custom_customizer' );
