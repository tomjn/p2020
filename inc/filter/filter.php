<?php

namespace P2020\Filter;

use function P2020\get_blog_url;

require_once get_template_directory() . '/inc/filter/unread.php';
//require_lib( 'seen-posts' );

const QUERY_ROWS_LIMIT           = 100;
const UNREAD_COUNT_DISPLAY_LIMIT = 99; // displays "99+" if unread is > 99
const DAY_IN_SECONDS             = 60 * 60 * 24;

/**
 * Checks whether the current page is a 'filter page'.
 *
 * @param string $type Filter type.
 *
 * @return boolean
 */
function is_filter_active( string $type ): bool {
	global $wp;
	$user = wp_get_current_user();

	$nonce_check = false;

	if ( isset( $_GET['nonce'] ) ) {
		$nonce_check = wp_verify_nonce( sanitize_key( $_GET['nonce'] ), 'p2-filter' );
	}

	switch ( $type ) {
		case 'posts':
			return (bool) get_query_var( 'p2filter_posts' );
		case 'comments':
			return (bool) get_query_var( 'p2filter_comments' );
		case 'mentions':
			return ( ( strpos( $wp->request, 'mentions/' . $user->user_nicename ) === 0 )
				|| isset( $_GET['mentions'] ) && $nonce_check );
		case 'myposts':
			return ( strpos( $wp->request, 'author/' . $user->user_nicename ) === 0 ) && $nonce_check;
		case 'unresolved':
			return isset( $_GET['resolved'] ) && 'unresolved' === $_GET['resolved'] && $nonce_check;
		case 'resolved':
			return isset( $_GET['resolved'] ) && 'resolved' === $_GET['resolved'] && $nonce_check;
	}

	return false;
}

/**
 * Returns the currently active filter, if any.
 *
 * @return string|null The active filter view.
 */
function get_active_filter() {
	$types = [ 'posts', 'comments', 'mentions', 'myposts', 'resolved', 'unresolved' ];
	foreach ( $types as $type ) {
		if ( is_filter_active( $type ) ) {
			return $type;
		}
	}

	return null;
}

/**
 * Returns an array of filter links.
 *
 * @return array An array of filter link items.
 */
function get_filters() : array {
	if ( ! is_user_logged_in() ) {
		return [];
	}

	$user        = wp_get_current_user();
	$last_active = \P2020\Filter\Unread\get_last_active();
	$nonce       = wp_create_nonce( 'p2-filter' );
	$filters     = [
		'posts'    => [
			'label'              => __( 'New posts', 'p2020' ),
			'url'                => esc_url(
				add_query_arg(
					[
						'p2filter_posts' => true,
						'ts'             => $last_active['posts'],
					],
					get_blog_url()
				)
			),
			'class'              => 'p2020-filter__recent-updates',
			'read_count_enabled' => true,
		],
		'comments' => [
			'label'              => __( 'New comments', 'p2020' ),
			'url'                => esc_url(
				add_query_arg(
					[
						'p2filter_comments' => true,
						'ts'                => $last_active['comments'],
					],
					get_blog_url()
				)
			),
			'class'              => 'p2020-filter__recent-comments',
			'read_count_enabled' => true,
		],
		'myposts'  => [
			'label'              => __( 'My posts', 'p2020' ),
			'url'                => esc_url(
				add_query_arg(
					[
						'nonce' => $nonce,
					],
					get_blog_url( '/author/' . $user->user_nicename )
				)
			),
			'class'              => 'p2020-filter__my-posts',
			'read_count_enabled' => false,
		],
	];

	if ( class_exists( 'Jetpack_Mentions' ) ) {
		$filters['mentions'] = [
			'label'              => __( 'My mentions ', 'p2020' ),
			'url'                => esc_url(
				add_query_arg(
					[
						'mentions' => $user->user_nicename,
						'ts'       => $last_active['mentions'],
						'nonce'    => $nonce,
					],
					get_blog_url()
				)
			),
			'class'              => 'p2020-filter__mentions',
			'read_count_enabled' => true,
		];
	}

	$o2_options               = get_option( 'o2_options' );
	$is_resolved_posts_active = ! empty( $o2_options['enable_resolved_posts'] );

	if ( \P2020\is_automattic() && $is_resolved_posts_active ) {
		$filters['unresolved'] = [
			'label'              => __( 'To do', 'p2020' ),
			'url'                => esc_url(
				add_query_arg(
					[
						'resolved' => 'unresolved',
						'nonce'    => $nonce,
					],
					get_blog_url()
				)
			),
			'class'              => 'p2020-filter__unresolved-posts',
			'read_count_enabled' => false,
		];
	}

	return $filters;
}

function get_unread_class( $item, $count ) : string {
	if ( empty( $item['read_count_enabled'] ) ) {
		return 'no-counts';
	}

	return $count > 0 ? 'has-unread' : 'zero-unread';
}

function get_unread_count_display( $count ) {
	if ( $count > UNREAD_COUNT_DISPLAY_LIMIT ) {
		return UNREAD_COUNT_DISPLAY_LIMIT . '+';
	}

	if ( 0 === $count ) {
		return '';
	}

	return $count;
}

function render_filter_item( $key, $item ) {
	$unread_count         = \P2020\Filter\Unread\get_unread_count( $key, UNREAD_COUNT_DISPLAY_LIMIT + 1 );
	$unread_count_display = get_unread_count_display( $unread_count );

	$unread_class = get_unread_class( $item, $unread_count );
	$active_class = is_filter_active( $key ) ? 'is-active' : '';

	return <<<P2020_FILTER_ITEM
		<li class="p2020-filter__item {$item['class']}">
			<a href="{$item['url']}" class="p2020-filter__item-link {$active_class}">
				<span class="p2020-filter__item-icon {$unread_class}">
					{$unread_count_display}
				</span>
				{$item['label']}
			</a>
		</li>
P2020_FILTER_ITEM;
}

/**
 * Display the filters
 *
 * @return void
 */
function render() {
	$filters = get_filters();

	echo '<ul class="p2020-filter">';
	foreach ( $filters as $key => $item ) {
		//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo render_filter_item( $key, $item );
	}
	echo '</ul>';
}

/**
 * Register enqueue scripts function.
 *
 * @return void
 */
function enqueue_scripts() {
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\scripts' );
}

function add_hooks() {
	// For changing the page title, i.e <title></title>.
	add_filter( 'wp_title_parts', __NAMESPACE__ . '\page_title' );

	// For changing the page title at the top of the posts feed.
	add_filter( 'o2_page_title', __NAMESPACE__ . '\o2_page_title' );

	// Modify o2 options for filter views
	add_filter( 'o2_options', __NAMESPACE__ . '\hide_editor_for_filter_views' );
	add_filter( 'o2_options', __NAMESPACE__ . '\hide_app_controls_for_comment_view' );

	// Add CSS class for unread content
	// We do the is_filter_active() check inside the function, because our custom param
	// isn't registered until then!
	add_filter( 'post_class', __NAMESPACE__ . '\custom_classes_for_posts', 10, 3 );
	add_filter( 'comment_class', __NAMESPACE__ . '\custom_classes_for_comments', 10, 3 );

	// Modify the posts query depending on which filter is active.
	add_action( 'pre_get_posts', __NAMESPACE__ . '\alter_query_for_filter_views' );
	add_filter( 'posts_clauses', __NAMESPACE__ . '\alter_query_for_comment_view', 10, 2 );
	add_filter( 'o2_options', __NAMESPACE__ . '\no_posts_message' );

	// Infinite Scroll.
	add_filter( 'infinite_scroll_ajax_url', __NAMESPACE__ . '\infinite_scroll_ajax_url' );
	add_filter( 'infinite_scroll_query_args', __NAMESPACE__ . '\infinite_scroll_query_args' );
}

function scripts() {
	if ( is_filter_active( 'posts' ) || is_filter_active( 'comments' ) ) {
		wp_enqueue_script( 'p2020-filter-no-posts', get_template_directory_uri() . '/inc/filter/js/no-posts.js', [ 'jquery' ], '20200801', true );
		$data = [
			'homeUrl'     => esc_url( get_blog_url() ),
			'homeMessage' => __( 'Return to home', 'p2020' ),
		];
		wp_localize_script( 'p2020-filter-no-posts', 'p2020FilterNoPosts', $data );
	}

	if ( is_filter_active( 'comments' ) ) {
		wp_enqueue_script( 'p2020-filter-read-more', get_template_directory_uri() . '/inc/filter/js/read-more.js', [ 'jquery' ], '20200801', true );
		$data = [
			'readPost'     => __( 'Read full post', 'p2020' ),
			'readComment'  => __( 'Read more', 'p2020' ),
			'moreComments' => __( ' more comment(s)', 'p2020' ),
		];
		wp_localize_script( 'p2020-filter-read-more', 'p2020FilterReadMore', $data );
	}
}

/**
 * Callback for 'pre_get_posts' action hook.
 * Modify posts query to only return posts published after a given cutoff.
 */
function alter_query_for_filter_views( $query ) {
	if ( ! $query->is_main_query() ) {
		return;
	}

	if ( is_filter_active( 'posts' ) || is_filter_active( 'comments' ) ) {
		$query->set( 'ignore_sticky_posts', 1 );
		$query->set( 'post_type', 'post' );
	}

	if ( is_filter_active( 'posts' ) ) {
		if ( \P2020\is_automattician() ) {
			// Use new seen system for a12s
			// posts after user subscription joined / feature release date.
			$user_subscription_timestamp = \P2020\Filter\Unread\get_user_subscription_timestamp();
			if ( $user_subscription_timestamp ) {
				$query->set(
					'date_query',
					[
						'after'     => gmdate( 'Y-m-d H:i:s e', $user_subscription_timestamp ),
						'inclusive' => true,
					]
				);
			}

			// post id not in seen
			$unseen_posts_ids = \P2020\Filter\Unread\get_unseen_blog_posts( $user_subscription_timestamp );
			if ( ! empty( $unseen_posts_ids ) ) {
				$query->set( 'post__in', $unseen_posts_ids );
			}
		} else {
			// use old high watermark for non a12s (to be removed)
			$query->set( 'author', -1 * get_current_user_id() );
			$posts_cutoff = \P2020\Filter\Unread\get_content_cutoff_ts( 'posts' );
			if ( ! empty( $posts_cutoff ) ) {
				$query->set(
					'date_query',
					[
						'after'     => gmdate( 'Y-m-d H:i:s e', \P2020\Filter\Unread\get_content_cutoff_ts( 'posts' ) ),
						'inclusive' => true,
					]
				);
			}
		}
	}
}

/**
 * Callback for 'posts_clauses' filter hook.
 * Modify posts query to only return posts with comments published after a given cutoff.
 */
function alter_query_for_comment_view( $clauses, $wp_query ) {
	global $wpdb;

	if ( ! $wp_query->is_main_query() ) {
		return $clauses;
	}

	if ( ! is_user_logged_in() ) {
		return $clauses;
	}

	if ( is_filter_active( 'comments' ) ) {
		// "SELECT $found_rows $distinct $fields FROM $wpdb->posts $join WHERE 1=1 $where $groupby $orderby $limits";
		$clauses['fields'] = "$wpdb->posts.*, MAX($wpdb->comments.comment_date_gmt) cdate";
		$clauses['join']   = "RIGHT JOIN $wpdb->comments ON (ID = comment_post_id)";

		// Exclude xposts from recent comments queries
		$clauses['where'] = "AND ( $wpdb->posts.post_title NOT LIKE 'x-post%' AND $wpdb->posts.post_content NOT LIKE 'x-post%' AND $wpdb->posts.post_content NOT LIKE 'x-comment%' )" .
			" AND $wpdb->comments.comment_approved = '1'" .
			" AND $wpdb->comments.user_id <> " . get_current_user_id();

		$comments_cutoff = \P2020\Filter\Unread\get_content_cutoff_ts( 'comments' );
		if ( $comments_cutoff ) {
			$clauses['where'] .= " AND $wpdb->comments.comment_date_gmt >= FROM_UNIXTIME({$comments_cutoff})";
		}

		$clauses['groupby'] = 'ID';
		$clauses['orderby'] = 'cdate DESC';
	}

	return $clauses;
}

/**
 * Callback for 'post_class' filter hook.
 * Add special CSS classes for unread posts, and posts with unread comments and mentions.
 */
// phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
// phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundBeforeLastUsed
// Reason: $class unused but passed by filter
function custom_classes_for_posts( $classes, $class, $post_id ) {
	// If a post appears in the recent posts filter view, automatically add "unread" class.
	if ( is_filter_active( 'posts' ) ) {
		$classes[] = 'p2020-unread-post';
	}

	// If a post appears in the recent comments filter view, automatically add "trim post" class
	if ( is_filter_active( 'comments' ) ) {
		$classes[] = 'p2020-post-read-more';
		// Remove project thread class to avoid styling clashes -- project thread posts are usually styled
		// differently, but inside the comments view, we want them to be just regular posts.
		$classes = array_diff( $classes, [ 'tag-project-thread' ] );
	}

	// If a post appears in the recent mentions view, we need to cross-check it against list
	//    of unread posts and posts with unread mentions
	if ( is_filter_active( 'mentions' ) ) {
		$mentions_cutoff = \P2020\Filter\Unread\get_content_cutoff_ts( 'mentions' );
		$unread_mentions = \P2020\Filter\Unread\get_mentions_after_ts( $mentions_cutoff, QUERY_ROWS_LIMIT );
		if ( is_array( $unread_mentions['posts'] ) && in_array( $post_id, $unread_mentions['posts'], true ) ) {
			$classes[] = 'p2020-unread-mention';
		}
	}

	return $classes;
}
// phpcs:enable VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
// phpcs:enable Generic.CodeAnalysis.UnusedFunctionParameter.FoundBeforeLastUsed

/**
 * Callback for 'comment_class' filter hook.
 * Add special CSS classes for unread comments and comments with unread mentions.
 */
// phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
// phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundBeforeLastUsed
// Reason: $class unused but passed by filter
function custom_classes_for_comments( $classes, $class, $comment_id ) {
	// Flag unread comments, trim everything else
	if ( is_filter_active( 'comments' ) ) {
		$comments_cutoff = \P2020\Filter\Unread\get_content_cutoff_ts( 'comments' );
		$unread_comments = \P2020\Filter\Unread\get_comments_after_ts( $comments_cutoff, QUERY_ROWS_LIMIT );
		if ( is_array( $unread_comments ) && in_array( $comment_id, $unread_comments, true ) ) {
			$classes[] = 'p2020-unread-comment';
		} else {
			$classes[] = 'p2020-comment-read-more';
		}
	}

	// Flag comments with unread mentions
	if ( is_filter_active( 'mentions' ) ) {
		$mentions_cutoff = \P2020\Filter\Unread\get_content_cutoff_ts( 'mentions' );
		$unread_mentions = \P2020\Filter\Unread\get_mentions_after_ts( $mentions_cutoff, QUERY_ROWS_LIMIT );
		if ( is_array( $unread_mentions['comments'] ) && in_array( $comment_id, $unread_mentions['comments'], true ) ) {
			$classes[] = 'p2020-unread-mention';
		}
	}

	return $classes;
}
// phpcs:enable VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
// phpcs:enable Generic.CodeAnalysis.UnusedFunctionParameter.FoundBeforeLastUsed

/**
 * Callback for 'o2_options' filter hook.
 * Modify the empty page message.
 */
function no_posts_message( $o2_options ) {
	if ( is_filter_active( 'posts' ) || is_filter_active( 'comments' ) ) {
		$no_posts_message                       = __( 'You’re all caught up!', 'p2020' );
		$o2_options['strings']['noPosts']       = $no_posts_message;
		$o2_options['strings']['noPostsMobile'] = $no_posts_message;
	}

	return $o2_options;
}

/**
 * Callback for 'o2_options' filter hook.
 * Hide the front-page editor when inside a filter view.
 */
function hide_editor_for_filter_views( $o2_options ) {
	$active_filter = get_active_filter();
	if ( ! empty( $active_filter ) ) {
		$o2_options['options']['showFrontSidePostBox'] = false;
	}

	return $o2_options;
}

/**
 * Callback for 'o2_options' filter hook.
 * Hide the comments toggler when inside the recent comments filter view.
 */
function hide_app_controls_for_comment_view( $o2_options ) {
	if ( is_filter_active( 'comments' ) ) {
		$o2_options['appControls'] = [];
	}

	return $o2_options;
}

/**
 * Callback for 'o2_page_title' filter hook.
 * Modify the page/feed title when inside a filter view.
 */
function o2_page_title( $title ) {
	$active_filter = get_active_filter();

	if ( empty( $active_filter ) ) {
		return $title;
	}

	global $wp_query;

	if ( 'unresolved' === $active_filter ) {
		/* translators: %d is the number of posts marked as "To Do" */
		return sprintf( __( 'To do (%d)', 'p2020' ), $wp_query->found_posts );
	}

	if ( 'resolved' === $active_filter ) {
		/* translators: %d is the number of posts marked as "To Do" */
		return sprintf( __( 'Done (%d)', 'p2020' ), $wp_query->found_posts );
	}

	if ( 'posts' === $active_filter && 0 === $wp_query->found_posts ) {
		return '';
	}

	if ( 'comments' === $active_filter && 0 === $wp_query->found_posts ) {
		return '';
	}

	$filters = get_filters();
	if ( ! empty( $filters[ $active_filter ]['label'] ) ) {
		return $filters[ $active_filter ]['label'];
	}

	return $title;
}

/**
 * Callback for 'wp_title_parts' filter hook.
 * Modify the page title when inside a filter view.
 */
function page_title( $title_parts ) {
	$active_filter = get_active_filter();

	if ( 'unresolved' === $active_filter ) {
		return [ __( 'To do', 'p2020' ) ];
	}

	if ( 'resolved' === $active_filter ) {
		return [ __( 'Done', 'p2020' ) ];
	}

	return $title_parts;
}

/**
 * Callback for 'infinite_scroll_ajax_url' filter hook.
 *    (https://opengrok.a8c.com/source/xref/jetpack/modules/infinite-scroll/infinity.php#762)
 * Add query args for making filter views work.
 */
function infinite_scroll_ajax_url( $ajax_url ) {
	$active_filter = get_active_filter();
	if ( empty( $active_filter ) ) {
		return $ajax_url;
	}

	$query_args = [];
	if ( 'posts' === $active_filter ) {
		$query_args = [ 'p2filter_posts' => true ];
	} elseif ( 'comments' === $active_filter ) {
		$query_args = [ 'p2filter_comments' => true ];
	}
	$query_args['ts'] = \P2020\Filter\Unread\get_content_cutoff_ts( $active_filter );

	$ajax_url = add_query_arg( $query_args, $ajax_url );

	return $ajax_url;
}

/**
 * Callback for 'infinite_scroll_query_args' filter hook.
 *    (https://opengrok.a8c.com/source/xref/jetpack/modules/infinite-scroll/infinity.php#1310)
 * Add query args for making filter views work.
 */
function infinite_scroll_query_args( $query_args ) {
	if ( ! is_array( $query_args ) ) {
		return $query_args;
	}

	$active_filter = get_active_filter();
	if ( empty( $active_filter ) ) {
		return $query_args;
	}

	if ( 'posts' === $active_filter ) {
		$query_args['p2filter_posts'] = true;
	} elseif ( 'comments' === $active_filter ) {
		$query_args['p2filter_comments'] = true;
	}
	$query_args['ts'] = \P2020\Filter\Unread\get_content_cutoff_ts( $active_filter );

	return $query_args;
}
