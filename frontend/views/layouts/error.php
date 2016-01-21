<?php

use frontend\assets\CommonAsset;
use yii\helpers\Html;
use yii\web\View;

/** @var View $this */
/** @var string $content */

CommonAsset::register($this);
/* @var $exception \yii\web\HttpException|\Exception */
/* @var $handler \yii\web\ErrorHandler */
if ($exception instanceof \yii\web\HttpException) {
	$code = $exception->statusCode;
} else {
	$code = $exception->getCode();
}
$name = \Yii::t('app',$handler->getExceptionName($exception));
if ($name === null) {
	$name = 'Ошибка';
}
if ($code) {
	$name .= " (#$code)";
}

if ($exception instanceof \yii\base\UserException) {
	$message = $exception->getMessage();
} else {
	$message = 'An internal server error occurred.';
}
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="bg-aside bg-left"></div>

<?= $this->render('//layouts/blocks/header') ?>

<div class="content">

	<?= $this->render('//layouts/blocks/breadcrumbs') ?>

	<h1><?= $this->title ?></h1>

	<h1><?= $handler->htmlEncode($name) ?></h1>
	<h2><?= nl2br($handler->htmlEncode($message)) ?></h2>

</div>

<div class="bg-aside bg-right"></div>

<?= $this->render('//layouts/blocks/footer') ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
