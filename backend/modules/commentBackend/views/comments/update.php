<?php
use common\modules\comment\models\Comment;
use yii\helpers\Html;
use yii\web\View;

/** @var View $this */
/** @var Comment $model */

$this->title = 'Редактирование комменария';
?>

	<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form_comment', [
	'model' => $model,
]) ?>