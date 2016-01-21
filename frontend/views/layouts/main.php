<?php

use frontend\assets\CommonAsset;
use yii\helpers\Html;
use common\base\View;

/** @var View $this */
/** @var string $content */

CommonAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>

	<?php if ($this->title === null) {
		$lastBreadcrumb = $this->breadcrumbs->getLast();

		if ($lastBreadcrumb !== null) {
			$this->title = $lastBreadcrumb->title;
		}

	}?>

	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="bg-aside bg-left"></div>

<?= $this->render('//layouts/blocks/header') ?>

<div class="content">

	<?= $this->render('//layouts/blocks/breadcrumbs') ?>

	<h1><?= ($this->titleCustom !== null ? $this->titleCustom : $this->title) ?></h1>

	<?= $content ?>

</div>

<div class="bg-aside bg-right"></div>

<footer class="footer">
	<div class="container">
		Vladivostok <?= date('Y') ?>
	</div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
