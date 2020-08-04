<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package p2020
 */

get_header(); ?>

<?php get_sidebar(); ?>

	<main id="content">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<?php get_template_part( 'content', 'page' ); ?>
		<?php endwhile; ?>
	</main><!-- #content -->

<?php get_footer(); ?>
