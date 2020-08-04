<?php
/**
 * The template for displaying Search Results pages.
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
			<?php get_template_part( 'content', 'search' ); ?>
		<?php endwhile; ?>
	</main><!-- #content -->

<?php get_footer(); ?>
