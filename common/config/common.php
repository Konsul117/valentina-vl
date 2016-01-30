<?php

use common\components\ImageThumbCreator;
use common\components\ModuleManager;
use common\interfaces\ImageProvider;

return [
	'bootstrap'  => [
		'log',
		'debug',
		'moduleManager',
	],
	'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	'language'   => 'ru',
	'components' => [
		'view'              => [
			'class' => \common\base\View::class,
		],
		'request'           => [
			//			'enableCookieValidation' => false,
			//			'enableCsrfValidation' => false,
			//			'cookieValidationKey' => 'xxxxxxx',
		],
		'configManager'     => [
			'class' => ModuleManager::class,
		],
		'cache'             => [
			'class' => \yii\caching\FileCache::class,
		],
		'urlManager'        => [
			'enablePrettyUrl' => true,
			'showScriptName'  => false,
		],
		'user'              => [
			'class'           => \yii\web\User::class,
			'identityClass'   => 'common\models\User',
			'enableAutoLogin' => true,
			'loginUrl'        => ['user/auth/login'],
		],
		'log'               => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets'    => [
				[
					'class'  => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		//		'errorHandler'  => [
		//			'errorAction' => 'site/error',
		//		],
		'db'                => [
			'class'               => \yii\db\Connection::class,
			'charset'             => 'utf8',
			'dsn'                 => 'mysql:host=localhost;port=3306;dbname=valentina-vl',
			'username'            => 'root',
			'password'            => 'qwe',
			'enableSchemaCache'   => true,
			'schemaCacheDuration' => 24 * 60 * 60,
		],
		'moduleManager'     => [
			'class' => ModuleManager::class,
		],
		'imageThumbCreator' => [
			'class'       => ImageThumbCreator::class,
			'thumbsSizes' => [
				ImageProvider::FORMAT_THUMB  => [
					'width'  => 90,
					'height' => 90,
					'crop'   => true,
				],
				ImageProvider::FORMAT_MEDIUM => [
					'width'  => 240,
					'height' => 240,
				],
				ImageProvider::FORMAT_FULL   => [
					'width'  => 1000,
					'height' => 1000,
				],
			],
		],
	],
	'params'     => [
		'baseDomain'     => 'valentina-vl.ru',
		'backendDomain'  => 'backend.valentina-vl.ru',
		'frontendDomain' => 'valentina-vl.ru',
	],
	'modules'    => [
		'debug' => [
			'class'      => \yii\debug\Module::class,
			'allowedIPs' => ['127.0.0.1', '::1'],
		],
	],
];