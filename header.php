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
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/vendor/html5.js" type="text/javascript"></script>
<![endif]-->

<?php
	wp_head();
?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<?php do_action( 'before' ); ?>
	<header id="masthead" class="site-header" role="banner">
		<a href="#" id="secondary-toggle" onclick="return false;"><strong><?php _e( 'Menu' ); ?></strong></a>
		<div class="hgroup">
			<?php
			$header_image = get_header_image();
			if ( ! empty( $header_image ) ) {
			?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<img src="<?php echo esc_url( add_query_arg( 'w', 276, $header_image ) ); ?>" class="header-image" alt="" />
				</a>
			<?php } ?>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
		</div><!-- .hgroup -->
		<nav id="site-navigation" class="navigation-main" role="navigation">
			<div class="screen-reader-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'p2020' ); ?>"><?php _e( 'Skip to content', 'p2020' ); ?></a></div>

			<?php wp_nav_menu( [ 'theme_location' => 'primary', 'fallback_cb' => false ] ); ?>
		</nav><!-- .navigation-main -->
	</header><!-- .site-header -->

	<div id="main" class="site-main">
