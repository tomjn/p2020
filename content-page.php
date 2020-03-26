<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package p2020
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( [ 'before' => '<div class="page-links">' . __( 'Pages:', 'p2020' ), 'after' => '</div>' ] ); ?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
