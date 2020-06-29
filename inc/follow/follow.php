<?php

namespace P2020\Follow;

function enqueue_scripts() {
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\scripts' );
}

function scripts() {
	wp_enqueue_script( 'p2020-follow', get_template_directory_uri() . '/inc/follow/js/follow.js', [ 'jquery' ], false, true );

	$data = [
		'isUserLoggedIn' => is_user_logged_in(),
		'nonce' => wp_create_nonce( 'manage_subscription' ),
		'followText' => __( 'Follow', 'p2020' ),
		'unfollowText' => __( 'Unfollow', 'p2020' ),
		'followingText' => __( 'Following', 'p2020' ),
	];

	wp_localize_script( 'p2020-follow', 'p2020FollowData', $data );
}

function render() {
	$is_following = wpcom_subs_is_subscribed( [
		'user_id' => get_current_user_id(),
		'blog_id' => get_current_blog_id(),
	] );
	$label = $is_following ? __( 'Following', 'p2020' ) : __( 'Follow', 'p2020' );
	$title = $is_following ? __( 'Unfollow', 'p2020' ) : '';
	$class = $is_following ? 'unfollow' : 'follow';
	echo '<button class="p2020-follow ' . esc_attr( $class ) . '" title="' . esc_attr( $title ) . '" ><span>' .
		esc_html( $label, 'p2020' ) .
		'</span></button>';
}
