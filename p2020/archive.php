<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
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
			<?php get_template_part( 'content', get_post_format() ); ?>
		<?php endwhile; ?>
	</main>

<?php get_footer(); ?>
