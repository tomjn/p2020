<?php
/**
 * Generate an ellipsis menu.
 *
 * Only supports links for now, no buttons.
 *
 * Example usage:
 *
 * $menu = new EllipsisMenu();
 * $menu->add_item( __( 'Item 1', 'p2020' ), 'https://wordpress.com' );
 * $menu->add_item( __( 'Item 2', 'p2020' ), 'https://wordpress.com' );
 * echo html_output( $menu->generate() );
 *
 * @package p2020
 */

namespace P2020;

class EllipsisMenu {
	private $items = [];

	public function add_item( string $label, string $url ) {
		$this->items[] = [
			'label' => $label,
			'url'   => $url,
		];
	}

	private function make_list(): string {
		$list_items = array_map(
			function ( array $item ): string {
				$label = esc_html( $item['label'] );
				$url   = esc_url( $item['url'] );

				return <<<ITEM
				<li role="menuitem">
					<a href="$url">
						$label
					</a>
				</li>
ITEM;
			},
			$this->items
		);

		return '<ul class="p2020-ellipsis-menu" role="menu">' . implode( $list_items ) . '</ul>';
	}

	/**
	 * Returns the HTML for the menu.
	 */
	public function generate(): string {
		$list       = $this->make_list();
		$menu_label = esc_attr__( 'More', 'p2020' );

		// Outer div is required for proper tab order
		return <<<MENU
			<div>
				<button
					aria-haspopup="true"
					aria-expanded="false"
					aria-label="$menu_label"
					class="p2020-ellipsis-button"
					data-tippy-menu-trigger
				>
				</button>
				<div data-tippy-menu-content hidden>
					$list
				</div>
			</div>
MENU;
	}
}
