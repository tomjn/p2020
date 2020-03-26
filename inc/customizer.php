<?php
/**
 * Theme Customizer
 *
 * @package p2020
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function breathe_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	$wp_customize->add_setting( 'breathe_theme_options[alternate_color]', [
		'default' => '#3498db',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport' => 'postMessage',
	] );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'alternate_color', [
    	'label' => __( 'Alternate Color', 'p2020' ),
		'section' => 'colors',
		'settings' => 'breathe_theme_options[alternate_color]',
	] ) );

	$wp_customize->add_setting( 'breathe_theme_options[link_color]', [
		'default' => '#3498db',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport' => 'postMessage',
	] );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', [
    	'label' => __( 'Link Color', 'p2020' ),
		'section' => 'colors',
		'settings' => 'breathe_theme_options[link_color]',
	] ) );
}
add_action( 'customize_register', 'breathe_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function breathe_customize_preview_js() {
	wp_enqueue_script( 'breathe_customizer', get_template_directory_uri() . '/js/customizer.js', [ 'customize-preview' ], '20130304', true );
}
add_action( 'customize_preview_init', 'breathe_customize_preview_js' );

/**
 * Add styles for color options
 */
function breathe_color_styles() {
	$options = get_theme_mod( 'breathe_theme_options' );

	if ( ! isset( $options ) )
		return;

	$style = '<style type="text/css">';

	if ( '#3498db' != $options['alternate_color'] )
		$style .= '.o2 .o2-app-page-title, .o2 .o2-app-new-post h2, #o2-expand-editor { background-color: ' . $options['alternate_color'] . '}';

	if ( '#3498db' != $options['link_color'] )
		$style .= 'a, a:hover, a:visited { color: ' . $options['link_color'] . '}';

	$style .= '</style>';

	echo $style;
}
add_filter( 'wp_head', 'breathe_color_styles' );