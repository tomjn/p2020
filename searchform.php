<?php
/**
 * The template for displaying search forms
 *
 * @package p2020
 */
?>
<div class="p2020-searchform" data-searchform>
	<form method="get" class="p2020-searchform__form" action="<?php echo esc_url( get_search_link() ); ?>" role="search">
		<input
			type="search"
			id="s"
			name="s"
			size="3"
			class="p2020-searchform__field"
			value="<?php echo esc_attr( get_search_query() ); ?>"
			aria-label="<?php esc_attr_e( 'Search', 'p2020' ); ?>"
		/>
	</form>
	<button
		class="p2020-searchform__close"
		aria-label="<?php esc_attr_e( 'Cancel search', 'p2020' ); ?>"
		data-searchform-cancel
	>
	</button>
</div>
