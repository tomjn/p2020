<?php
/**
 * Theme Customizer
 *
 * @package p2020
 */

namespace P2020;

require get_template_directory() . '/inc/customizer/custom-header.php';
require get_template_directory() . '/inc/customizer/custom-colors.php';

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Dotcom_Customize $wp_customize Theme Customizer object.
 */
function customize_register( \WP_Customize_Manager $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	$wp_customize->selective_refresh->add_partial(
		'header_image',
		[
			'selector' => '#p2020-custom-header-partial',
		]
	);

	/**
	 * Add P2 Identity section with site title, tagline and header image controls.
	 */
	// Rename Site Identity section to Name and description
	$wp_customize->get_section( 'title_tagline' )->title    = __( 'Name and description', 'p2020' );
	$wp_customize->get_section( 'title_tagline' )->priority = 1;

	// Site Title
	$wp_customize->get_control( 'blogname' )->priority = 1;

	// Rename Tagline to Description and convert to textarea type
	$wp_customize->get_control( 'blogdescription' )->label    = __( 'Description', 'p2020' );
	$wp_customize->get_control( 'blogdescription' )->type     = 'textarea';
	$wp_customize->get_control( 'blogdescription' )->priority = 2;

	// Site Icon
	$wp_customize->get_control( 'site_icon' )->priority = 3;

	// Move Header Image section to after Name and description
	$wp_customize->get_section( 'header_image' )->title    = __( 'Header image', 'p2020' );
	$wp_customize->get_section( 'header_image' )->priority = 2;
}

add_action( 'customize_register', 'P2020\customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function customize_preview_js() {
	wp_enqueue_script( 'p2020_customizer', get_template_directory_uri() . '/js/customizer.js', [ 'customize-preview' ], '20130304', true );
}

add_action( 'customize_preview_init', 'P2020\customize_preview_js' );

function disable_nonrelevant_sections( \WP_Customize_Manager $wp_customize ) {
	// Remove "Homepage Settings".
	$wp_customize->remove_section( 'static_front_page' );

	// Remove "Theme Options" https://github.com/Automattic/p2/issues/594
	// https://code.a8c.com/diffusion/WP/browse/trunk/wp-content/mu-plugins/theme-plugins.php$222
	$wp_customize->remove_section( 'o2_options' );

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
function enable_custom_customizer(): bool {
	return false;
}

add_filter( 'enable_custom_customizer', 'P2020\enable_custom_customizer' );
