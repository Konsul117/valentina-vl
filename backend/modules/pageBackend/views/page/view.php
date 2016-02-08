<?php
/** @var View $this */
use backend\modules\blog\models\BlogPostForm;
use backend\modules\editor\assets\EditorViewAsset;
use yii\helpers\Html;
use yii\web\View;

/** @var BlogPostForm $model */

$this->title = $model->title;

EditorViewAsset::register($this);
?>

<h1><?= Html::encode($model->title) ?></h1>

<p>
	<?= Html::a('Редактировать', ['update?id=' . $model->id], ['class' => 'btn btn-success']) ?>
</p>

<div class="post-content">
	<?= $model->content ?>
</div>
