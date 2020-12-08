<?php

namespace P2020\Filter\Unread;

if ( file_exists( WPMU_PLUGIN_DIR . '/inline-terms/mentions.php' ) ) {
	require_once WPMU_PLUGIN_DIR . '/inline-terms/mentions.php';
}
if ( function_exists( 'require_lib' ) ) {
	require_lib( 'seen-posts' );
}

use function P2020\Filter\is_filter_active;
use function P2020\is_automattician;
use \FeedBag;

/**
 * Register additional URL query vars, so we can pull them via
 *     get_query_var(...) later.
 *
 * @param array $vars
 *
 * @return array
 */
function add_unread_query_vars( array $vars ) : array {
	$vars[] = 'p2filter_posts';
	$vars[] = 'p2filter_comments';
	$vars[] = 'ts';

	return $vars;
}

add_filter( 'query_vars', __NAMESPACE__ . '\add_unread_query_vars' );

/**
 * Get user meta 'p2020_last_active', used for tracking user visit across
 * special pages.
 *
 * @param bool $all_sites get last active for this site or all sites?
 *
 * @return array
 */
function get_last_active( $all_sites = false ) : array {
	if ( ! is_user_logged_in() ) {
		return [];
	}

	$user_id = get_current_user_id();
	$blog_id = get_current_blog_id();

	$last_active = get_user_meta( $user_id, 'p2020_last_active', true ); // empty string if var not found
	$last_active = $last_active && is_string( $last_active ) ? unserialize( $last_active ) : [];

	if ( $all_sites ) {
		return $last_active;
	}

	return $last_active[ $blog_id ] ?? [];
}

/**
 * Update the user meta 'p2020_last_active, used for tracking user visit across
 *     special pages.
 *
 * @param string $type The filter type, e.g. 'posts' for unread posts,
 *    'comments' for unread comments.
 */
function update_last_active( $type ) {
	if ( ! is_user_logged_in() ) {
		return [];
	}

	$user_id = get_current_user_id();
	$blog_id = get_current_blog_id();

	$last_active = get_last_active( true );

	// TODO maybe check if malformed and rebuild if so

	$last_active[ $blog_id ][ $type ] = time();
	update_user_meta( $user_id, 'p2020_last_active', serialize( $last_active ) );
}

/**
 * For kickstarting user visit history.
 */
function initialize_visit_history() {
	if ( ! is_user_logged_in() ) {
		return;
	}

	update_last_active( 'posts' );
	update_last_active( 'comments' );
	update_last_active( 'mentions' );
}

/**
 * Callback function for logging user visits to pages of interest.
 */
function log_user_visit() {
	if ( ! is_user_logged_in() ) {
		return;
	}

	if ( wp_doing_ajax() ) {
		return;
	}

	if ( empty( get_last_active() ) ) {
		initialize_visit_history();

		return;
	}

	if ( is_single() && is_automattician() ) {
		mark_as_seen();
	} elseif ( is_filter_active( 'posts' ) ) {
		if ( is_automattician() ) {
			// use new seen system for a12s
			mark_all_as_seen();
		} else {
			// use old high watermark for non a12s (to be removed)
			update_last_active( 'posts' );
		}
	} elseif ( is_filter_active( 'comments' ) ) {
		update_last_active( 'comments' );
	} elseif ( is_filter_active( 'mentions' ) ) {
		update_last_active( 'mentions' );
	}
}

if ( is_user_logged_in() ) {
	add_action( 'wp', __NAMESPACE__ . '\log_user_visit' );
}

/**
 * Retrieve posts created after given timestamp, inclusive.
 *     Own posts not included. If $ts is empty, return all posts.
 *
 * @param $ts Optional timestamp.
 * @param $limit Optional
 * @param $fields Optional
 *
 * @return array Posts (IDs only).
 */
function get_posts_after_ts( $ts = null, $limit = null, $fields = 'ids' ) : array {
	if ( empty( $ts ) ) {
		return [];
	}

	$args = [
		'post_type'      => 'post',
		'posts_per_page' => $limit ?? - 1,
		'author'         => - 1 * get_current_user_id(),
		'fields'         => $fields,
	];

	// If $ts is set, we filter on that
	$ts_condition       = [
		'after'     => gmdate( 'Y-m-d H:i:s e', $ts ),
		'inclusive' => true,
	];
	$args['date_query'] = [ $ts_condition ];

	$query = new \WP_Query( $args );

	return $query->posts;
}

/**
 * Retrieve comments created after given timestamp, inclusive.
 *     Own comments not included. If $ts is empty, return all comments.
 *
 * @param $ts Optional timestamp.
 *
 * @return array Comments (IDs only).
 */
function get_comments_after_ts( $ts = null, $limit = null ) {
	if ( empty( $ts ) ) {
		return [];
	}

	$args = [
		'post_type'      => 'post',
		'type'           => 'comment',
		'author__not_in' => [ get_current_user_id() ],
		'fields'         => 'ids',
	];

	$ts_condition = [
		'after'     => gmdate( 'Y-m-d H:i:s e', $ts ),
		'inclusive' => true,
	];

	$args['date_query'] = [ $ts_condition ];

	if ( isset( $limit ) ) {
		$args['number'] = $limit;
	}

	$query = new \WP_Comment_Query( $args );

	return $query->get_comments();
}

/**
 * Retrieve posts and comments with mentions of current user,
 *     submitted after given timestamp, inclusive.
 *     Own posts and comments not included. If $ts is empty, return all content.
 *
 * @param $ts Optional timestamp.
 *
 * @return array Posts and comments (IDs only).
 */

function get_mentions_after_ts( $ts = null, $limit = null ) {
	$mentions = [
		'posts'    => [],
		'comments' => [],
	];

	if ( empty( $ts ) ) {
		return $mentions;
	}

	if ( ! class_exists( 'Jetpack_Mentions' ) ) {
		return $mentions;
	}

	$user         = wp_get_current_user();
	$unread_posts = get_posts_after_ts( $ts, $limit );
	foreach ( $unread_posts as $post_id ) {
		$post_mentions = \Jetpack_Mentions::get_post_mentions( $post_id );
		if ( in_array( $user->user_nicename, $post_mentions, true ) ) {
			$mentions['posts'][] = (int) $post_id;
		}
	}

	$unread_comments = get_comments_after_ts( $ts, $limit );
	foreach ( $unread_comments as $comment_id ) {
		$comment_mentions = \Jetpack_Mentions::get_comment_mentions( $comment_id );
		if ( in_array( $user->user_nicename, $comment_mentions, true ) ) {
			$mentions['comments'][] = (int) $comment_id;
		}
	}

	return $mentions;
}

/**
 * Get unread count statistics for given filter
 *
 * @param string $key filter type
 * @param int|null $limit
 *
 * @return 0 Unread count for posts, comments and mentions.
 */
function get_unread_count( $key, $limit = null ) {
	if ( ! is_user_logged_in() ) {
		return null;
	}

	$last_active = get_last_active();

	switch ( $key ) {
		case 'posts':
			if ( is_automattician() ) {
				// use new seen system for a12s
				return get_unseen_posts_count();
			} else {
				// use old high watermark for non a12s (to be removed)
				return ( ! empty( $last_active['posts'] ) ) ? count( get_posts_after_ts( $last_active['posts'], $limit ) ) : 0;
			}

		case 'comments':
			return ( ! empty( $last_active['comments'] ) ) ?
				count( get_comments_after_ts( $last_active['comments'], $limit ) ) :
				0;

		case 'mentions':
			if ( ! empty( $last_active['mentions'] ) ) {
				$unread_mentions = get_mentions_after_ts( $last_active['mentions'], $limit );

				return count( $unread_mentions['posts'] ) + count( $unread_mentions['comments'] );
			}

			return 0;

		default:
			return 0;
	}
}

/**
 * In charge of figuring out the content cutoff (timestamp) when modifying
 *     queries to only return those published after a certain datetime.
 *
 * @param string $content_type
 */
function get_content_cutoff_ts( $content_type ) {
	if ( ! in_array( $content_type, [ 'posts', 'comments', 'mentions' ], true ) ) {
		return null;
	}

	$query_var_ts = get_query_var( 'ts' );
	if ( is_numeric( $query_var_ts ) ) {
		return (int) $query_var_ts;
	}

	$last_active = get_last_active();

	return $last_active[ $content_type ];
}

/**
 * Get post ids after given timestamp
 *
 * @param int null $timestamp given timestamp
 *
 * @return array
 */
function get_post_ids_after( $timestamp = null ) {
	if ( empty( $timestamp ) ) {
		return [];
	}

	$args = [
		'post_type'      => 'post',
		'fields'         => 'ids',
		'posts_per_page' => - 1,
	];

	// If $ts is set, we filter on that
	$ts_condition       = [
		'after'     => gmdate( 'Y-m-d H:i:s e', $timestamp ),
		'inclusive' => true,
	];
	$args['date_query'] = [ $ts_condition ];

	$query = new \WP_Query( $args );

	return $query->posts;
}

/**
 * Mark a blog post as seen
 */
function mark_as_seen() {
	$user_id = get_current_user_id();

	// get feed id
	$blog_id = (int) get_current_blog_id();
	$feed_id = (int) FeedBag::get_feed_id_for_blog_id( $blog_id );

	// get feed item id
	$post_object  = get_post();
	$post_hash    = FeedBag::hash_post( $post_object );
	$feed_item_id = (int) FeedBag::get_feed_item_by_hash( $feed_id, $post_hash );

	// mark as seen
	\SeenPosts\mark_as_seen( $user_id, $feed_id, [ $feed_item_id ], \SeenPosts\Constants\SOURCE_FRONTEND_P2 );
}

/**
 * Mark a blog as seen
 */
function mark_all_as_seen() {
	$user_id = get_current_user_id();

	// get feed id
	$blog_id = (int) get_current_blog_id();
	$feed_id = (int) FeedBag::get_feed_id_for_blog_id( $blog_id );

	// mark all as seen
	\SeenPosts\mark_all_as_seen( $user_id, [ $feed_id ], \SeenPosts\Constants\SOURCE_FRONTEND_P2 );
}

/**
 * Get user subscription date for current blog/user
 *
 * @return string|null
 */
function get_user_subscription_timestamp() {
	$user_id = get_current_user_id();
	$blog_id = get_current_blog_id();

	$user_subscription_date = userfeed_get_subscription_date_by_blog_id( $user_id, $blog_id );
	if ( ! $user_subscription_date ) {
		return 0;
	}

	return \SeenPosts\get_timestamp_threshold( strtotime( $user_subscription_date ) );
}

/**
 * Get unseen posts count for the current blog/user
 *
 * @return int
 */
function get_unseen_posts_count() {
	$start_time = microtime( true );
	$stat_name  = 'com.wordpress.seen_posts.p2.function.get_unseen_posts_count';
	$user_id    = get_current_user_id();
	$blog_id    = get_current_blog_id();

	// get feed id for blog
	$feed_id = FeedBag::get_feed_id_for_blog_id( $blog_id );

	$user_subscription_date = userfeed_get_subscription_date_by_blog_id( $user_id, $blog_id );
	if ( ! $user_subscription_date ) {
		// we have no subscription date for this feed => 0 unseen items
		\SeenPosts\log_timing( $stat_name . '.1', $start_time );

		return 0;
	}

	// get feed items after threshold
	$feed_items_count = \SeenPosts\get_feed_items_count( $feed_id, strtotime( $user_subscription_date ) );

	// get user feed seen counts
	$seen_entries = \SeenPosts\Repositories\SeenCounts::get_by_user_and_feed_ids( $user_id, [ $feed_id ] );

	if ( ! $seen_entries ) {
		// no seen entries available => all unseen items
		\SeenPosts\log_timing(
			$stat_name . '.2',
			$start_time,
			[
				'blog_id'                => $blog_id,
				'user_subscription_date' => $user_subscription_date,
				'feed_items_count'       => $feed_items_count,
			]
		);

		return $feed_items_count;
	}

	// get seen counts
	$seen_counts = [];
	foreach ( $seen_entries as $seen_entry ) {
		$entry_feed_id                 = (int) $seen_entry['feed_id'];
		$count                         = (int) $seen_entry['counts'];
		$seen_counts[ $entry_feed_id ] = $count;
	}
	$seen_count = $seen_counts[ $feed_id ];

	\SeenPosts\log_timing(
		$stat_name . '.3',
		$start_time,
		[
			'blog_id'                => $blog_id,
			'user_subscription_date' => $user_subscription_date,
			'feed_items_count'       => $feed_items_count,
			'seen_count'             => $seen_count,
		]
	);

	return max( $feed_items_count - $seen_count, 0 );
}

/**
 * Get unseen blog posts ids
 *
 * @param int $user_subscription_timestamp
 *
 * @return array list of posts ids that were not seen
 */
function get_unseen_blog_posts( $user_subscription_timestamp ) {
	$start_time = microtime( true );
	$stat_name  = 'com.wordpress.seen_posts.p2.function.get_unseen_blog_posts';
	$user_id    = get_current_user_id();
	$blog_id    = get_current_blog_id();

	// get blog posts ids after user subscribed
	$blog_posts_ids = get_post_ids_after( $user_subscription_timestamp );

	// get feed id for blog
	$feed_id = FeedBag::get_feed_id_for_blog_id( $blog_id );
	if ( ! $feed_id ) {
		\SeenPosts\log_timing( $stat_name . '.1', $start_time );

		return $blog_posts_ids;
	}

	// get seen entries
	$feed_items = \SeenPosts\Repositories\Seen::get_by_feed_id( $user_id, $feed_id );
	if ( ! $feed_items ) {
		\SeenPosts\log_timing( $stat_name . '.2', $start_time );

		return $blog_posts_ids;
	}

	// get feed info based on feed items
	$feed_items_data = FeedBag::get_item_data_by_ids( $feed_items );
	if ( ! $feed_items_data ) {
		\SeenPosts\log_timing( $stat_name . '.3', $start_time );

		return $blog_posts_ids;
	}

	// get post ids from feed data
	$seen_post_ids = [];
	foreach ( $feed_items_data as $feed_item_data ) {
		$post_object     = maybe_unserialize( $feed_item_data['data'] );
		$seen_post_ids[] = $post_object->post_id;
	}

	\SeenPosts\log_timing(
		$stat_name . '.4',
		$start_time,
		[
			'blog_id'                     => $blog_id,
			'feed_id'                     => $feed_id,
			'user_subscription_timestamp' => $user_subscription_timestamp,
			'blog_posts_ids'              => count( $blog_posts_ids ),
			'feed_items'                  => count( $feed_items ),
			'feed_items_data'             => count( $feed_items_data ),
			'seen_post_ids'               => count( $seen_post_ids ),
		]
	);

	return array_diff( $blog_posts_ids, $seen_post_ids );
}
