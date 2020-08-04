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

get_header(); ?>

<?php get_sidebar(); ?>

	<?php
		/**
		 * Show/hide toggle for the new post editor in mobile.
		 *
		 * This div will be moved by JS to be right before the editor.
		 * We can't actually put the HTML there because everything inside main#content
		 * will be overwritten by O2.
		 */
	?>
	<div class="p2020-mobile-new-post" data-p2020-mobile-new-post-controls>
		<button
			class="p2020-mobile-new-post__new"
			title="<?php esc_attr_e( 'New post', 'p2020' ); ?>"
		>
		</button>
		<button
			class="p2020-mobile-new-post__cancel"
		>
			<?php esc_html_e( 'Cancel', 'p2020' ); ?>
		</button>
	</div>


	<main id="content">

		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<?php
				get_template_part( 'content', get_post_format() );
			?>
		<?php endwhile; ?>

	</main>

<?php get_footer(); ?>
