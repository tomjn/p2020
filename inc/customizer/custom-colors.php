<?php
/**
 * Colors section in Customizer
 *
 * @package p2020
 */

namespace P2020;

require get_template_directory() . '/inc/vendor/Color.php';
use Mexitek\PHPColors\Color as Color;

// Keep in sync with CSS variables/themes
function get_default_color( string $key ): string {
	$default_colors = [
		'color_link'               => '#0267ff',
		'color_mentions'           => '#b35eb1',
		'color_sidebar_background' => '#f3f3f3',
		'color_sidebar_content'    => '#00101c',
	];
	return $default_colors[ $key ];
}

// Whether a color option is set and is a non-default value
function is_custom_color( array $options, string $key ): bool {
	if ( ! isset( $options[ $key ] ) ) {
		return false;
	}

	$color = $options[ $key ];
	return get_default_color( $key ) !== $color;
}

function color_hex_to_rgba( string $hex, float $alpha ): string {
	$rgb   = sscanf( $hex, '#%02x%02x%02x' );
	$rgb[] = $alpha;
	return sprintf( 'rgba(%d, %d, %d, %f);', ...$rgb );
}

// Utility function to add a single color setting and control
function register_color( \WP_Customize_Manager $wp_customize, string $key, string $label ) {
	$wp_customize->add_setting(
		"p2020_theme_options[$key]",
		[
			'default'           => get_default_color( $key ),
			'sanitize_callback' => 'sanitize_hex_color',
		]
	);
	$wp_customize->add_control(
		new \WP_Customize_Color_Control(
			$wp_customize,
			$key,
			[
				'label'    => $label,
				'section'  => 'colors',
				'settings' => "p2020_theme_options[$key]",
			]
		)
	);
}

/**
 * Add color settings to Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function customize_register_colors( \WP_Customize_Manager $wp_customize ) {
	register_color( $wp_customize, 'color_link', __( 'Links', 'p2020' ) );
	register_color( $wp_customize, 'color_mentions', __( 'Mentions', 'p2020' ) );
	register_color( $wp_customize, 'color_sidebar_background', __( 'Sidebar background', 'p2020' ) );
	register_color( $wp_customize, 'color_sidebar_content', __( 'Sidebar content', 'p2020' ) );
}

add_action( 'customize_register', __NAMESPACE__ . '\customize_register_colors' );

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
			<?php if ( is_custom_color( $options, 'color_link' ) ) : ?>
				<?php $color_link = new Color( $options['color_link'] ); ?>
				<?php $color_link_background = color_hex_to_rgba( $options['color_link'], 0.1 ); ?>

				--color-link: <?php echo esc_html( $options['color_link'] ); ?>;
				--color-link-dark: <?php echo sprintf( '#%s;', esc_html( $color_link->darken( 13 ) ) ); ?>;
				--color-link-background: <?php echo esc_html( $color_link_background ); ?>;
			<?php endif; ?>

			<?php if ( is_custom_color( $options, 'color_mentions' ) ) : ?>
				<?php $color_mentions_highlight = color_hex_to_rgba( $options['color_mentions'], 0.1 ); ?>

				--color-mentions: <?php echo esc_html( $options['color_mentions'] ); ?>;
				--color-mentions-highlight: <?php echo esc_html( $color_mentions_highlight ); ?>;
			<?php endif; ?>

			<?php if ( is_custom_color( $options, 'color_sidebar_background' ) ) : ?>
				--color-sidebar-background: <?php echo esc_html( $options['color_sidebar_background'] ); ?>;
			<?php endif; ?>

			<?php if ( is_custom_color( $options, 'color_sidebar_content' ) ) : ?>
				--color-sidebar-content: <?php echo esc_html( $options['color_sidebar_content'] ); ?>;
			<?php endif; ?>
		}
	</style>
	<?php
}

add_filter( 'wp_head', __NAMESPACE__ . '\color_styles' );
