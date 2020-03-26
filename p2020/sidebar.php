<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package p2020
 */

if( ! is_active_sidebar( 'sidebar-1' ) )
	return;
?>
	<div id="primary-modal"></div>
	<div id="secondary" class="widget-area" role="complementary">
		<div id="secondary-content">
			<?php do_action( 'before_sidebar' ); ?>
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</div>
	</div><!-- #secondary -->
