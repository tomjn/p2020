<?php
/**
 * Generate a Contributors block.
 *
 * @package p2020
 */

namespace P2020;

function get_contributors( int $post_author, int $limit = 3 ): array {
	$args                          = [ 'order' => 'ASC' ];
	$revisions                     = wp_get_post_revisions( get_queried_object_id(), $args );
	$authors_by_contribution_count = [ $post_author => 1 ];

	foreach ( (array) $revisions as $revision ) {
		if ( wp_is_post_autosave( $revision ) ) {
			continue;
		}

		$authors_by_contribution_count[ $revision->post_author ] ++;
	}

	arsort( $authors_by_contribution_count, SORT_NUMERIC );
	return array_slice( $authors_by_contribution_count, 0, $limit, true );
}

function get_contributors_block(): string {
	$post_author  = get_post()->post_author;
	$contributors = get_contributors( $post_author );

	if ( count( $contributors ) === 0 ) {
		return null;
	}

	$contributor_list_items = array_map(
		function ( int $contributor_id, int $count ): string {
			$userdata   = get_userdata( $contributor_id );
			$avatar     = get_avatar( $contributor_id, 36 );
			$link       = esc_url( get_author_posts_url( $contributor_id ) );
			$link_title = esc_attr(
				sprintf(
				/* translators: %1$s is replaced with the user's display name; %2$s is the user's nicename */
					__( 'Posts by %1$s ( @%2$s )', 'p2020' ),
					$userdata->display_name,
					$userdata->user_nicename
				)
			);
			$name = esc_html( $userdata->display_name );

			$revisions = esc_html(
			/* translators: %s is replaced with the number of revisions */
				sprintf( _n( '%s revision', '%s revisions', $count, 'p2020' ), $count )
			);

			return <<<LISTITEM
		<li class="p2020-contributors__item">
			<div class="p2020-contributors__item-avatar">
				$avatar
			</div>
			<div>
				<a
					href="$link"
					class="p2020-contributors__item-name"
					title="$link_title"
				>
					$name
				</a>
				<div class="p2020-contributors__item-revisions">
					$revisions
				</div>
			</div>
		</li>
LISTITEM;
		},
		array_keys( $contributors ),
		$contributors
	);

	$contributor_list_items_imploded = implode( '', $contributor_list_items );

	$heading_text = esc_html__( 'Contributors', 'p2020' );

	return <<<CONTRIBUTORS
	<aside class="p2020-contributors">
		<h2 class="p2020-contributors__heading">
			<span class="p2020-contributors__heading-text">
				$heading_text
			</span>
		</h2>
		<ol class="p2020-contributors__list">
			$contributor_list_items_imploded
		</ol>
	</aside>
CONTRIBUTORS;
}
