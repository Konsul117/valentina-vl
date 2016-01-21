<?php
return [
	'frontend' => [
		[
			'pattern' => 'blog',
			'route'   => 'blogFront/posts/index',
			'suffix'  => '/',
		],
		[
			'pattern' => 'blog/search',
			'route'   => 'blogFront/posts/search',
			'suffix'  => '/',
		],
		[
			'pattern' => 'blog/tag/',
			'route'   => 'blogFront/posts/tags',
			'suffix'  => '/',
		],
		[
			'pattern' => 'blog/<title_url:[a-zA-Z0-9\-_]*>',
			'route'   => 'blogFront/posts/view',
			'suffix'  => '/',
		],
		[
			'pattern' => 'blog/category/<category_url:[a-zA-Z0-9\-_]*>',
			'route'   => 'blogFront/posts/category',
			'suffix'  => '/',
		],
		[
			'pattern' => 'blog/tag/<tag:[a-zA-Z0-9\-_]*>',
			'route'   => 'blogFront/posts/tag',
			'suffix'  => '/',
		],
	],
];