<?php
/**
 * Functions for third-party plugins
 *
 * @package p2020
 */

namespace P2020;

/**
 * Check if p2tenberg is enabled by the user.
 *
 * @return bool
 */
function is_p2tenberg_user_enabled() {
	$user = wp_get_current_user();
	$current_blog_id = get_current_blog_id();
	$use_block_editor = get_user_attribute( $user->data->ID, 'o2_use_p2tenberg_' . $current_blog_id );

	return 'disabled' !== $use_block_editor;
}

/**
 * Enable o2 plugin for front-page editing
 */
function enable_o2() {
	require_once WP_PLUGIN_DIR . '/o2/o2.php';
}

/**
 * Enable p2tenberg for Gutenberg for the front-page editor
 */
function enable_p2tenberg() {
	if ( is_p2tenberg_user_enabled() ) {
		require_once WP_PLUGIN_DIR . '/p2tenberg/p2tenberg.php';
	}

	// Load plugin code for displaying p2tenberg toggle
	require_once WP_PLUGIN_DIR . '/p2tenberg-wpcom/p2tenberg-wpcom.php';
}

/**
 * Enable inline-terms for @-mentions autocomplete
 */
function enable_inline_terms() {
	wp_enqueue_script(
		'inline-terms-mentions-js',
		plugins_url( 'wpcom-mentions.js', WPMU_PLUGIN_DIR . '/inline-terms/inline-terms.php' ),
		[ 'jquery.wpcom-proxy-request' ]
	);

	require_once( WPMU_PLUGIN_DIR . '/inline-terms/wpcom-email-templates.php' );
	require_once( WPMU_PLUGIN_DIR . '/inline-terms/inline-terms.php' );
	require_once( WPMU_PLUGIN_DIR . '/inline-terms/mentions.php' );
	require_once( WPMU_PLUGIN_DIR . '/inline-terms/wpcom.php' );
}

/**
 * Enable notifications
 */
function enable_notifications() {
	require_once WPMU_PLUGIN_DIR . '/notes/simperium-wpcom-notes.php';
	require_once WPMU_PLUGIN_DIR . '/notes/store-wpcom-notes.php';
	require_once WPMU_PLUGIN_DIR . '/notes/notifications-ui.php';
	require_once WPMU_PLUGIN_DIR . '/notes/jetpack-notes.php';
	require_once WPMU_PLUGIN_DIR . '/notes/notes-hooks.php';
	require_once WPMU_PLUGIN_DIR . '/notes/chrome-push-notifications.php';

	add_action( 'init', function() {
		if ( ! wp_style_is( 'noticons', 'registered' ) ) {
			wp_register_style(
				'noticons',
				staticize_subdomain( '//wordpress.com/i/noticons/noticons.css' ),
				null,
				Notifications_UI::CACHE_BUSTER,
				'all' );
		}
	}, -1 );
}

/**
 * Set Homepage display to latest posts.
 */
function set_homepage_display() {
	$display = get_option( 'show_on_front' );
	if ( $display !== 'posts' ) {
		update_option( 'show_on_front', 'posts' );
	}
}

function enable_default_widgets() {
	$sidebars = get_option( 'sidebars_widgets' );

	if ( empty( $sidebars['sidebar-1'] ) ) {
		$widget_no = 2;

		// O2 Filter widget
		$filter_widget_settings = [
			$widget_no => [
				'title' => __( 'Links', 'p2020' ),
			]
		];
		update_option( 'widget_o2-filter-widget', $filter_widget_settings );
		
		// O2 Live Posts and Comments widget
		$live_comments_widget_settings = [
			$widget_no => [
				'title' => __( 'Recent Activity', 'p2020' ),
				'kind' => 'both',
				'number' => 10,
			]
		];
		update_option( 'widget_o2-live-comments-widget', $live_comments_widget_settings );

		// My Team widget
		$team_widget_settings = [
			$widget_no => [
				'title' => __( 'My Team', 'p2020' ),
			]
		];
		update_option( 'widget_p2020-my-team-widget', $team_widget_settings );

		// Add widgets to sidebar
		$sidebars['sidebar-1'] = [
			'o2-filter-widget-' . $widget_no,
			'o2-live-comments-widget-' . $widget_no,
			'p2020-my-team-widget-' . $widget_no,
		];

		$sidebars['wp_inactive_widgets'] = [];
		$sidebars['array_version'] = 3;

		update_option( 'sidebars_widgets', $sidebars );
	}
}

add_action( 'after_setup_theme', 'P2020\enable_inline_terms', 100 );
add_action( 'after_setup_theme', 'P2020\enable_notifications', 100 );
add_action( 'after_setup_theme', 'P2020\enable_o2', 101 );
add_action( 'after_setup_theme', 'P2020\set_homepage_display', 102 );
add_action( 'after_setup_theme', 'P2020\enable_default_widgets', 102 );
add_action( 'after_setup_theme', 'P2020\enable_p2tenberg', 103 );
