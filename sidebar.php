<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package p2020
 */

namespace P2020;

require_once get_template_directory() . '/inc/class-ellipsis-menu.php';
require_once get_template_directory() . '/inc/menu/menu.php';

use function \P2020\html_output;

$customizer_menu_url = admin_url( 'nav-menus.php' );
$pages_url           = admin_url( 'edit.php?post_type=page' );
$editor_page_url     = admin_url( 'post-new.php?post_type=page' );
?>

<div id="sidebar" class="p2020-sidebar 
<?php
if ( is_page() ) {
	echo 'is-dark';
}
?>
">
	<div class="screen-reader-text skip-link">
		<a href="#content" title="<?php esc_attr_e( 'Skip to content', 'p2020' ); ?>">
			<?php esc_html_e( 'Skip to content', 'p2020' ); ?>
		</a>
	</div>

	<header class="p2020-sidebar__header">
		<div class="p2020-sidebar-padded-container">
			<?php get_template_part( 'partials/sidebar-header' ); ?>
		</div>
	</header>

	<div class="p2020-sidebar__main">

		<div data-sidebar-primary 
		<?php
		if ( is_page() ) {
			echo esc_attr( 'hidden' );}
		?>
		>
			<?php get_template_part( 'partials/sidebar-info' ); ?>

			<?php do_action( 'before_sidebar' ); ?>

			<?php
			if ( is_page() && is_active_sidebar( 'sidebar-pages' ) ) {
				dynamic_sidebar( 'sidebar-pages' );
			} else {
				dynamic_sidebar( 'sidebar-1' );
			}
			?>
		</div><!-- [data-sidebar-primary] -->

		<div data-sidebar-secondary 
		<?php
		if ( ! is_page() ) {
			echo esc_attr( 'hidden' );}
		?>
		>

			<div class="p2020-sidebar__menu">
				<div class="p2020-sidebar-padded-container">
					<div class="p2020-sidebar__menu-header">
						<h2><?php esc_html_e( 'Documents', 'p2020' ); ?></h2>
						<?php if ( current_user_can( 'editor' ) ) : ?>
							<div class="p2020-sidebar__menu-header-ellipsis">
								<?php
									$pages_menu = new \P2020\EllipsisMenu();
									$pages_menu->add_item( __( 'New document', 'p2020' ), $editor_page_url );
									$pages_menu->add_item( __( 'Manage documents', 'p2020' ), $pages_url );
									echo html_output( $pages_menu->generate() );
								?>
							</div>
						<?php endif; ?>
					</div>
					<?php \P2020\Menu\render_page_menu(); ?>
				</div>
			</div>

			<div class="p2020-sidebar__menu">
				<div class="p2020-sidebar-padded-container">
					<div class="p2020-sidebar__menu-header">
						<h2><?php esc_html_e( 'Links', 'p2020' ); ?></h2>
						<?php if ( current_user_can( 'customize' ) ) : ?>
							<div class="p2020-sidebar__menu-header-ellipsis">
								<?php
									$nav_menu = new \P2020\EllipsisMenu();
									$nav_menu->add_item( __( 'Manage links', 'p2020' ), $customizer_menu_url );
									echo html_output( $nav_menu->generate() );
								?>
							</div>
						<?php endif; ?>
					</div>
					<?php \P2020\Menu\render_nav_menu(); ?>
				</div>
			</div>

		</div><!-- [data-sidebar-secondary] -->

		<footer class="p2020-sidebar__footer">
			<div class="p2020-sidebar-padded-container">
				<ul class="p2020-sidebar__footer-links">
					<li>
						<?php
							// CAUTION: This class is a fragile hack to get O2 to attach its
							// cheatsheet toggling event listener to this element.
							// See https://opengrok.a8c.com/source/xref/trunk/wp-content/plugins/p2tenberg/p2/js/keyboard.js#35
						?>
						<button class="o2-toggle-keyboard-help">
							<?php esc_html_e( 'Keyboard shortcuts', 'p2020' ); ?>
						</button>
					</li>
				</ul>

				<p class="p2020-sidebar__footer-credit">
					<?php esc_html_e( 'Powered by WordPress', 'p2020' ); ?>
				</p>
			</div>
		</footer>

	</div><!-- .p2020-sidebar__main -->

</div>
