<?php
/**
 * The Template for displaying all single posts.
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
			<?php get_template_part( 'content', 'single' ); ?>
		<?php endwhile; ?>
	</main>

<?php get_footer(); ?>
