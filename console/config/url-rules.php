<?php
return [
	//добавляем сюда роуты, нужные для генерации sitemap
	//@see {SitemapController}
	'console' => [
		[
			'pattern' => 'blog',
			'route'   => 'blogFront/posts/index',
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
			'pattern' => 'page/<title_url:[a-zA-Z0-9\-_]*>',
			'route'   => 'pageFront/page/view',
			'suffix'  => '/',
		],
	],
];