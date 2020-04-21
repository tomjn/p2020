<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package p2020
 */

use function P2020\{
	get_page_number,
	content_nav
};

get_header(); ?>

<?php get_sidebar(); ?>

	<main id="content">

		<?php do_action( 'p2020_post_editor' ); ?>

		<header class="page-header">
			<h1 class="page-title">
			<?php if ( is_home() or is_front_page() ) : ?>

				<?php esc_html_e( 'Recent Updates', 'p2020' ); ?> <?php if ( get_page_number() > 1 ) printf( esc_html__( 'Page %s', 'p2020' ), esc_html( get_page_number() ) ); ?>

			<?php else : ?>

				<?php printf( esc_html_x( 'Updates from %s', 'Month name', 'p2020' ), esc_html( get_the_time( 'F, Y' ) ) ); ?>

			<?php endif; ?>

			<span class="controls">
				<?php do_action( 'p2020_view_controls' ); ?>
			</span>
			</h1>
		</header><!-- .page-header -->

		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to overload this in a child theme then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );
				?>

			<?php endwhile; ?>

			<?php content_nav( 'nav-below' ); ?>

		<?php else : ?>

			<?php get_template_part( 'no-results', 'index' ); ?>

		<?php endif; ?>

	</main><!-- #content -->

<?php get_footer(); ?>
