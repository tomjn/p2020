<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package p2020
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="HandheldFriendly" content="True">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="cleartype" content="on">

<?php // Will be overwritten with git commit hash on `gulp build` (see gulpfile) ?>
<meta name="p2020-build" revision="85901fb">

<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo esc_attr( get_template_directory_uri() ); ?>/js/vendor/html5.js" type="text/javascript"></script>
<![endif]-->

<?php
	wp_head();
?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">

	<?php do_action( 'before' ); ?>
	<header id="masthead" role="banner">

		<div class="screen-reader-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'p2020' ); ?>"><?php esc_html_e( 'Skip to content', 'p2020' ); ?></a></div>

		<div class="p2020-site-header">

			<button
				class="p2020-mobile-menu-toggle"
				aria-label="<?php esc_attr_e( 'Menu', 'p2020' ); ?>"
				data-mobile-menu-toggle
			></button>

			<div class="p2020-site-header__main">
				<h1 class="p2020-site-title" data-customizer-blogname>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
						<?php bloginfo( 'name' ); ?>
					</a>
				</h1>
				<?php wp_nav_menu( [
					'theme_location' => 'primary',
					'container' => 'nav',
					'container_class' => 'p2020-menu-primary',
					'fallback_cb' => false
				] ); ?>
			</div>

			<?php get_search_form(); ?>

		</div><!-- .p2020-site-header -->

	</header><!-- #masthead -->

	<div id="main-wrapper">
