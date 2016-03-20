<?php
use common\modules\blog\models\BlogCategory;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/** @var View $this */

/** @var BlogCategory $model */
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

	<?= $form->field($model, BlogCategory::ATTR_TITLE)->textInput(['maxlength' => 100]) ?>
	<?= $form->field($model, BlogCategory::ATTR_TITLE_URL)->textInput(['maxlength' => 100]) ?>
	<?= $form->field($model, BlogCategory::ATTR_META_TITLE)->textInput(['maxlength' => 100]) ?>
	<?= $form->field($model, BlogCategory::ATTR_META_DESCRIPTION)->textInput(['maxlength' => 100]) ?>

</div>

<div class="form-group">
	<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
	<?= Html::a('Отмена', ['/blog/category/index'],
		['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
