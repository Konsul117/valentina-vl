<?php
use backend\modules\blog\models\BlogPostForm;
use yii\helpers\Html;
use yii\web\View;
/** @var View $this */
/** @var BlogPostForm $model */

$title = 'Создание нового поста: ' . ($model->category->title);
$this->title = $title;
?>

	<h1><?= Html::encode($title) ?></h1>

<?= $this->render('_form_post', [
	'model' => $model,
]) ?>