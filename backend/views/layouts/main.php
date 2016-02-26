<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>

	<link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />

	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
	<?php
	NavBar::begin([
			'options' => [
					'class' => 'navbar-inverse',
			],
	]);

	if (!Yii::$app->user->isGuest) {
		echo Nav::widget([
				'options' => ['class' => 'navbar-nav navbar-left'],
				'items'   => [
						['label' => 'Бисер', 'url' => ['/blog/blog/category/?category_url=biser']],
						['label' => 'Не бисер', 'url' => ['/blog/blog/category/?category_url=not_biser']],
						['label' => 'Страницы', 'url' => ['/pageBackend/page/index']],
						['label' => 'Настройки', 'items' => [
								['label' => 'Водяной знак', 'url' => ['/image/settings/watermark']],
								['label' => 'Очистка', 'url' => ['/image/settings/clear-thumbs']],
						]],
						['label' => 'Главная', 'url' => ['/']],
				],
		]);
	}

	$rightItems = [];
	if (Yii::$app->user->isGuest) {
		$rightItems[] = ['label' => 'Вход', 'url' => ['/site/login']];
	}
	else {
		$rightItems[] = [
				'label'       => 'Выход (' . Yii::$app->user->identity->username . ')',
				'url'         => ['/site/logout'],
				'linkOptions' => ['data-method' => 'post'],
		];
	}

	echo Nav::widget([
			'options' => ['class' => 'navbar-nav navbar-right'],
			'items'   => $rightItems,
	]);

	NavBar::end();
	?>

	<div class="container">
		<?= Breadcrumbs::widget([
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
		]) ?>
		<?= Alert::widget() ?>
		<?= $content ?>
	</div>
</div>

<footer class="footer">
	<div class="container_wrap">
		<p class="pull-left">&copy; My Company <?= date('Y') ?></p>

		<p class="pull-right"><?= Yii::powered() ?></p>
	</div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
