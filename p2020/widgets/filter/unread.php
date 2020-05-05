<?php
/**
 * Functions for customizing o2 filter widget behavior.
 *
 * @package p2020
 */

namespace P2020;

require_once( WPMU_PLUGIN_DIR . '/inline-terms/mentions.php' );

require_once( __DIR__ . '/helper.php' );

/**
* Register additional URL query vars, so we can pull them via
*     get_query_var(...) later.
* @param $vars
* @return array
*/
function add_unread_query_vars( $vars ) {
	$vars[] = 'p2020_recent_posts';
	$vars[] = 'p2020_recent_comments';

	return $vars;
}
add_filter( 'query_vars', __NAMESPACE__ . '\add_unread_query_vars' );

/**
* Get user meta 'p2020_last_active', used for tracking user visit across
*	special pages.
*
* @return array
*/
function get_last_active( $all_sites = false ) {
	if ( ! is_user_logged_in() ) {
		return [];
	}

	$user_id = get_current_user_id();
	$blog_id = get_current_blog_id();

	$last_active = get_user_meta( $user_id, 'p2020_last_active', true ); // empty string if var not found
	$last_active = $last_active ? unserialize( $last_active ) : [];

	if ( $all_sites ) {
		return $last_active;
	}

	return $last_active[$blog_id] ?? [];
}

/**
* Update the user meta 'p2020_last_active, used for tracking user visit across
*     special pages.
*
* @param $type The filter type, e.g. 'posts' for unread posts,
* 		'comments' for unread comments
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
* Callback function for logging user visits to pages of interest.
*/
function log_user_visit() {
	if ( ! is_user_logged_in() ) {
		return;
	}

	if ( is_filter_active( 'posts' ) ) {
		update_last_active( 'posts' );
	} elseif ( is_filter_active( 'comments' ) ) {
		update_last_active( 'comments' );
	} elseif ( is_filter_active( 'mentions' ) ) {
		update_last_active( 'mentions' );
	}
}
if ( is_user_logged_in() ) {
	// TODO Maybe hook this to a custom action with a more obvious timing?
	add_action( 'wp', __NAMESPACE__ . '\log_user_visit' );
}

/**
 * Retrieve posts created after given timestamp, inclusive.
 *     Own posts not included. If $ts is empty, return all posts.
 *
 * @param $ts Optional timestamp.
 *
 * @return array Posts (IDs only).
 */
function get_posts_after_ts( $ts = null, $limit = null ) {
	$args = [
		'post_type' => 'post',
		'posts_per_page' => $limit ?? -1,
		'author' => -1 * get_current_user_id(),
		'fields' => 'ids',
	];

	// If $ts is set, we filter on that
	if ( ! empty( $ts ) ) {
		$ts_condition = [
			'after' => date( 'Y-m-d H:i:s e', $ts ),
			'inclusive' => true,
		];

		$args['date_query'] = [ $ts_condition ];
	}

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
	$args = [
		'post_type' => 'post',
		'type' => 'comment',
		'author__not_in' => [ get_current_user_id() ],
		'fields' => 'ids',
	];

	// If $ts is set, we filter on that
	if ( ! empty( $ts ) ) {
		$ts_condition = [
			'after' => date( 'Y-m-d H:i:s e', $ts ),
			'inclusive' => true,
		];

		$args['date_query'] = [ $ts_condition ];
	}

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
		'posts' => [],
		'comments' => [],
	];
	$user = wp_get_current_user();
	$unread_posts = get_posts_after_ts( $ts, $limit );
	foreach ( $unread_posts as $post_id ) {
		$post_mentions = \Jetpack_Mentions::get_post_mentions( $post_id );
		if ( in_array( $user->user_nicename, $post_mentions ) ) {
			$mentions['posts'][] = $post_id;
		}
	}

	$unread_comments = get_comments_after_ts( $ts );
	foreach ( $unread_comments as $comment_id ) {
		$comment_mentions = \Jetpack_Mentions::get_comment_mentions( $comment_id );
		if ( in_array( $user->user_nicename, $comment_mentions ) ) {
			$mentions['comments'][] = $comment_id;
		}
	}

	return $mentions;
}

/**
 * Get unread count statistics.
 *
 * @return array Unread count for posts, comments and mentions.
 */
function get_unread_count( $limit = null ) {
	if ( ! is_user_logged_in() ) {
		return null;
	}

	$last_active = get_last_active();

	$unread_count = [];

	$unread_count['posts'] = ( ! empty( $last_active['posts'] ) ) ?
		count( get_posts_after_ts( $last_active['posts'], $limit ) ) :
		0;

	$unread_count['comments'] = ( ! empty( $last_active['comments'] ) ) ?
		count(	get_comments_after_ts( $last_active['comments'], $limit ) ) :
		0;

	if ( ! empty( $last_active['mentions'] ) ) {
		$unread_mentions = get_mentions_after_ts( $last_active['mentions'] ?? null, $limit );
		$unread_count['mentions'] =  count( $unread_mentions['posts'] ) + count( $unread_mentions['comments'] );
	} else {
		$unread_count['mentions'] = 0;
	}

	return $unread_count;
}

/**
 * Get unread posts, based on $p2020_last_active activity tracker.
 *
 * @return array|null Posts (IDs only).
 */
function get_unread_posts() {
	$last_active = get_last_active();
	return get_posts_after_ts( $last_active['posts'] ?? null, $limit );
}

/**
 * Get unread comments, based on $p2020_last_active activity tracker.
 *
 * @return array|null Comments (IDs only).
 */
function get_unread_comments() {
	$last_active = get_last_active();
	return get_comments_after_ts( $last_active['comments'] ?? null, $limit );
}

/**
 * Get unread posts and comments with user mentions, based on
 *     $p2020_last_active activity tracker.
 *
 * @return array Posts and comments (IDs only).
 */
function get_unread_mentions( $limit ) {
	$last_active = get_last_active();
	return get_mentions_after_ts( $last_active['mentions'] ?? null, $limit );
}
