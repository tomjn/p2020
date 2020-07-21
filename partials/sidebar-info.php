<?php
/**
 * Info section of sidebar.
 *
 * @package p2020
 */

namespace P2020;

require_once( get_template_directory() . '/inc/class-ellipsis-menu.php' );
require_once( get_template_directory() . '/inc/filter/filter.php' );
require_once( get_template_directory() . '/inc/follow/follow.php' );

$site_slug = \WPCOM_Masterbar::get_calypso_site_slug( get_current_blog_id() );

$return_home_param = '?return=' . esc_url( get_blog_url() );
$customizer_url = 'https://wordpress.com/customize/' . $site_slug . $return_home_param;
$customizer_identity_url = 'https://wordpress.com/customize/identity/' . $site_slug . $return_home_param;
$settings_url = 'https://wordpress.com/settings/general/' . $site_slug;

$header_image_height = get_theme_support( 'custom-header', 'height' ) / 2 . 'px';
?>

<div class="p2020-sidebar-info">
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
						class="p2020-sidebar-info__image"
						width="<?php echo esc_attr( get_custom_header()->width ); ?>"
						height="<?php echo esc_attr( get_custom_header()->height ); ?>"
					>
				<?php else: ?>
					<div
						class="p2020-sidebar-info__image is-placeholder"
						style="height: <?php echo esc_attr( $header_image_height ); ?>;"
					>
					</div>
				<?php endif; ?>
			</a>

			<div class="p2020-sidebar-info__header">
				<h2 class="p2020-sidebar-info__header-blogname">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" data-customizer-blogname>
						<?php bloginfo( 'name' ); ?>
					</a>
				</h2>

				<?php if ( is_user_member_of_blog() &&
						( current_user_can( 'customize' ) || current_user_can( 'manage_options' ) ) ): ?>
					<div class="p2020-sidebar-info__header-ellipsis">
						<?php
							$info_menu = new \P2020\EllipsisMenu();
							if ( current_user_can( 'customize' ) && ! has_header_image() ) {
								$info_menu->add_item( __( 'Upload a header image', 'p2020' ), $customizer_identity_url );
							}
							if ( current_user_can( 'customize' ) ) {
								$info_menu->add_item( __( 'Customize sidebar', 'p2020' ), $customizer_url );
							}
							if ( current_user_can( 'manage_options' ) ) {
								$info_menu->add_item( __( 'Site settings', 'p2020' ), $settings_url );
							}
							echo html_output( $info_menu->generate() );
						?>
					</div>
				<?php endif; ?>
			</div>

			<div class="p2020-sidebar-info__follow">
				<?php \P2020\Follow\render(); ?>
			</div>

			<div class="p2020-sidebar-info__blogdescription" data-customizer-blogdescription>
				<?php bloginfo( 'description' ); ?>
			</div>
		</section>

		<nav>
			<?php \P2020\Filter\render(); ?>
		</nav>

	</div>
</div>
