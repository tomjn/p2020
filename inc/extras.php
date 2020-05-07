<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package p2020
 */

namespace P2020;
/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'P2020\page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 */
function body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if( has_nav_menu( 'primary' ) ){
		$classes[] = 'custom-menu';
	}

	return $classes;
}
add_filter( 'body_class', 'P2020\body_classes' );

/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 */
function enhanced_image_navigation( $url, $id ) {
	if ( ! is_attachment() && ! wp_attachment_is_image( $id ) )
		return $url;

	$image = get_post( $id );
	if ( ! empty( $image->post_parent ) && $image->post_parent != $id )
		$url .= '#main';

	return $url;
}
add_filter( 'attachment_link', 'P2020\enhanced_image_navigation', 10, 2 );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 */
function wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $sep $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $sep " . sprintf( __( 'Page %s', 'p2020' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'P2020\wp_title', 10, 2 );

/**
 * Function for wrapping output that have HTML and should not be escaped, for purposes
 * of whitelisting. As suggested in
 * https://github.com/WordPress/WordPress-Coding-Standards/wiki/Whitelisting-code-which-flags-errors#when-to-use
 *
 * @param $str The string
 *
 * @return string
 */
function html_output( $str ) {
	return $str;
}
