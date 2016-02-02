<?php
use common\base\View;
use yii\helpers\Html;

/** @var View $this */

$title = 'Очистка';
$this->title = $title;
?>

<h1><?= Html::encode($title) ?></h1>

<?= Html::beginForm('', 'post', ['enctype' => 'multipart/form-data']) ?>

<?= Html::hiddenInput('clear', 1) ?>

<div class="form-group">
	<?= Html::submitButton('Очистить', ['class' => 'btn btn-success']) ?>
</div>

<?= Html::endForm() ?>
