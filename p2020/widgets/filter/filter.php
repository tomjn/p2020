<?php

namespace P2020;

require_once( WP_PLUGIN_DIR . '/o2/o2.php' );

require_once( __DIR__ . '/helper.php' );
require_once( __DIR__ . '/unread.php' );

class P2020_Filter_Widget extends \o2_Filter_Widget {

	var $filters;
	var $last_active;
	var $unread_posts;
	var $unread_comments;
	var $unread_mentions;

	const UNREAD_COUNT_DISPLAY_LIMIT = 99; // displays "99+" if unread is > 99

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

		if ( ! is_user_logged_in() ) {
			return;
		}

		$user = wp_get_current_user();

		$this->filters = [
			'posts' => [
				'label' => __( 'Recent updates', 'p2020' ),
				'url' => esc_url( add_query_arg( 'p2020_recent_posts', 1, home_url() ) ),
				'css_id' => 'widget-filter-recent-updates',
				'section' => 'team',
			],
			'comments' => [
				'label' => __( 'Recent comments', 'p2020' ),
				'url' => esc_url( add_query_arg( 'p2020_recent_comments', 1, home_url() ) ),
				'css_id' => 'widget-filter-recent-comments',
				'section' => 'team',
			],
			'mentions' => [
				'label' => __( 'Mentions', 'p2020' ),
				'url' => esc_url( add_query_arg( 'mentions', $user->user_nicename, home_url() ) ),
				'css_id' => 'widget-filter-my-mentions',
				'section' => 'me',
			],
			'myposts' => [
				'label' => __( 'My posts', 'p2020' ),
				'url' => esc_url( home_url( '/author/' . $user->user_nicename ) ),
				'css_id' => 'widget-filter-my-posts',
				'section' => 'me',
			],
		];

		// For changing the page title, to indicate active filter
		add_filter( 'o2_page_title', [ $this, 'page_title' ] );

		// Hide editor for filter views
		add_filter( 'o2_options', [ $this, 'hide_editor_for_filter_views' ] );

		// Retrieve activity and unread content during 'widgets_init'
		//     ie. before visit timestamps are updated
		$this->last_active = get_last_active();
		$this->unread_posts = get_unread_posts();
		$this->unread_comments = get_unread_comments();
		$this->unread_mentions = get_unread_mentions();

		// Add CSS class for unread posts
		// We do the is_filter_active() check inside the function, because our custom param
		//    isn't registered until then!
		add_filter( 'post_class', [ $this, 'add_class_for_unread_posts' ], 10, 3 );
		add_filter( 'comment_class', [ $this, 'add_class_for_unread_comments' ], 10, 3 );

		// Modify the posts query depending on which filter is active
		add_action( 'pre_get_posts', [ $this, 'alter_query_for_filter_views' ] );
		add_filter( 'posts_clauses', [ $this, 'alter_query_for_comment_view' ], 10, 2 );
		add_filter( 'o2_options', [ $this, 'no_posts_message' ] );
	}

	function alter_query_for_filter_views( $query ) {
		if ( ! $query->is_main_query() ) {
			return;
		}

		if ( is_filter_active( 'posts' ) || is_filter_active( 'comments' ) ) {
			$query->set( 'ignore_sticky_posts', 1 );
			$query->set( 'post_type', 'post' );
		}

		if ( is_filter_active( 'posts' ) ) {
			$query->set( 'author', -1 * get_current_user_id() );
			if ( ! empty( $this->last_active['posts'] ) ) {
				$query->set( 'date_query', [
						'after' => date( 'Y-m-d H:i:s e', $this->last_active['posts'] ),
						'inclusive' => true,
					]
				);
			}
		}
	}

	public function alter_query_for_comment_view( $clauses, $wp_query ) {
		global $wpdb;

		if ( ! is_user_logged_in() ) {
			return $clauses;
		}

		if ( ! $wp_query->is_main_query() ) {
			return $clauses;
		}

		if ( is_filter_active( 'comments' ) ) {
			// "SELECT $found_rows $distinct $fields FROM $wpdb->posts $join WHERE 1=1 $where $groupby $orderby $limits";
			$clauses['fields'] = "$wpdb->posts.*, MAX($wpdb->comments.comment_date_gmt) cdate";
			$clauses['join'] = "RIGHT JOIN $wpdb->comments ON (ID = comment_post_id)";

			// Exclude xposts from recent comments queries
			$clauses['where'] = "AND ( $wpdb->posts.post_title NOT LIKE 'x-post%' AND $wpdb->posts.post_content NOT LIKE 'x-post%' AND $wpdb->posts.post_content NOT LIKE 'x-comment%' )" .
				" AND $wpdb->comments.comment_approved = '1'" .
				" AND $wpdb->comments.user_id <> " . get_current_user_id();

			if ( ! empty( $this->last_active['comments'] ) ) {
				$clauses['where'] .= " AND $wpdb->comments.comment_date_gmt >= FROM_UNIXTIME({$this->last_active['comments']})";
			}

			$clauses['groupby'] = "ID";
			$clauses['orderby'] = "cdate DESC";
		}
		return $clauses;
	}

	function no_posts_message( $o2_options ) {
		if ( is_filter_active( 'posts' )  || is_filter_active( 'comments' ) ) {
			$o2_options['strings']['noPosts'] = __( "You’re all caught up!", 'p2020' );
		}

		return $o2_options;
	}

	function add_class_for_unread_posts( $classes, $class, $post_id ) {
		if ( is_filter_active( 'posts' ) &&
			is_array( $this->unread_posts ) && in_array( $post_id, $this->unread_posts ) ) {
			$classes[] = 'p2020-unread-post';
		}

		if ( is_filter_active( 'mentions' ) &&
				is_array( $this->unread_mentions['posts'] ) && in_array( $post_id, $this->unread_mentions['posts'] ) ) {
			$classes[] = 'p2020-unread-mention';
		}

		return $classes;
	}

	function add_class_for_unread_comments( $classes, $class, $comment_id ) {
		if ( is_filter_active( 'comments' ) &&
				is_array( $this->unread_comments ) && in_array( $comment_id, $this->unread_comments ) ) {
			$classes[] = 'p2020-unread-comment';
		}

		if ( is_filter_active( 'mentions' ) &&
				is_array( $this->unread_mentions['comments'] ) && in_array( $comment_id, $this->unread_mentions['comments'] ) ) {
			$classes[] = 'p2020-unread-mention';
		}

		return $classes;
	}

	function hide_editor_for_filter_views( $o2_options ) {
		if ( is_filter_active( 'posts' )  || is_filter_active( 'comments' ) ) {
			$o2_options['options']['showFrontSidePostBox'] = false;
		}

		return $o2_options;
	}

	function widget( $args, $instance ) {
		$title = ( isset( $instance['title'] ) ) ? $instance['title'] : '';
		$title = apply_filters( 'widget_title', $title );

		$profile_url = 'https://wordpress.com/me';

		if ( 0 < count( $this->filters ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput -- HTML from theme
			echo $args['before_widget'];

			if ( ! empty( $title ) ) {
				// phpcs:ignore WordPress.Security.EscapeOutput -- HTML from theme
				echo $args['before_title'] . $title . $args['after_title'];
			}

			// Get unread count at widget render time (page render time)
			$unread_count = get_unread_count( self::UNREAD_COUNT_DISPLAY_LIMIT + 1 );

			echo '<ul class="widget-filter-list">';
			foreach ( (array) $this->filters as $key => $item ) {
				if ( $item['section'] === 'team' ) {
					$this->render_filter_item( $unread_count, $key, $item );
				}
			}
			echo '</ul>';

			if ( is_user_logged_in() ) {
				echo '<div id="widget-filter-me">';
				echo '<div class="widget-filter-me-title">';
				echo '<h2 class="widget-filter-me-label">Me</h2>';
				echo '<a href="' . esc_url( $profile_url ) . '" class="widget-title-secondary-action">My profile</a>';
				echo '</div>';

				echo '<ul class="widget-filter-list">';
				foreach ( (array) $this->filters as $key => $item ) {
					if ( $item['section'] === 'me' ) {
						$this->render_filter_item( $unread_count, $key, $item );
					}
				}
				echo '</ul>';
				echo '</div>';
			}

			// phpcs:ignore WordPress.Security.EscapeOutput -- HTML from theme
			echo $args['after_widget'];
		}
	}

	function page_title( $title ) {
		$type = get_active_filter();
		if ( ! empty( $type ) ) {
			return $this->filters[ $type ]['label'];
		}

		return $title;
	}

	private function render_filter_item( $unread_count, $key, $item ) {
		$li_class = is_filter_active( $key ) ? "widget-filter-item is-active" : "widget-filter-item";
		echo '<li id="' . esc_attr( $item['css_id'] ) . '" class="' . esc_attr( $li_class ) . '" >';
			echo '<a href="' . esc_url( $item['url'] ) . '" class="widget-filter-item-link" >';
				echo '<span class="widget-filter-item-link-icon"></span>';
				echo '<span class="widget-filter-item-link-label">' . esc_html( $item['label'] ) . '</span>';
				$this->maybe_render_unread_count( $unread_count, $key );
			echo '</a>';
		echo '</li>';
	}

	private function maybe_render_unread_count( $unread_count, $key ) {
		if ( ! empty( $key ) && ! empty( $unread_count[ $key ] ) ) {
			$unread_count_display =
				( $unread_count[ $key ] > self::UNREAD_COUNT_DISPLAY_LIMIT ) ?
				self::UNREAD_COUNT_DISPLAY_LIMIT . '+' :
				$unread_count[ $key ];
		?>
			<span class="widget-filter-item-link-unread">
				<?php echo esc_html( $unread_count_display ); ?>
			</span>
		<?php }
	}
}

function p2020_filter_widget_init() {
	register_widget( __NAMESPACE__ . '\P2020_Filter_Widget' );
}
add_action( 'widgets_init', __NAMESPACE__ . '\p2020_filter_widget_init' );
