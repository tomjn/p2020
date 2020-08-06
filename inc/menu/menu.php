<?php

namespace P2020\Menu;

use function P2020\html_output;

function enqueue_scripts() {
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\scripts' );
}

function scripts() {
	wp_enqueue_script( 'p2020-menu-tree', get_template_directory_uri() . '/inc/menu/js/tree.js', [ 'jquery' ], '20200801', true );
}

function render_page_menu() {
	$options = [
		'title_li'    => '',
		'sort_column' => 'menu_order, post_title',
		'exclude'     => '',
		'echo'        => false,
		'container'   => 'div',
		'menu_class'  => 'p2020-sidebar-menu p2020-sidebar-menu__pages',
		'show_home'   => false,
	];

	$pages_html = wp_page_menu( $options );

	// empty list
	$container = $options['container'];
	$classes   = $options['menu_class'];
	if ( "<$container class=\"$classes\"></$container>" === $pages_html ) {
		$page_editor_link = admin_url( 'post-new.php?post_type=page' );
		echo html_output( '<div class="empty-menu-list">No documents — <a href="' . $page_editor_link . '">Start one</a></div>' );
		echo '<div class="empty-menu-list">No documents — <a href="' . $page_editor_link . '">Start one</a></div>';

		return;
	}

	if ( ! empty( $pages_html ) ) {
		$pages_html = normalize_classnames( $pages_html );
		$pages_html = format_items( 'pages', $pages_html );
		echo html_output( $pages_html );
	}
}

function render_nav_menu() {
	$menu_locations = get_nav_menu_locations();

	$nav_html = wp_nav_menu(
		[
			'echo'            => false,
			'container'       => 'div',
			'container_class' => 'p2020-sidebar-menu p2020-sidebar-menu__nav',
			'menu'            => $menu_locations['primary'] ?? null,
		]
	);

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
	if ( 'pages' === $menu_type ) {
		$pattern = '/(<li [^>]*page-item-([0-9]+)[^>]*>)<a href="([^"]*)"[^>]*>([^<]*)<\/a>/i';
	} else {
		$pattern = '/(<li .* menu-item-([0-9]+).*>)<a href="(.*)".*>(.*)<\/a>/i';
	}

	$page_editor_link = admin_url( 'post-new.php?post_type=page' );
	$replacement      = '$1
		<button class="menu-item-toggle" aria-label="Expand" aria-expanded=false></button>
		<span class="menu-item-links">
		<a href="$3" class="menu-item-title">$4</a>';
	if ( 'pages' === $menu_type ) {
		$replacement .= '<a href="' . esc_url( $page_editor_link . '&parent_post=$2' ) . '" class="menu-item-add"' .
			' data-tippy-content="' . __( 'New subdocument', 'p2020' ) . '"><span class="screen-reader-text">' . __( 'New subdocument', 'p2020' ) . '</span></a>';
	}
	$replacement .= '</span>';
	$menu_html    = preg_replace( $pattern, $replacement, $menu_html );

	return $menu_html;
}

/**
 * Use regex to transform classnames to hyphen-separated for
 *     consistency with wp_nav_menu.
 */
function normalize_classnames( $pages_html ) {
	$classnames = [
		'page_item'              => 'page-item',
		'page_item_has_children' => 'page-item-has-children',
		'current_page_item'      => 'current-page-item',
		'current_page_ancestor'  => 'current-page-ancestor',
	];
	foreach ( $classnames as $original => $replacement ) {
		$pages_html = preg_replace( "/(\b)$original(\b)/", "$1$replacement$2", $pages_html );
	}

	return $pages_html;
}
