<?php

/** @var \yii\web\View $this */

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


<?= $this->render('//layouts/blocks/header')?>


<!--<footer class="footer">-->
<!--	<div class="container">-->
<!--		<p class="pull-left">&copy; My Company --><? //= date('Y') ?><!--</p>-->
<!---->
<!--		<p class="pull-right">--><? //= Yii::powered() ?><!--</p>-->
<!--	</div>-->
<!--</footer>-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
