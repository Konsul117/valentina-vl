<?php use common\modules\sitemap\Sitemap;

return [
	'id' => 'console',
	'components' => [
		'urlManager' => [
			'baseUrl' => 'http://valentina-vl.ru',
		],
	],
	'modules' => [
		'sitemap' => [
			'class' => Sitemap::class,
		]
	]
];