<?php
use backend\modules\pageBackend\models\PageForm;
use yii\helpers\Html;
use yii\web\View;
/** @var View $this */
/** @var PageForm $model */

$this->title = 'Редактирование страницы: ' . $model->title . '.';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form_page', [
	'model' => $model,
]) ?>