<?php
use backend\modules\pageBackend\models\PageForm;
use yii\helpers\Html;
use yii\web\View;
/** @var View $this */
/** @var PageForm $model */

$title = 'Создание новой страницы';
$this->title = $title;
?>

	<h1><?= Html::encode($title) ?></h1>

<?= $this->render('_form_page', [
	'model' => $model,
]) ?>