<?php

add_filter( 'typekit_add_font_category_rules', function( $category_rules ) {

	TypekitTheme::add_font_category_rule( $category_rules, 'body-text',
		'body',
			[
				[ 'property' => 'font-size', 'value' => '1.05em' ],
			]
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'body-text',
		'body,
		textarea',
			[
				[ 'property' => 'font-family', 'value' => '"Noto Serif", serif' ],
			]
	);
	
	TypekitTheme::add_font_category_rule( $category_rules, 'headings',
		'h1',
			[
				[ 'property' => 'font-size', 'value' => '2.2em' ],
				[ 'property' => 'font-weight', 'value' => '400' ],
			]
	);
		
	TypekitTheme::add_font_category_rule( $category_rules, 'headings',
		'h2',
			[
				[ 'property' => 'font-size', 'value' => '2em' ],
				[ 'property' => 'font-weight', 'value' => '400' ],
			]
	);
	
	TypekitTheme::add_font_category_rule( $category_rules, 'headings',
		'h3',
			[
				[ 'property' => 'font-size', 'value' => '1.6em' ],
				[ 'property' => 'font-weight', 'value' => '400' ],
			]
	);
	
	TypekitTheme::add_font_category_rule( $category_rules, 'headings',
		'h4',
			[
				[ 'property' => 'font-size', 'value' => '1.4em' ],
				[ 'property' => 'font-weight', 'value' => '400' ],
			]
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'headings',
		'.site-header .site-title',
			[
				[ 'property' => 'font-family', 'value' => '"Noto Serif", serif' ],
			]
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'headings',
		'.site-header .site-description',
			[
				[ 'property' => 'font-family', 'value' => '"Open Sans", sans-serif' ],
				[ 'property' => 'font-size', 'value' => '1.3em' ],
			]
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'body-text',
		'.entry-author',
			[
				[ 'property' => 'font-family', 'value' => '"Open Sans", sans-serif' ],
				[ 'property' => 'font-size', 'value' => '1.2em' ],
			]
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'headings',
		'.widget-title',
			[
				[ 'property' => 'font-family', 'value' => '"Open Sans", sans-serif' ],
				[ 'property' => 'font-size', 'value' => '1.9em' ],
			]
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'body-text',
		'.o2-app-controls',
		[
				[ 'property' => 'font-family', 'value' => '"Open Sans", sans-serif' ],
		]
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'headings',
		'.navigation-main ul li a,
		.o2 .comment-likes,
		.o2 .o2-comment-footer-actions ul li a,
		.o2 .o2-app-page-title,
		.o2 .o2-app-new-post h2,
		.o2 .o2-actions,
		.o2-save',
			[
				[ 'property' => 'font-family', 'value' => '"Open Sans", sans-serif' ],
			]
	);

	return $category_rules;
} );
