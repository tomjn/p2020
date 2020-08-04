<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package p2020
 */

namespace P2020;

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function infinite_scroll_setup() {
	$footer    = 'main';
	$is_tablet = ( class_exists( 'Jetpack_User_Agent_Info' ) ) ? \Jetpack_User_Agent_Info::is_tablet() : false;
	if ( $is_tablet ) {
		// don't show the infinite scroll footer on tablet devices (infinite scroll will still work)
		$footer = false;
	}

	add_theme_support(
		'infinite-scroll',
		[
			'container' => 'content',
			'footer'    => $footer,
		]
	);
}
add_action( 'after_setup_theme', 'P2020\infinite_scroll_setup' );

/**
 * Switch to 'click' method if is_mobile and sidebar-1 is active
 */
if ( function_exists( 'jetpack_is_mobile' ) ) {
	function has_footer_widgets() {
		if ( jetpack_is_mobile( '', true ) && is_active_sidebar( 'sidebar-1' ) ) {
			return true;
		}

		return false;
	}
	add_filter( 'infinite_scroll_has_footer_widgets', 'P2020\has_footer_widgets' );
}

/**
 * Add theme support for o2.
 */
function o2_setup() {
	add_theme_support(
		'o2',
		[
			'o2_enabled' => true,
		]
	);
}
add_action( 'after_setup_theme', 'P2020\o2_setup' );
