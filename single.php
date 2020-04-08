<?php
/**
 * The Template for displaying all single posts.
 *
 * @package p2020
 */

use function P2020\content_nav;

get_header(); ?>

<?php get_sidebar(); ?>

	<main id="content">

		<header class="page-header">
			<h2>
			<span class="controls">
				<?php do_action( 'p2020_view_controls' ); ?>
			</span>
			</h2>
		</header><!-- .page-header -->

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single' ); ?>

			<aside>
				<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() )
					comments_template();
				?>
			</aside>

			<?php content_nav( 'nav-below' ); ?>

		<?php endwhile; // end of the loop. ?>

	</main><!-- #content -->

<?php get_footer(); ?>
