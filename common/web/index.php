<?php
mb_internal_encoding('UTF-8');
mb_regex_encoding('UTF-8');

$repository = dirname(dirname(__DIR__));

defined('YII_ENV') or define('YII_ENV', file_exists($repository . '/.dev') ? 'dev' : 'prod');

define('YII_DEBUG', true);

defined('YII_DEBUG') or define('YII_DEBUG', YII_ENV === 'dev' || ((@$_COOKIE['profiler'] === 'yes') && in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1'], true)));

require($repository . '/vendor/autoload.php');
require($repository . '/vendor/yiisoft/yii2/Yii.php');
require($repository . '/common/config/bootstrap.php');

$config = \common\components\ConfigCollector::getApplicationConfig();

$application = new yii\web\Application($config);
$application->run();