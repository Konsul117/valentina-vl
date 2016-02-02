<?php
use common\base\View;
use yii\helpers\Html;

/** @var View $this */
/** @var bool $watermarkExists */
?>

<?php if ($watermarkExists): ?>
	<p>Загруженный водяной знак: </p>

	<p>
		<?= Html::img(['/image/watermark/watermark']) ?>
	</p>
<?php endif ?>

<?= Html::beginForm('', 'post', ['enctype' => 'multipart/form-data']) ?>

<div class="form-group">
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
