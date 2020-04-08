<?php
/**
 * The template for displaying search forms
 *
 * @package p2020
 */
?>
	<form method="get" class="p2020-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
			<div class="p2020-searchform__container">
				<!-- To ensure the container is at least as wide as the translated placeholder text -->
				<span class="p2020-searchform__label" aria-hidden="true">
					<?php echo esc_attr_x( 'Search', 'placeholder', 'p2020' ); ?>
				</span>
				<input
					type="search"
					name="s"
					class="p2020-searchform__field"
					value="<?php echo esc_attr( get_search_query() ); ?>"
					placeholder="<?php echo esc_attr_x( 'Search', 'placeholder', 'p2020' ); ?>"
					aria-label="<?php echo esc_attr_x( 'Search', 'placeholder', 'p2020' ); ?>"
				/>
			</div>
	</form>
