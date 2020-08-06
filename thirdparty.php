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
function is_p2tenberg_user_enabled(): bool {
	return true;
	/*$user             = wp_get_current_user();
	$current_blog_id  = get_current_blog_id();
	$use_block_editor = get_user_attribute( $user->data->ID, 'o2_use_p2tenberg_' . $current_blog_id );

	return 'disabled' !== $use_block_editor;*/
}

/**
 * Enable o2 plugin for front-page editing
 */
function enable_o2() {
	if ( class_exists( 'o2' ) ) {
		return;
	}

	if ( ! file_exists( WP_PLUGIN_DIR . '/o2/o2.php' ) ) {
		if ( is_admin() ) {
			return;
		}
		wp_die( 'The O2 plugin is needed to use this theme! Get it from <a href="https://github.com/automattic/o2">https://github.com/automattic/o2</a>' );
	}
	require_once WP_PLUGIN_DIR . '/o2/o2.php';
}

/**
 * Enable p2tenberg for Gutenberg for the front-page editor
 */
function enable_p2tenberg() {
	if ( file_exists( WP_PLUGIN_DIR . '/p2tenberg/p2tenberg.php' ) ) {
		if ( is_p2tenberg_user_enabled() ) {
			require_once WP_PLUGIN_DIR . '/p2tenberg/p2tenberg.php';
		}
	}

	// Load plugin code for displaying p2tenberg toggle
	if ( file_exists( WP_PLUGIN_DIR . '/p2tenberg-wpcom/p2tenberg-wpcom.php' ) ) {
		require_once WP_PLUGIN_DIR . '/p2tenberg-wpcom/p2tenberg-wpcom.php';
	}
}

/**
 * Enable inline-terms for @-mentions autocomplete
 */
function enable_inline_terms() {
	wp_enqueue_script(
		'inline-terms-mentions-js',
		plugins_url( 'wpcom-mentions.js', WPMU_PLUGIN_DIR . '/inline-terms/inline-terms.php' ),
		[ 'jquery.wpcom-proxy-request' ],
		'20200801',
		true
	);

	require_once WPMU_PLUGIN_DIR . '/inline-terms/wpcom-email-templates.php';
	require_once WPMU_PLUGIN_DIR . '/inline-terms/inline-terms.php';
	require_once WPMU_PLUGIN_DIR . '/inline-terms/mentions.php';
	require_once WPMU_PLUGIN_DIR . '/inline-terms/wpcom.php';
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

	add_action(
		'init',
		function() {
			if ( ! wp_style_is( 'noticons', 'registered' ) ) {
				wp_register_style(
					'noticons',
					staticize_subdomain( '//wordpress.com/i/noticons/noticons.css' ),
					null,
					Notifications_UI::CACHE_BUSTER,
					'all'
				);
			}
		},
		-1
	);
}

function enable_emoji() {
	if ( file_exists( WP_PLUGIN_DIR . '/emoji-autocomplete-gutenberg/emoji-autocomplete-gutenberg.php' ) ) {
		require_once WP_PLUGIN_DIR . '/emoji-autocomplete-gutenberg/emoji-autocomplete-gutenberg.php';
	}
}

add_action( 'after_setup_theme', __NAMESPACE__ . '\enable_emoji', 100 );
//add_action( 'after_setup_theme', __NAMESPACE__ . '\enable_inline_terms', 100 );
//add_action( 'after_setup_theme', __NAMESPACE__ . '\enable_notifications', 100 );
add_action( 'after_setup_theme', __NAMESPACE__ . '\enable_o2', 101 );
add_action( 'after_setup_theme', __NAMESPACE__ . '\enable_p2tenberg', 102 );
