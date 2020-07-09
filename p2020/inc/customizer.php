<?php
/**
 * Theme Customizer
 *
 * @package p2020
 */

namespace P2020;

require( get_template_directory() . '/inc/vendor/Color.php' );
use Mexitek\PHPColors\Color as Color;

// Keep in sync with CSS variables/themes
function get_default_color( string $key ): string {
	$default_colors = [
		'color_link' => '#0267ff',
		'color_mentions' => '#b35eb1',
	];
	return $default_colors[$key];
}

// Whether a color option is set and is a non-default value
function isCustomColor( array $options, string $key ): bool {
	if ( ! isset( $options[ $key ] ) ) {
		return false;
	}

	$color = $options[ $key ];
	return $color !== get_default_color( $key );
}

function color_hex_to_rgba( string $hex, float $alpha ): string {
	$rgb = sscanf( $hex, '#%02x%02x%02x' );
	$rgb[] = $alpha;
	return sprintf( 'rgba(%d, %d, %d, %f);', ...$rgb );
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Dotcom_Customize $wp_customize Theme Customizer object.
 */
function customize_register( \WP_Dotcom_Customize $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	$wp_customize->add_setting( 'p2020_theme_options[color_link]', [
		'default' => get_default_color( 'color_link' ),
		'sanitize_callback' => 'sanitize_hex_color',
	] );
	$wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'color_link', [
		'label' => __( 'Links', 'p2020' ),
		'section' => 'colors',
		'settings' => 'p2020_theme_options[color_link]',
	] ) );

	$wp_customize->add_setting( 'p2020_theme_options[color_mentions]', [
		'default' => get_default_color( 'color_mentions' ),
		'sanitize_callback' => 'sanitize_hex_color',
	] );
	$wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'color_mentions', [
		'label' => __( 'Mentions', 'p2020' ),
		'section' => 'colors',
		'settings' => 'p2020_theme_options[color_mentions]',
	] ) );

	$wp_customize->selective_refresh->add_partial( 'header_image', [
		'selector' => '#p2020-custom-header-partial',
	] );

	/**
	 * Add P2 Identity section with site title, tagline and header image controls.
	 */
	// Rename Site Identity section to P2 Info
	$wp_customize->get_section( 'title_tagline' )->title = __( 'P2 Identity', 'p2020' );

	// Site Title
	$wp_customize->get_control( 'blogname' )->priority = 1;

	// Rename Tagline to Description and convert to textarea type
	$wp_customize->get_control( 'blogdescription' )->label = 'Description';
	$wp_customize->get_control( 'blogdescription' )->type = 'textarea';
	$wp_customize->get_control( 'blogdescription' )->priority = 2;

	// Site Icon
	$wp_customize->get_control( 'site_icon' )->priority = 3;

	// Move Header Image control to this section
	$wp_customize->get_control( 'header_image' )->section = 'title_tagline';
	$wp_customize->get_control( 'header_image' )->priority = 4;
}

add_action( 'customize_register', 'P2020\customize_register' );

/**
 * Add styles for color options
 *
 * Keep variable names in sync with CSS themes.
 */
function color_styles() {
	$options = get_theme_mod( 'p2020_theme_options' );

	if ( ! $options ) {
		return;
	}
?>
	<style type="text/css">
		:root {
			<? if ( isCustomColor( $options, 'color_link' ) ): ?>
				<?php $color_link = new Color( $options['color_link'] ); ?>
				<?php $color_link_background = color_hex_to_rgba( $options['color_link'], 0.1 ); ?>
				--color-link: <?php echo esc_html( $options['color_link'] ) ?>;
				--color-link-dark: <?php echo sprintf( '#%s;', esc_html( $color_link->darken( 13 ) ) ); ?>;
				--color-link-background: <?php echo esc_html( $color_link_background ); ?>;
			<? endif; ?>
			<? if ( isCustomColor( $options, 'color_mentions' ) ): ?>
				<?php $color_mentions_highlight = color_hex_to_rgba( $options['color_mentions'], 0.1 ); ?>
				--color-mentions: <?php echo esc_html( $options['color_mentions'] ) ?>;
				--color-mentions-highlight: <?php echo esc_html( $color_mentions_highlight ); ?>;
			<? endif; ?>
		}
	</style>
<?php
}

add_filter( 'wp_head', 'P2020\color_styles' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function customize_preview_js() {
	wp_enqueue_script( 'p2020_customizer', get_template_directory_uri() . '/js/customizer.js', [ 'customize-preview' ], '20130304', true );
}

add_action( 'customize_preview_init', 'P2020\customize_preview_js' );

function disable_nonrelevant_sections( \WP_Dotcom_Customize $wp_customize ) {
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
