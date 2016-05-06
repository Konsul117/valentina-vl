<?php
use common\modules\comment\models\Comment;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/** @var View $this */

/** @var Comment $model */
/** @var string[] $errors */
?>

<?php if (!empty($errors)): ?>
	<div class="alert alert-warning">
		<p>При сохранении произошли следующие ошибки:</p>
		<ul class="list-unstyled">
			<?php foreach ($errors as $error): ?>
				<li><?= $error ?></li>
			<?php endforeach ?>
		</ul>
	</div>
<?php endif ?>


<?php $form = ActiveForm::begin(['options' => ['id' => 'contactForm', 'autocomplete' => 'off']]); ?>

<div class="form-group">

	<p>Комментарий для <strong><?= $model->entityTitle ?></strong>, пост <strong><?= $model->entityItemTitle ?></strong></p>

	<?= $form->field($model, Comment::ATTR_NICKNAME)->textInput() ?>

	<?= $form->field($model, Comment::ATTR_CONTENT)->textarea() ?>

	<?= $form->field($model, Comment::ATTR_IS_PUBLISHED)->checkbox() ?>

</div>

	<div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
		<?= Html::a('Отмена', ['index'],
				['class' => 'btn btn-primary']) ?>
	</div>

<?php ActiveForm::end(); ?>