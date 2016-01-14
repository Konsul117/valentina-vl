<?php
use backend\modules\blog\models\BlogPostForm;
use yii\helpers\Html;
use yii\web\View;
/** @var View $this */
/** @var BlogPostForm $model */

?>

<h1><?= Html::encode($model->title) ?></h1>

<?= $this->render('_form_post', [
	'model' => $model,
]) ?>