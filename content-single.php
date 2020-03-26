<?php
/**
 * @package p2020
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<div class="entry-meta">
			<?php if ( !is_page() ) :
				$author_posts_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
					$posts_by_title = sprintf(
					__( 'Posts by %1$s ( @%2$s )', 'p2020' ),
					get_the_author_meta( 'display_name' ),
					get_the_author_meta( 'user_nicename' )
				); ?>
			<a href="<?php echo esc_url( $author_posts_url ); ?>" title="<?php echo esc_attr( $posts_by_title ); ?>" class="author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), 48 ); ?>
			</a>
			<?php endif; ?>

			<?php if ( ! is_page() ) : ?>
				<a href="<?php echo esc_url( $author_posts_url ); ?>" title="<?php echo esc_attr( $posts_by_title ); ?>" class="entry-author"><?php the_author(); ?></a>
			<?php endif; ?>
			<span class="entry-date">
				<?php breathe_date_time_with_microformat(); ?>
			</span>
			<span class="entry-actions">
				<?php do_action( 'breathe_post_actions' ); ?>
			</span>
			<?php if ( is_object_in_taxonomy( get_post_type(), 'post_tag' ) ) : ?>
				<span class="entry-tags">
					<?php breathe_tags_with_count( '', __( '<br />Tags:' , 'p2020' ) .' ', ', ', ' &nbsp;' ); ?>&nbsp;
				</span>
			<?php endif; ?>

			<?php do_action( 'breathe_header_entry_meta' ); ?>
		</div><!-- .entry-meta -->

		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( [ 'before' => '<div class="page-links">' . __( 'Pages:', 'p2020' ), 'after' => '</div>' ] ); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php do_action( 'breathe_footer_entry_meta' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-## -->
