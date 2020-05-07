<?php

namespace P2020;

class P2020_Pages_Widget extends \WP_Widget_Pages {

	public function __construct() {
		\WP_Widget::__construct(
			'p2020-pages-widget', // Base ID
			__( 'P2020 Pages', 'p2020' ), // Name
			[
				'description' => __( 'An extension of the Pages widget.', 'p2020' ),
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
				if ( current_user_can( 'administrator' ) ) {
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
		// Add expand/collapse icons, 'add page' link icon
		$page_item_pattern = '/(<li .* page-item-([0-9]+).*>)(<a .*>.*<\/a>)/i';
		$enhanced_page_item = '$1
			<div class="widget-p2020-pages-label">
				<span class="widget-p2020-pages-expand"><button aria-label="Expand/Collapse"></button></span>
				<span class="widget-p2020-pages-link">$3</span>';
		if ( current_user_can( 'administrator' ) ) {
			$nonce = wp_create_nonce( 'page-nonce' );
			$enhanced_page_item .= '<span class="widget-p2020-pages-add">
					<a href="' . add_query_arg(
						[ 'p2020_pages_parent' => '$2', 'nonce' => $nonce ],
						admin_url( 'post-new.php?post_type=page' ) ).
						'" aria-label="Add child page" data-tippy-content="Add child page"></a>
				</span>';
		}
		$enhanced_page_item .= '</div>';
		$pages_html = preg_replace( $page_item_pattern, $enhanced_page_item, $pages_html );

		// Display an expand/collapse button if has children, default btn-expanded
		$pages_html = preg_replace(
			'/<li class="(.*)page_item_has_children([\S\s]+?)widget-p2020-pages-expand([\S\s]+?)button/i',
			'<li class="$1page_item_has_children$2widget-p2020-pages-expand$3button class="btn-expanded"',
			$pages_html
		);
		// Add 'btn-collapsed' class if not root and has children
		$pages_html = preg_replace(
			'/<ul class=["\']children["\']>(\s*)<li class="(.*)page_item_has_children([\S\s]+?)widget-p2020-pages-expand([\S\s]+?)button class="btn-expanded"/i',
			'<ul class="children">$1<li class="$2page_item_has_children$3widget-p2020-pages-expand$4button class="btn-collapsed"',
			$pages_html
		);

		return $pages_html;
	}

	function add_pages_query_vars( $vars ) {
		$vars[] = 'p2020_pages_parent';

		return $vars;
	}

	function set_parent_page() {
		$post_type = isset( $_GET['post_type'] ) ? sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) : null;
		if ( $post_type !== 'page' ) {
			return;
		}

		$nonce = isset( $_GET['nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['nonce'] ) ) : null;
		if ( ! wp_verify_nonce( $nonce , 'page-nonce' ) ) {
			return;
		}

		$parent = isset( $_GET[ 'p2020_pages_parent' ] ) ? intval( sanitize_text_field( wp_unslash( $_GET[ 'p2020_pages_parent' ] ) ) ) : 0 ;
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
