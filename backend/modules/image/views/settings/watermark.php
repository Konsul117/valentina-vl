<?php
use common\base\View;
use yii\helpers\Html;

/** @var View $this */
/** @var bool $watermarkExists */
$title       = 'Редактирование водяного знака';
$this->title = $title;
?>

<h1><?= Html::encode($title) ?></h1>

<?php if ($watermarkExists): ?>
	<p>Загруженный водяной знак: </p>

	<p>
		<?= Html::img(['/image/settings/watermark-show']) ?>
	</p>
<?php endif ?>

<?= Html::beginForm('', 'post', ['enctype' => 'multipart/form-data']) ?>

<div class="form-group">
	<div class="alert alert-info">
		Необходимо загружать только png-файл
	</div>

	<div class="btn btn-default btn-file">
		Выбрать файл
		<?= Html::fileInput('watermark', null, [
				'class' => 'btn-file',
				'id'    => 'uploadWatermarkFile',
		]) ?>
	</div>
</div>

<div class="form-group">
	<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
</div>

<?= Html::endForm() ?>
