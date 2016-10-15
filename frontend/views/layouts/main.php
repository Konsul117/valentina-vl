<?php

use frontend\assets\CommonAsset;
use yii\helpers\Html;
use common\base\View;

/** @var View $this */
/** @var string $content */

//BootstrapAsset::register($this);
CommonAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta charset="<?= Yii::$app->charset ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

	<?= Html::csrfMetaTags() ?>

	<?php

	if ($this->title === null) {
		$lastBreadcrumb = $this->breadcrumbs->getLast();

		if ($lastBreadcrumb !== null) {
			$this->title = $lastBreadcrumb->title;
		}
	}

	$pageTagHeading = $this->title . ' - Авторский блог Валентины Панченко';

	if (!$this->metaTagContainer->title) {
		$this->metaTagContainer->title = $pageTagHeading;
	}
	?>

	<?= $this->render('//layouts/blocks/meta', [
			'metaTagContainer' => $this->metaTagContainer,
	]) ?>

	<title><?= Html::encode($pageTagHeading) ?></title>

	<link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />

	<?php $this->head() ?>
</head>
<body>

<?php if (!defined('YII_DEBUG') || !YII_DEBUG): ?>
	<?= $this->render('//layouts/blocks/counters') ?>
<?php endif ?>

<?php $this->beginBody() ?>

<?= $this->render('//layouts/blocks/header') ?>

<div class="content">

	<?= $this->render('//layouts/blocks/breadcrumbs') ?>

	<?= $content ?>

</div>

<?= $this->render('//layouts/blocks/footer') ?>

<?php $this->endBody() ?>

<div id="fade-layer"></div>

</body>
</html>
<?php $this->endPage() ?>
