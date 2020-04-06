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

use function P2020\content_nav;

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

		<header class="page-header">
			<h1 class="page-title">
				<?php the_title(); ?>

				<span class="controls">
					<?php do_action( 'p2020_view_controls' ); ?>
				</span>
			</h1>

			<span class="entry-actions">
				<?php do_action( 'p2020_post_actions' ); ?>
			</span>

			<?php do_action( 'p2020_header_entry_meta' ); ?>
		</header><!-- .page-header -->

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'page' ); ?>

			<aside>
				<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() )
					comments_template();
				?>
			</aside>

			<?php content_nav( 'nav-below' ); ?>

		<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
