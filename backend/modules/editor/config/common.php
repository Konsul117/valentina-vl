<?php
use backend\modules\editor\components\ImageUploader;
use backend\modules\editor\Editor;

return [
	'modules' => [
		'editor' => [
			'class'      => Editor::class,
			'components' => [
				'imageUploader' => [
					'class'             => ImageUploader::class,
					'maxOriginalWidth'  => 1000,
					'maxOriginalHeight' => 1000,
				],
			],
		],
	],
];