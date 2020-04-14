<?php

namespace P2020;

require_once( WP_PLUGIN_DIR . '/o2/o2.php' );

require_once( __DIR__ . '/helper.php' );
require_once( __DIR__ . '/unread.php' );

class P2020_Filter_Widget extends \o2_Filter_Widget {

	var $unread_count;

	function __construct() {

		\WP_Widget::__construct(
	 		'p2020-filter-widget', // Base ID
			'P2020 Filter Widget', // Name
			[ 'description' => __( 'An extension of the o2 filter widget, with unread counts.', 'p2020' ) ]
		);

		if ( is_active_widget( false, false, 'p2020-filter-widget', true ) ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'register_widget_scripts' ] );
			add_action( 'wp_enqueue_scripts', [ $this, 'register_widget_styles' ] );
		}

		$this->filters = [
			'all' => [
				'posts' => [
					'label' => __( 'Recent updates', 'p2020' ),
					'url' => esc_url(
						add_query_arg(
							[
								'p2020_recent_posts' => true,
							],
							home_url()
						)
					),
					'css_id' => 'widget-filter-recent-updates'
				],
				'comments' => [
					'label' => __( 'Recent comments', 'p2020' ),
					'url' => esc_url(
						add_query_arg(
							[
								'o2_recent_comments' => true, // required by o2/recent-comments module
								'p2020_recent_comments' => true,
							],
							home_url()
						)
					),
					'css_id' => 'widget-filter-recent-comments',
				],
			],
		];

		if ( is_user_logged_in() ) {
			$user = wp_get_current_user();
			$this->filters['me'] = [
				'mentions' => [
					'label' => __( 'Mentions', 'p2020' ),
					'url' => esc_url( add_query_arg( 'mentions', $user->user_login, home_url() ) ),
					'css_id' => 'widget-filter-my-mentions'
				],
				'myposts' => [
					'label' => __( 'My posts', 'p2020' ),
					'url' => esc_url( home_url( '/author/' . $user->user_login ) ),
					'css_id' => 'widget-filter-my-posts'
				]
			];
		}

		// For changing the page title, to indicate active filter
		add_filter( 'o2_page_title', [ $this, 'page_title' ] );
	}

	function widget( $args, $instance ) {
		$title = ( isset( $instance['title'] ) ) ? $instance['title'] : '';
		$title = apply_filters( 'widget_title', $title );

		$this->unread_count = get_unread_count();

		if ( 0 < count( $this->filters ) ) {
			echo $args['before_widget'];

			if ( ! empty( $title ) ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			echo '<ul class="widget-filter-list">';
			foreach ( (array) $this->filters['all'] as $key => $item ) {
				$this->render_filter_item( $key, $item );
			}
			echo '</ul>';

			if ( is_user_logged_in() ) {
				echo '<div id="widget-filter-me">';
				echo '<div class="widget-filter-me-title">';
				echo '<h2 class="widget-filter-me-label">Me</h2>';
				echo '<a href="" id="widget-filter-me-settings">My settings</a>';
				echo '</div>';

				echo '<ul class="widget-filter-list">';
				foreach ( (array) $this->filters['me'] as $key => $item ) {
					$this->render_filter_item( $key, $item );
				}
				echo '</ul>';
				echo '</div>';
			}

			echo $args['after_widget'];
		}
	}

	function page_title( $title ) {
		if ( is_filter_active( 'posts' ) && is_home() ) {
			return 'Recent updates';
		}

		return $title;
	}

	private function render_filter_item( $key, $item ) {
		$li_class = is_filter_active( $key ) ? "widget-filter-item is-active" : "widget-filter-item";
		echo '<li id="' . esc_attr( $item['css_id'] ) . '" class="' . $li_class . '" >';
			echo '<a href="' . esc_url( $item['url'] ) . '" class="widget-filter-item-link" >';
				echo '<span class="widget-filter-item-link-icon"></span>';
				echo '<span class="widget-filter-item-link-label">' . esc_html( $item['label'] ) . '</span>';
				$this->render_unread_count( $key );
			echo '</a>';
		echo '</li>';
	}

	private function render_unread_count( $key ) {
		if ( ! empty( $key ) && ! empty( $this->unread_count[ $key ] ) ) {
			echo '<span class="widget-filter-item-link-unread">' . esc_html( $this->unread_count[ $key ] ) . '</span>';
		}
	}
}

function p2020_filter_widget_init() {
	register_widget( __NAMESPACE__ . '\P2020_Filter_Widget' );
}
add_action( 'widgets_init', __NAMESPACE__ . '\p2020_filter_widget_init' );
