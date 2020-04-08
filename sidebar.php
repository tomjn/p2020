<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package p2020
 */

if( ! is_active_sidebar( 'sidebar-1' ) )
	return;
?>
	<aside id="sidebar">
		<?php do_action( 'before_sidebar' ); ?>
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</aside>
