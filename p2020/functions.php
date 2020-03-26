<?php
/**
 * P2 functions and definitions
 *
 * @package p2020
 */

/**
 * Load partner plugins loader file.
 */
require( 'thirdparty.php' );

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 776; /* pixels */

/*
 * Load Jetpack compatibility file.
 */
require( get_template_directory() . '/inc/jetpack.php' );

if ( ! function_exists( 'breathe_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function breathe_setup() {

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	require( get_template_directory() . '/inc/extras.php' );

	/**
	 * Customizer additions
	 */
	require( get_template_directory() . '/inc/customizer.php' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on P2, use a find and replace
	 * to change 'p2020' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'p2020', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
 	 * Enable support for Full-Width Images
 	 */
	add_theme_support( 'align-wide' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( [
		'primary' => __( 'Primary Menu', 'p2020' ),
	] );

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support( 'post-formats', [ 'aside', 'image', 'video', 'quote', 'link' ] );
}
endif; // breathe_setup
add_action( 'after_setup_theme', 'breathe_setup' );

/**
 * Setup the WordPress core custom background feature.
 *
 * Hooks into the after_setup_theme action.
 */
function breathe_register_custom_background() {
	$args = [
		'default-color' => 'f1f1f1',
		'default-image' => '',
	];

	add_theme_support( 'custom-background', apply_filters( 'breathe_custom_background_args', $args ) );
}
add_action( 'after_setup_theme', 'breathe_register_custom_background' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function breathe_widgets_init() {
	register_sidebar( [
		'name' => __( 'Sidebar', 'p2020' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	] );
}
add_action( 'widgets_init', 'breathe_widgets_init' );

/**
 * Enqueue Google Fonts
 */
function breathe_fonts() {
	/**
	 * translators: If there are characters in your language that are not supported
	 * by Open Sans, translate this to 'off'. Do not translate into your own language.
	 */

	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'p2020' ) ) {
		wp_register_style( 'breathe-open-sans', "https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,300,400,600" );
	}
	if ( 'off' !== _x( 'on', 'Serif: on or off', 'p2020' ) ) {
		wp_register_style( 'breathe-serif', "https://fonts.googleapis.com/css?family=Noto+Serif:400,700,400italic,700italic" );
	}
}
add_action( 'init', 'breathe_fonts' );

/**
 * Enqueue font styles in custom header admin
 */
function breathe_admin_fonts( $hook_suffix ) {
	if ( 'appearance_page_custom-header' != $hook_suffix )
		return;

	wp_enqueue_style( 'breathe-open-sans' );
	wp_enqueue_style( 'breathe-serif' );
}
add_action( 'admin_enqueue_scripts', 'breathe_admin_fonts' );

/**
 * Enqueue scripts and styles
 */
function breathe_scripts() {
	wp_enqueue_style( 'breathe-style', get_stylesheet_uri() );
	wp_style_add_data( 'breathe-style', 'rtl', 'replace' );
	//wp_enqueue_style( 'breathe-open-sans' );
	wp_enqueue_style( 'breathe-serif' );

	wp_enqueue_script( 'breathe-skip-link-focus-fix', get_template_directory_uri() . '/js/vendor/skip-link-focus-fix.js', [], '20130115', true );

	wp_enqueue_script( 'breathe-mobile-helper', get_template_directory_uri() . '/js/vendor/mobile-helper.js', [], '20130513', true );
	wp_enqueue_script( 'breathe-js', get_template_directory_uri() . '/js/breathe.js', [ 'breathe-mobile-helper', 'o2-enquire' ], '20130513', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'breathe-keyboard-image-navigation', get_template_directory_uri() . '/js/vendor/keyboard-image-navigation.js', [ 'jquery' ], '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', 'breathe_scripts' );

/**
 * Add a no-sidebar body class, if there are no widgets in the sidebar.
 */
function breathe_check_no_sidebar( $body_classes ) {
	if( ! is_active_sidebar( 'sidebar-1' ) )
		$body_classes[] = 'no-sidebar';

	return $body_classes;
}
add_filter( 'body_class', 'breathe_check_no_sidebar' );

/**
 * Implement the Custom Header feature
 */
require( get_template_directory() . '/inc/custom-header.php' );
