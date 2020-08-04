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
 * Adds custom classes to the array of body classes.
 */
function body_classes( array $classes ): array {
	// Used for fallback styling when JS is disabled or slow to load (Removed when JS kicks in)
	$classes[] = 'no-js';

	// Adds a class of group-blog to blogs with more than 1 published author
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( has_nav_menu( 'primary' ) ) {
		$classes[] = 'custom-menu';
	}

	if ( is_a8c_p2() ) {
		$classes[] = 'a8c-p2';
	}

	return $classes;
}
add_filter( 'body_class', 'P2020\body_classes' );

/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 */
function enhanced_image_navigation( $url, $id ) {
	if ( ! is_attachment() && ! wp_attachment_is_image( $id ) ) {
		return $url;
	}

	$image = get_post( $id );
	if ( ! empty( $image->post_parent ) && (int) $image->post_parent !== (int) $id ) {
		$url .= '#main';
	}

	return $url;
}
add_filter( 'attachment_link', 'P2020\enhanced_image_navigation', 10, 2 );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 */
function wp_title( string $title, string $sep ): string {
	global $page, $paged;

	if ( is_feed() ) {
		return $title;
	}

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 ) {
		/* translators: %s is replaced with the page number */
		$title .= " $sep " . sprintf( __( 'Page %s', 'p2020' ), max( $paged, $page ) );
	}

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
function html_output( string $str ): string {
	return $str;
}

/**
 * Auto subscribe author to their post/comments (check the hidden o2 checkbox)
 */
add_filter( 'subscribe_author_to_comments_override', '__return_true' );
add_filter( 'subscribe_to_comments_override', '__return_true' );

/**
 * Hide intralink for P2 Pages
 */
function p2_show_intralinks() {
	if ( is_page() ) {
		return false;
	}
	return true;
}
add_filter( 'a8c_show_intralinks', 'P2020\p2_show_intralinks' );
