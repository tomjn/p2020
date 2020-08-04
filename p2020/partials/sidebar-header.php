<?php
/**
 * Sidebar header.
 *
 * @package p2020
 */

namespace P2020;

$site_name = get_bloginfo( 'name', 'display' );
?>

<nav class="p2020-sidebar-header">

	<div class="p2020-sidebar-header__group-left">
		<h1 class="p2020-sidebar-header__heading">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"
				class="p2020-sidebar-header__logo"
				aria-label="<?php echo esc_attr( $site_name ); ?> P2"
			>
				<?php get_template_part( 'partials/logo-p2' ); ?>
			</a>
		</h1>

		<button
			class="p2020-sidebar-header__mobile-toggle-button"
			aria-label="<?php esc_attr_e( 'Menu', 'p2020' ); ?>"
			aria-expanded="false"
			data-sidebar-mobile-toggle
		>
		</button>
	</div>

	<span class="p2020-sidebar-header__mobile-title" aria-hidden="true">
		<?php echo esc_attr( $site_name ); ?>
	</span>

	<div class="p2020-sidebar-header__group-right">
		<?php get_search_form(); ?>
		<button
			class="p2020-sidebar-header__hamburger-button"
			aria-label="<?php esc_attr_e( 'Documents and Links', 'p2020' ); ?>"
			aria-expanded="<?php echo is_page() ? 'true' : 'false'; ?>"
			data-sidebar-hamburger
		>
		</button>
	</div>

</nav>
