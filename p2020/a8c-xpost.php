<?php 

namespace P2020;

$p2_xpost_file = WP_CONTENT_DIR . '/a8c-plugins/p2-xpost/p2-xpost.php';
require_once( $p2_xpost_file );

// phpcs:disable 
// Reason: P2_XPost plugin code requires these constants defined
define( 'P2_XPOST_PLUGIN_DIR', plugin_dir_path( $p2_xpost_file) );
define( 'P2_XPOST_PLUGIN_URL', plugin_dir_url( $p2_xpost_file ) );
// phpcs:enable
	
class A8c_XPost extends \P2_XPost {

	/**
	 * We are overriding instead of changing the original plugin file, to skip the 
	 *    template whitelist check. P2_XPost is specifically for a8c P2s and does not
	 *     provide generic x-posting features, and should not be part of P2020 as is.
	 */
	function __construct() {
		add_filter( 'the_content', [ $this, 'links' ], 5 );
		add_filter( 'comment_text', [ $this, 'links' ], 5 );
		add_filter( 'p2_found_xposts', [ $this, 'filter_xposts' ], 5 );
		add_action( 'wp_head', [ $this, 'wp_head' ], 1 );
		add_action( 'transition_post_status', [ $this, 'process_xpost' ], 12, 3 );
		add_action( 'wp_insert_comment', [ $this, 'process_xcomment' ], 12, 2 );
		add_action( 'transition_comment_status', [ $this, 'process_xcomment_transition' ], 12, 3 );
		add_action( 'template_redirect', [ $this, 'redirect_permalink' ], 1 );
	}
}
