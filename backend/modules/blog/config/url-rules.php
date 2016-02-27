<?php
return [
	'backend' => [
		[
			'pattern' => 'blog',
			'route'   => 'blog/blog/index',
			'suffix'  => '/',
		],
		[
			'pattern' => 'blog/<title_url:[a-zA-Z0-9\-_]*>',
			'route'   => 'blogFront/posts/view',
			'suffix'  => '/',
		],
	],
];