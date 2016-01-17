<?php
return [
	'frontend' => [
		[
			'pattern' => 'blog',
			'route'   => 'blogFront/posts/index',
//			'suffix'  => '/',
		],
		[
			'pattern' => 'blog/<title_url:[a-zA-Z0-9\-_]*>',
			'route'   => 'blogFront/posts/view',
			'suffix'  => '/',
		]
	],
];