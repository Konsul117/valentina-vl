<?php

Yii::setAlias('@themeRoot', '@frontend/sources/front/tmp/dist');

return [
	'id'         => 'frontend',
	'components' => [
		'assetManager' => [
			//делаем пустой ассет для jquery (т.к. jquery итак собирается грунтом)
			'bundles' => [
				'yii\web\JqueryAsset' => [
					'sourcePath' => null,
					'js'         => [],
				],
			],
		],
		'errorHandler' => [
			'errorView' => '@frontend/views/layouts/error.php',
		],
		'request'      => [
			'cookieValidationKey' => 'valentina-vl',
		],
	],
];