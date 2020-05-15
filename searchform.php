<?php
/**
 * The template for displaying search forms
 *
 * @package p2020
 */
?>
	<form method="get" class="p2020-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
		<input
			type="search"
			id="s"
			name="s"
			class="p2020-searchform__field"
			value="<?php echo esc_attr( get_search_query() ); ?>"
			placeholder="<?php echo esc_attr_x( 'Search', 'placeholder', 'p2020' ); ?>"
			aria-label="<?php echo esc_attr_x( 'Search', 'placeholder', 'p2020' ); ?>"
		/>
	</form>
