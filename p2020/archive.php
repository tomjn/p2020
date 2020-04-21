<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package p2020
 */

use function P2020\content_nav;

get_header(); ?>

<?php get_sidebar(); ?>

	<main id="content">

		<?php do_action( 'p2020_post_editor' ); ?>

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php
						if ( is_category() ) :
							// phpcs:ignore WordPress.Security.EscapeOutput -- HTML expected
							printf( __( 'Category Archives: %s', 'p2020' ), '<span>' . single_cat_title( '', false ) . '</span>' );

						elseif ( is_tag() ) :
							// phpcs:ignore WordPress.Security.EscapeOutput -- HTML expected
							printf( __( 'Tag Archives: %s', 'p2020' ), '<span>' . single_tag_title( '', false ) . '</span>' );

						elseif ( is_author() ) :
							/* Queue the first post, that way we know
							 * what author we're dealing with (if that is the case).
							*/
							the_post();
							// phpcs:ignore WordPress.Security.EscapeOutput -- HTML expected
							printf( __( 'Author Archives: %s', 'p2020' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
							/* Since we called the_post() above, we need to
							 * rewind the loop back to the beginning that way
							 * we can run the loop properly, in full.
							 */
							rewind_posts();

						elseif ( is_day() ) :
							// phpcs:ignore WordPress.Security.EscapeOutput -- HTML expected
							printf( __( 'Daily Archives: %s', 'p2020' ), '<span>' . get_the_date() . '</span>' );

						elseif ( is_month() ) :
							// phpcs:ignore WordPress.Security.EscapeOutput -- HTML expected
							printf( __( 'Monthly Archives: %s', 'p2020' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

						elseif ( is_year() ) :
							// phpcs:ignore WordPress.Security.EscapeOutput -- HTML expected
							printf( __( 'Yearly Archives: %s', 'p2020' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

						elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
							esc_html_e( 'Asides', 'p2020' );

						elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
							esc_html_e( 'Images', 'p2020' );

						elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
							esc_html_e( 'Videos', 'p2020' );

						elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
							esc_html_e( 'Quotes', 'p2020' );

						elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
							esc_html_e( 'Links', 'p2020' );

						else :
							esc_html_e( 'Archives', 'p2020' );

						endif;
					?>

					<span class="controls">
						<?php do_action( 'p2020_view_controls' ); ?>
					</span>
				</h1>
				<?php
					if ( is_category() ) :
						// show an optional category description
						$category_description = category_description();
						if ( ! empty( $category_description ) ) :
							// phpcs:ignore WordPress.Security.EscapeOutput -- HTML expected
							echo apply_filters( 'category_archive_meta', '<div class="taxonomy-description">' . $category_description . '</div>' );
						endif;

					elseif ( is_tag() ) :
						// show an optional tag description
						$tag_description = tag_description();
						if ( ! empty( $tag_description ) ) :
							// phpcs:ignore WordPress.Security.EscapeOutput -- HTML expected
							echo apply_filters( 'tag_archive_meta', '<div class="taxonomy-description">' . $tag_description . '</div>' );
						endif;

					endif;
				?>
			</header><!-- .page-header -->

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

			<?php get_template_part( 'no-results', 'archive' ); ?>

		<?php endif; ?>

	</main><!-- #content -->

<?php get_footer(); ?>
