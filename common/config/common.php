<?php

use common\components\ModuleManager;

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
			'cachePath' => '@common/runtime/cache',
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
	],
	'params'     => [
		'baseDomain'          => 'valentina-vl.ru',
		'backendDomain'       => 'backend.valentina-vl.ru',
		'frontendDomain'      => 'valentina-vl.ru',
		//смещение локальной таймзоны от UTC
		'localTimezoneOffset' => 10,
	],
	'modules'    => [
		'debug' => [
			'class'      => \yii\debug\Module::class,
			'allowedIPs' => ['127.0.0.1', '::1'],
		],
	],
];