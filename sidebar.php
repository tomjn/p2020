<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package p2020
 */

namespace P2020;

require_once( get_template_directory() . '/inc/extras.php' );
require_once( get_template_directory() . '/inc/class-ellipsis-menu.php' );
require_once( get_template_directory() . '/inc/filter/filter.php' );
require_once( get_template_directory() . '/inc/follow/follow.php' );
require_once( get_template_directory() . '/inc/menu/menu.php' );
require_once( get_template_directory() . '/inc/sidebar-header.php' );

use function \P2020\html_output;

$site_slug = \WPCOM_Masterbar::get_calypso_site_slug( get_current_blog_id() );

$signup_url = 'https://wordpress.com/start/p2';
$customizer_url = 'https://wordpress.com/customize/' . $site_slug;
$customizer_identity_url = 'https://wordpress.com/customize/identity/' . $site_slug;
$customizer_menu_url = 'https://wordpress.com/customize/menus/' . $site_slug;
$help_url = 'https://wordpress.com/help';
$settings_url = 'https://wordpress.com/settings/general/' . $site_slug;
$pages_url = 'https://wordpress.com/pages/' . $site_slug;

$header_image_height = get_theme_support( 'custom-header', 'height' ) / 2 . 'px';
?>

	<div id="sidebar" class="p2020-sidebar <?php if ( is_page() ) echo esc_attr( 'is-dark' ); ?>">
		<div class="screen-reader-text skip-link">
			<a href="#content" title="<?php esc_attr_e( 'Skip to content', 'p2020' ); ?>">
				<?php esc_html_e( 'Skip to content', 'p2020' ); ?>
			</a>
		</div>

		<header class="p2020-sidebar__header">
			<div class="p2020-sidebar-padded-container">
				<?php echo html_output( get_sidebar_header() ); ?>
			</div>
		</header>

		<div class="p2020-sidebar__main">

			<div data-sidebar-primary <?php if ( is_page() ) echo esc_attr( 'hidden' ); ?>>
				<div class="p2020-sidebar__info">
					<div class="p2020-sidebar-padded-container">
						<section>
							<a href="<?php echo esc_url( \P2020\get_blog_url( '/' ) ); ?>"
								id="p2020-custom-header-partial"
								title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"
								rel="home"
							>
								<?php if ( has_header_image() ): ?>
									<img
										src="<?php echo esc_html( get_header_image() ) ?>"
										alt=""
										class="p2020-sidebar__info-image"
										width="<?php echo esc_attr( get_custom_header()->width ); ?>"
										height="<?php echo esc_attr( get_custom_header()->height ); ?>"
									>
								<?php else: ?>
									<div
										class="p2020-sidebar__info-image is-placeholder"
										style="height: <?php echo esc_attr( $header_image_height ); ?>;"
									>
									</div>
								<?php endif; ?>
							</a>

							<div class="p2020-sidebar__info-header">
								<h2>
									<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"
										class="p2020-sidebar__info-header-blogname" data-customizer-blogname>
										<?php bloginfo( 'name' ); ?>
									</a>
								</h2>

								<?php if ( is_user_member_of_blog() &&
										( current_user_can( 'customize' ) || current_user_can( 'manage_options' ) ) ): ?>
									<div class="p2020-sidebar__info-header-ellipsis">
										<?php
											$info_menu = new \P2020\EllipsisMenu();
											if ( current_user_can( 'customize' ) && ! has_header_image() ) {
												$info_menu->add_item( __( 'Upload a header image', 'p2020' ), $customizer_identity_url );
											}
											if ( current_user_can( 'customize' ) ) {
												$info_menu->add_item( __( 'Customize', 'p2020' ), $customizer_url );
											}
											if ( current_user_can( 'manage_options' ) ) {
												$info_menu->add_item( __( 'Settings', 'p2020' ), $settings_url );
											}
											echo html_output( $info_menu->generate() );
										?>
									</div>
								<?php endif; ?>
							</div>

							<div class="p2020-sidebar__info-follow">
								<?php \P2020\Follow\render(); ?>
							</div>

							<div class="p2020-sidebar__info-blogdescription" data-customizer-blogdescription>
								<?php bloginfo( 'description' ); ?>
							</div>
						</section>

						<nav>
							<?php \P2020\Filter\render(); ?>
						</nav>
					</div><!-- .p2020-sidebar-padded-container -->
				</div><!-- .p2020-sidebar__info -->

				<?php do_action( 'before_sidebar' ); ?>
				<?php
					if ( is_page() && is_active_sidebar( 'sidebar-pages' ) ) {
						dynamic_sidebar( 'sidebar-pages' );
					} else {
						dynamic_sidebar( 'sidebar-1' );
					}
				?>
			</div><!-- [data-sidebar-primary] -->

			<div data-sidebar-secondary <?php if ( ! is_page() ) echo esc_attr( 'hidden' ); ?>>

				<div class="p2020-sidebar__menu">
					<div class="p2020-sidebar-padded-container">
						<div class="p2020-sidebar__menu-header">
							<h2><?php esc_html_e( 'Documents', 'p2020' ) ?></h2>
							<?php if ( current_user_can( 'editor' ) ) : ?>
								<div class="p2020-sidebar__menu-header-ellipsis">
									<?php
										$pages_menu = new \P2020\EllipsisMenu();
										$pages_menu->add_item( __( 'Manage pages', 'p2020' ), $pages_url );
										echo html_output( $pages_menu->generate() );
									?>
								</div>
							<?php endif; ?>
						</div>
						<?php \P2020\Menu\render_page_menu() ?>
					</div>
				</div>

				<div class="p2020-sidebar__menu">
					<div class="p2020-sidebar-padded-container">
						<div class="p2020-sidebar__menu-header">
							<h2><?php esc_html_e( 'Links', 'p2020' ) ?></h2>
							<?php if ( current_user_can( 'customize' ) ): ?>
								<div class="p2020-sidebar__menu-header-ellipsis">
									<?php
										$nav_menu = new \P2020\EllipsisMenu();
										$nav_menu->add_item( __( 'Manage links', 'p2020' ), $customizer_menu_url );
										echo html_output( $nav_menu->generate() );
									?>
								</div>
							<?php endif; ?>
						</div>
						<?php \P2020\Menu\render_nav_menu() ?>
					</div>
				</div>

			</div><!-- [data-sidebar-secondary] -->

			<footer class="p2020-sidebar__footer">
				<div class="p2020-sidebar-padded-container">
					<ul class="p2020-sidebar__footer-links">
						<li>
							<a href="<?php echo esc_url( $signup_url ) ?>">
								<?php esc_html_e( 'Create a new P2', 'p2020' ); ?>
							</a>
						</li>
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
						<li>
							<a href="<?php echo esc_url( $help_url ) ?>">
								<?php esc_html_e( 'Help', 'p2020' ); ?>
							</a>
						</li>
					</ul>

					<p class="p2020-sidebar__footer-credit">
						<?php esc_html_e( 'Powered by WordPress.com', 'p2020' ); ?>
					</p>
				</div>
			</footer>

		</div><!-- .p2020-sidebar__main -->

	</div>
