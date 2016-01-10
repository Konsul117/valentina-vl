<?php

Yii::setAlias('@themeRoot', '@frontend/sources/front/tmp/dist');

return [
	'id' => 'frontend',
	'components' => [
		'request' => [
			'cookieValidationKey' => 'valentina-vl',
		],
	],
];