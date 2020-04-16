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
	add_action( 'wp', __NAMESPACE__ . '\log_user_visit' );
}

/**
 * Retrieve posts created after given timestamp, inclusive.
 *     Own posts not included. If $ts is empty, return all posts.
 *
 * @param $ts The cutoff timestamp.
 *
 * @return array Posts created after $ts.
 */
function get_unread_posts( $ts = null ) {
	$args = [
		'post_type' => 'post',
		'posts_per_page' => -1,
		'author' => -1 * get_current_user_id(),
	];

	if ( ! empty( $ts ) ) {
		$ts_condition = [
			'after' => date( 'Y-m-d H:i:s e', $ts ),
			'inclusive' => true,
		];
	}
	if ( isset( $ts_condition ) ) {
		$args['date_query'] = [ $ts_condition ];
	}

	$query = new \WP_Query( $args );

	return $query->posts;
}

/**
 * Retrieve comments created after given timestamp, inclusive.
 *     Own comments not included. If $ts is empty, return all comments.
 *
 * @param $ts The cutoff timestamp.
 *
 * @return array Comments created after $ts.
 */
function get_unread_comments( $ts = null ) {
	$last_active = get_last_active();

	$args = [
		'post_type' => 'post',
		'type' => 'comment',
		'author' => -1 * get_current_user_id(),
	];

	if ( ! empty( $ts ) ) {
		$ts_condition = [
			'after' => date( 'Y-m-d H:i:s e', $ts ),
			'inclusive' => true,
		];
	}
	if ( isset( $ts_condition ) ) {
		$args['date_query'] = [ $ts_condition ];
	}

	$query = new \WP_Comment_Query( $args );

	return $query->get_comments();
}

/**
 * Get unread count statistics.
 *
 * @return array Unread count for posts, comments and mentions.
 */
function get_unread_count() {
	if ( ! is_user_logged_in() ) {
		return null;
	}

	$user = wp_get_current_user();

	$last_active = get_last_active();

	$unread_count = [];

	$unread_count['posts'] = count( get_unread_posts( $last_active['posts'] ?? null ) );

	$unread_count['comments'] = count( get_unread_comments( $last_active['comments'] ?? null ) );

	$unread_count['mentions'] = 0;

	$unread_posts = get_unread_posts( $last_active['mentions'] );
	foreach ( $unread_posts as $post ) {
		$mentions = \Jetpack_Mentions::get_post_mentions( $post->ID );
		$unread_count['mentions'] += count( array_keys( $mentions, $user->user_nicename ) );
	}

	$unread_comments = get_unread_comments( $last_active['mentions'] );
	foreach ( $unread_comments as $comment ) {
		$mentions = \Jetpack_Mentions::get_comment_mentions( $comment->comment_ID );
		$unread_count['mentions'] += count( array_keys( $mentions, $user->user_nicename ) );
	}

	return $unread_count;
}
add_action( 'wp', __NAMESPACE__ . '\get_unread_count' );
