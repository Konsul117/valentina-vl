<?php

/** @var \yii\web\View $this */
/** @var string $content */

use yii\helpers\Html;

\frontend\assets\CommonAsset::register($this);
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


<?= $this->render('//layouts/blocks/header') ?>

<div class="content">

	<?= $this->render('//layouts/blocks/breadcrumbs') ?>

	<h1><?= $this->title ?></h1>

	<?= $content ?>

</div>


<footer class="footer">
	<div class="container">
		Vladivostok <?= date('Y') ?>
	</div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
