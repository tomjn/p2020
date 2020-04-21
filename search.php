<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package p2020
 */

use function P2020\content_nav;

get_header(); ?>

<?php get_sidebar(); ?>

	<main id="content">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php // phpcs:ignore WordPress.Security.EscapeOutput -- HTML expected ?>
					<?php printf( __( 'Search Results for: %s', 'p2020' ), '<span>' . get_search_query() . '</span>' ); ?>

					<span class="controls">
						<?php do_action( 'p2020_view_controls' ); ?>
					</span>
				</h1>
			</header><!-- .page-header -->

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'search' ); ?>

			<?php endwhile; ?>

			<?php content_nav( 'nav-below' ); ?>

		<?php else : ?>

			<?php get_template_part( 'no-results', 'search' ); ?>

		<?php endif; ?>

	</main><!-- #content -->

<?php get_footer(); ?>
