<?php
return [
	//добавляем сюда роуты, нужные для генерации sitemap
	//@see {SitemapController}
	'frontend' => [
		[
			'pattern' => 'sitemap.xml',
			'route'   => 'site/sitemap',
		],
	],
];