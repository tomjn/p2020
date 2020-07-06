<?php
/**
 * Generate the sidebar header.
 *
 * @package p2020
 */

namespace P2020;

function get_sidebar_header(): string {
	$home_url = esc_url( home_url( '/' ) );
	$site_name = esc_attr( get_bloginfo( 'name', 'display' ) );

	$searchform = get_search_form( [ "echo" => false ] );

	$mobile_toggle_label = esc_attr( 'Menu', 'p2020' );
	$hamburger_label = esc_attr( 'Documents and Links', 'p2020' );

	$is_page = is_page() ? 'true' : 'false';

	return <<<HTML
		<nav class="p2020-sidebar-header">

			<div class="p2020-sidebar-header__group-left">
				<h1 class="p2020-sidebar-header__heading">
					<a href="$home_url" aria-label="$site_name P2">
						<svg
							class="p2020-logo-p2"
							role="presentation"
							width="32" height="32"
							viewBox="0 0 32 32"
							fill="none"
							xmlns="http://www.w3.org/2000/svg"
						>
							<rect class="p2020-logo-p2__background" width="31.9649" height="32" rx="2" />
							<rect class="p2020-logo-p2__foreground" x="9.40149" y="7.5293" width="12.692" height="8.47059" />
							<rect class="p2020-logo-p2__foreground" x="9.40149" y="18.8232" width="8.46131" height="5.64706"/>
						</svg>
					</a>
				</h1>

				<button
					class="p2020-sidebar-header__mobile-toggle-button"
					aria-label="$mobile_toggle_label"
					aria-expanded="false"
					data-sidebar-mobile-toggle
				>
				</button>
			</div>

			<span class="p2020-sidebar-header__mobile-title" aria-hidden="true">
				$site_name
			</span>

			<div class="p2020-sidebar-header__group-right">
				$searchform
				<button
					class="p2020-sidebar-header__hamburger-button"
					aria-label="$hamburger_label"
					aria-expanded="$is_page"
					data-sidebar-hamburger
				>
				</button>
			</div>

		</nav>
HTML;
}
