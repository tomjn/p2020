<?php

namespace P2020;

class P2020_Pages_Widget extends \WP_Widget_Pages {

	// Whitelisted qvar in Calypso, should we add our own?
	// Currently, it looks safe to repurpose this qvar.
	const PARENT_PAGE_QVAR = 'fse_parent_post';

	public function __construct() {
		\WP_Widget::__construct(
			'p2020-pages-widget', // Base ID
			__( 'P2 Pages', 'p2020' ), // Name
			[
				'description' => __( 'Displays a tree outline of your site\'s pages.', 'p2020' ),
				'customize_selective_refresh' => true,
			]
		);

			if ( is_active_widget( false, false, 'p2020-pages-widget', true ) ) {
				add_action( 'wp_enqueue_scripts', [ $this, 'p2020_scripts' ] );
				add_filter( 'query_vars',  [ $this, 'add_pages_query_vars' ] );
				add_action( 'admin_print_scripts-post-new.php', [ $this, 'set_parent_page' ] );
		}
	}

	function p2020_scripts() {
		wp_enqueue_script( 'p2020-pages-tree', get_template_directory_uri() . '/widgets/pages/js/tree.js', [ 'jquery' ] );
	}

	public function widget( $args, $instance ) {
		$title = $instance['title'] ?? __( 'Pages', 'p2020' );
		$sortby = empty( $instance['sortby'] ) ? 'menu_order' : $instance['sortby'];
		$exclude = empty( $instance['exclude'] ) ? '' : $instance['exclude'];

		if ( 'menu_order' === $sortby ) {
			$sortby = 'menu_order, post_title';
		}

		$pages_html = wp_list_pages( [
			'title_li' => '',
			'echo' => 0,
			'sort_column' => $sortby,
			'exclude' => $exclude,
		] );

		if ( ! empty( $pages_html ) ) {
			$site_slug = \WPCOM_Masterbar::get_calypso_site_slug( get_current_blog_id() );

			echo html_output( $args['before_widget'] );
			if ( $title ) {
				$widget_title = $args['before_title'] . $title;
				if ( is_user_member_of_blog() && current_user_can( 'administrator' ) ) {
					$manage_pages_link = "https://wordpress.com/pages/${site_slug}";
					$widget_title .= '<a class="widget-title-secondary-action" href="' . $manage_pages_link . '">' . __( 'Manage', 'p2020' ) . '</a>';
				}
				$widget_title .= $args['after_title'];
				echo html_output( $widget_title );
			}

			$pages_html = $this->enhance_page_items( $pages_html );
			?>
			<ul>
				<?php echo html_output( $pages_html ); ?>
			</ul>
			<?php
			echo html_output( $args['after_widget'] );
		}
	}

	private function enhance_page_items( $pages_html ) {
		$site_slug = \WPCOM_Masterbar::get_calypso_site_slug( get_current_blog_id() );
		$admin_edit_page_url = "https://wordpress.com/block-editor/page/" . $site_slug;

		// Add expand/collapse icons, 'add page' link icon
		$page_item_pattern = '/(<li .* page-item-([0-9]+).*>)(<a .*>.*<\/a>)/i';
		$enhanced_page_item = '$1
			<div class="widget-p2020-pages-label">
				<span class="widget-p2020-pages-expand"><button aria-label="Expand/Collapse"></button></span>
				<span class="widget-p2020-pages-link">$3</span>';
		if ( current_user_can( 'administrator' ) ) {
			$enhanced_page_item .= '<span class="widget-p2020-pages-add">
					<a href="' .
						add_query_arg(
							[ self::PARENT_PAGE_QVAR => '$2' ],
							$admin_edit_page_url
						) .
						'" aria-label="Add page inside" data-tippy-content="Add page inside"></a>
				</span>';
		}
		$enhanced_page_item .= '</div>';
		$pages_html = preg_replace( $page_item_pattern, $enhanced_page_item, $pages_html );

		return $pages_html;
	}

	function add_pages_query_vars( $vars ) {
		$vars[] = self::PARENT_PAGE_QVAR;

		return $vars;
	}

	function set_parent_page() {
		$post_type = isset( $_GET['post_type'] ) ? sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) : null;
		if ( $post_type !== 'page' ) {
			return;
		}

		$parent = isset( $_GET[ self::PARENT_PAGE_QVAR ] ) ? intval( sanitize_text_field( wp_unslash( $_GET[ self::PARENT_PAGE_QVAR ] ) ) ) : 0 ;
		if ( ! $parent ) {
			return;
		}

		wp_enqueue_script( 'p2020-pages-new-page', get_template_directory_uri() . '/widgets/pages/js/new-page.js', [ 'jquery' ] );
		$data = [
			'parent' => $parent,
		];
		wp_localize_script( 'p2020-pages-new-page', 'p2020Pages', $data );
	}
}

function p2020_pages_widget_init() {
	register_widget( __NAMESPACE__ . '\P2020_Pages_Widget' );
}
add_action( 'widgets_init', __NAMESPACE__ . '\p2020_pages_widget_init' );
