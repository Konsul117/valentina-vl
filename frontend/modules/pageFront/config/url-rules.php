<?php
return [
	'frontend' => [
		[
			'pattern' => 'page/<title_url:[a-zA-Z0-9\-_]*>',
			'route'   => 'pageFront/page/view',
			'suffix'  => '/',
		],
	],
];