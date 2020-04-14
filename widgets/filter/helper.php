<?php

/**
 * Checks whether the current page is a 'filter page'.
 *
 * @param type Filter type.
 *
 * @return boolean
 */
function is_filter_active( $type ) {
	global $wp;
	$user = wp_get_current_user();

	switch ( $type ) {
		case 'posts':
			return is_home() && (bool)get_query_var( 'p2020_recent_posts' );
		case 'comments':
			return is_home() && (bool)get_query_var( 'p2020_recent_comments' );
		case 'mentions':
			return ( strpos( $wp->request, 'mentions/' . $user->user_login ) === 0 );
		case 'myposts':
			return ( strpos( $wp->request, 'author/' . $user->user_login ) === 0 );
	}

	return false;
}
