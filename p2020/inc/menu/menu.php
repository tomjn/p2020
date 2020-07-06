<?php

namespace P2020\Menu;

use function \P2020\html_output;

function enqueue_scripts() {
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\scripts' );
}

function scripts() {
	wp_enqueue_script( 'p2020-menu-tree', get_template_directory_uri() . '/inc/menu/js/tree.js', [ 'jquery' ] );
}

function render_page_menu() {
	$pages_html = wp_page_menu( [
		'title_li' => '',
		'sort_column' => 'menu_order, post_title',
		'exclude' => '',
		'echo' => false,
		'container' => 'div',
		'menu_class' => 'p2020-sidebar-menu p2020-sidebar-menu__pages',
		'show_home' => false,
	] );

	if ( ! empty( $pages_html ) ) {
		$pages_html = normalize_classnames( $pages_html );
		$pages_html = format_items( 'pages', $pages_html );
		echo html_output( $pages_html );
	}
}

function render_nav_menu() {
	$menu_locations = get_nav_menu_locations();

	$nav_html = wp_nav_menu( [
		'echo' => false,
		'container' => 'div',
		'container_class' => 'p2020-sidebar-menu p2020-sidebar-menu__nav',
		'menu' => $menu_locations['primary'] ?? null,
	] );

	if ( ! empty( $nav_html ) ) {
		$nav_html = format_items( 'nav', $nav_html );
		echo html_output( $nav_html );
	}
}

/**
 * Use regex to add subtree toggling
 *
 * From
 *   <li class="(page|menu)_item (page|menu)-item-xxx">
 *     <a href="...">About Us</a>
 *     (subtree)
 *   </li>
 *
 * To
 *   <li class="(page|menu)_item (page|menu)-item-xxx">
 *     <a href="..." class="nav-item-link">About Us</a>
 *     <button class="nav-item-toggle" aria-label="Expand" aria-expanded=false></button>
 *     (subtree)
 *   </li>
 */
function format_items( $menu_type, $menu_html ) {
	if ( $menu_type === 'pages' ) {
		$pattern = '/(<li [^>]*page-item-([0-9]+)[^>]*>)<a href="([^"]*)"[^>]*>([^<]*)<\/a>/i';
	} else {
		$pattern = '/(<li .* menu-item-([0-9]+).*>)<a href="(.*)".*>(.*)<\/a>/i';
	}

	$replacement = '$1
		<a href="$3" class="menu-item-link">$4</a>
		<button class="menu-item-toggle" aria-label="Expand" aria-expanded=false></button>';
	$menu_html = preg_replace( $pattern, $replacement, $menu_html );

	return $menu_html;
}

/**
 * Use regex to transform classnames to hyphen-separated for
 *     consistency with wp_nav_menu.
 */
function normalize_classnames( $pages_html ) {
	$classnames = [
		'page_item' => 'page-item',
		'page_item_has_children' => 'page-item-has-children',
		'current_page_item' => 'current-page-item',
		'current_page_ancestor' => 'current-page-ancestor',
	];
	foreach ( $classnames as $original => $replacement ) {
		$pages_html = preg_replace( "/(\b)$original(\b)/", "$1$replacement$2", $pages_html );
	}

	return $pages_html;
}
