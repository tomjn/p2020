<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package p2020
 */

if( ! is_active_sidebar( 'sidebar-1' ) )
	return;

$site_slug = \WPCOM_Masterbar::get_calypso_site_slug( get_current_blog_id() );

$invite_url = 'https://wordpress.com/people/new/' . $site_slug;
$customizer_url = 'https://wordpress.com/customize/' . $site_slug;
$help_url = 'https://wordpress.com/help';
?>

	<aside id="sidebar" class="p2020-sidebar">

		<?php if ( has_header_image() ) : ?>
			<img
				src="<?php header_image(); ?>"
				alt=""
				class="p2020-sidebar__header-image"
				width="<?php echo esc_attr( get_custom_header()->width ); ?>"
				height="<?php echo esc_attr( get_custom_header()->height ); ?>"
			>
		<?php endif; ?>

		<div class="p2020-sidebar__inner-main">

			<div class="p2020-sidebar__search">
				<?php get_search_form(); /* for mobile */ ?>
			</div>

			<?php do_action( 'before_sidebar' ); ?>
			<?php dynamic_sidebar( 'sidebar-1' ); ?>

			<footer class="p2020-sidebar__footer">
				<ul class="p2020-sidebar__footer-links">
					<?php if ( current_user_can( 'create_users' ) ) : ?>
						<li>
							<a href="<?php echo esc_url( $invite_url ) ?>">
								<?php esc_html_e( 'Invite people to your team', 'p2020' ); ?>
							</a>
						</li>
					<?php endif; ?>
					<?php if ( current_user_can( 'customize' ) ) : ?>
					<li>
						<a href="<?php echo esc_url( $customizer_url ) ?>">
							<?php esc_html_e( 'Customize your site', 'p2020' ); ?>
						</a>
					</li>
					<?php endif; ?>
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
			</footer>

		</div><!-- .p2020-sidebar__inner-main -->

	</aside>
