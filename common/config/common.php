<?php

use common\components\ModuleManager;

return [
	'bootstrap' => [
		'log',
		'debug',
		'moduleManager',
	],
	'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	'language' => 'ru',
	'components' => [
		'request' => [
//			'enableCookieValidation' => false,
//			'enableCsrfValidation' => false,
//			'cookieValidationKey' => 'xxxxxxx',
		],
		'configManager' => [
			'class' => ModuleManager::class,
		],
		'cache' => [
			'class' => \yii\caching\FileCache::class,
		],
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
		],
		'user' => [
			'identityClass' => 'common\models\User',
			'enableAutoLogin' => true,
		],
		'log' => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'db' => [
			'class'               => \yii\db\Connection::class,
			'charset'             => 'utf8',
			'dsn'                 => 'mysql:host=localhost;port=3306;dbname=altopromo',
			'username'            => 'root',
			'password'            => 'qwe',
			'enableSchemaCache'   => true,
			'schemaCacheDuration' => 24 * 60 * 60,
		],
		'moduleManager' => [
			'class' => ModuleManager::class,
		],
		'processLocker' => [
			'class' => \common\base\ProcessFileLocker::class,
			'method' => 'file',
		],
	],
	'modules' => [
		'debug' => [
			'class'      => \yii\debug\Module::class,
			'allowedIPs' => ['127.0.0.1', '::1'],
		],
	],
];