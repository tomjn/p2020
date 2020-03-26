<?php

$options = get_theme_mod( 'breathe_theme_options' );
$alternate_color = ( !empty( $options['alternate_color'] ) && '#3498db' != $options['alternate_color'] ? $options['alternate_color'] : '#3498db' );
$link_color = ( !empty( $options['link_color'] ) && '#3498db' != $options['link_color'] ? $options['link_color'] : '#3498db' );

add_color_rule( 'bg', '#f1f1f1', [
 	[ 'body', 'background-color' ],
 	[ 'body.custom-background', 'background-color' ],
 	[ '.o2-app-footer > .navigation, .navigation .nav-older a', 'border-color', 'bg', 2 ],
 	[ '#secondary', 'background' ],
] );

add_color_rule( 'link', $link_color, [
	[ 'a', 'color', '#ffffff' ],
	[ 'a:visited', 'color', '#ffffff' ],
	[ 'a:active', 'color', '#ffffff' ],
	[ '.entry-meta .entry-actions:hover a', 'color', '#ffffff' ],
	[ '.entry-meta .entry-actions:hover a:visited', 'color', '#ffffff' ],
	[ '.comment-meta .comment-actions:hover a', 'color', '#ffffff' ],
	[ '.comment-meta .comment-actions:hover a:visited', 'color', '#ffffff' ],
	[ '#help dt', 'color', '#ffffff' ],
	[ '#media-buttons .button', 'color', '#ffffff' ],
	[ '.o2 .o2-editor-toolbar-button', 'color', '#fafafa' ],
	[ '.o2-comment:hover .o2-actions a:after', 'color', '#ffffff' ],
	[ '.entry-content a', 'color', '#ffffff' ],
	[ '.entry-meta a', 'color', '#ffffff' ],
	[ '.o2-comment a', 'color', '#ffffff' ],
	[ '.widget a', 'color', '#ffffff' ],
	[ '.widget li a', 'color', '#ffffff' ],
	[ '.site-main a', 'color', '#ffffff' ],
	[ '.o2 .o2-app-controls a', 'color', 'fg1' ],
	[ '.navigation-main a', 'color', '#ffffff', 7 ],
	[ '.navigation-main ul li li a', 'color', '#ffffff' ],
	[ '.navigation-main ul li:hover > a, .navigation-main ul li li a:hover, .navigation-main ul ul li:hover > a, .navigation-main ul li.current-menu-item a', 'color', 'link', 7 ],
	[ '.entry-content p a', 'color', '#ffffff' ],
	[ '.navigation a:hover', 'color', '#ffffff' ],
	[ '.o2-app-footer a, .site-footer a', 'color', 'bg' ],
	[ '.navigation-main ul li:hover > a', 'background-color' ],
	[ '.navigation-main ul ul li:hover > a', 'background-color' ],
	[ '.navigation-main ul li.current-menu-item a', 'background-color' ],
	[ '#o2-expand-editor', 'background' ],
	[ '.o2 .o2-editor-wrapper.dragging', 'outline-color' ],
	[ '.o2-editor-upload-progress', 'background-color' ],
	[ 'li.o2-filter-widget-item a', 'color' ],
	[ '.o2-app-new-post a.o2-editor-fullscreen', 'color', 'bg' ]
] );

add_color_rule( 'fg1', $alternate_color, [
	[ '.o2 .o2-app-page-title', 'background-color' ],
	[ 'li.o2-filter-widget-item a.o2-filter-widget-selected', 'background-color' ],
	[ '.o2 .o2-app-new-post h2', 'background-color', 'color' ],
	[ 'h1.site-title a', 'color' ],
	[ 'h1.widget-title', 'color', '#ffffff' ],
	[ 'li.o2-filter-widget-item a:before', 'color' ],
] );

add_color_rule( 'fg2', '#ffffff', [
	[ '.o2 .o2-app-page-title', 'color', 'fg1' ],
	[ '.o2 .o2-app-new-post h2', 'color', 'fg1' ],
	[ 'li.o2-filter-widget-item a.o2-filter-widget-selected,li.o2-filter-widget-item a.o2-filter-widget-selected:before', 'color', 'fg1' ],

] );

add_color_rule( 'extra', '#000000', [
	[ '.no-sidebar .site-header .site-title a', 'color', 'bg' ],

] );

add_color_rule( 'extra', '#222222', [
	[ '.no-sidebar .site-header .site-description', 'color', 'bg' ],
	[ 'a.subscription-link.o2-follow.post-comments-subscribed:after', 'color' ],
	[ '.o2-post:hover a.subscription-link.o2-follow.post-comments-subscribed:after', 'color' ],
	[ '.o2-app-new-post .oblique-strategy', 'color', 'bg', 4 ]
] );

add_color_rule( 'extra', '#555555', [
	[ '.site-footer, .o2-app-new-post .comment-subscription-form', 'color', 'bg' ]
] );

add_theme_support( 'custom_colors_extra_css', 'breathe_extra_css' );
function breathe_extra_css() { ?>
.custom-background.o2 .tag-p2-xpost {
	background-color: rgba(255,255,255,0.9) !important;
}
<?php
}

add_color_palette( [
    '#f4f4f4',
    '',
    '#f0e5c9',
    '#a68c69',
    '#594433',
], 'Neutral' );

add_color_palette( [
    '#2b2b2b',
    '',
    '#bcbcbc',
    '#424242',
    '#e9e9e9',
], 'Dark' );

add_color_palette( [
    '#f1f1f1',
    '',
    '#3498db',
    '#888888',
    '#eeeeee',
], 'Light' );
