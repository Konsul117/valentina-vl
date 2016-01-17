<?php
use backend\modules\blog\models\BlogPostForm;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/** @var View $this */

/** @var BlogPostForm $model */
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

	<?= $form->field($model, BlogPostForm::ATTR_CATEGORY_ID)->hiddenInput()->label(false) ?>

	<?= $form->field($model, BlogPostForm::ATTR_TITLE)->textInput(['maxlength' => 100]) ?>

	<?php
	/** @var \backend\modules\editor\Editor $editorModule */
	$editorModule = Yii::$app->modules['editor']; ?>

	<?= $editorModule->getEditorWidget($form, $model, BlogPostForm::ATTR_ID, BlogPostForm::ATTR_SHORT_CONTENT)->run() ?>

	<?= $editorModule->getEditorWidget($form, $model, BlogPostForm::ATTR_ID, BlogPostForm::ATTR_CONTENT, 'images', true)->run() ?>

	<?= $form->field($model, BlogPostForm::ATTR_IS_PUBLISHED)->checkbox() ?>
</div>

<div class="form-group">
	<?= $form->field($model, BlogPostForm::ATTR_TAGS)->textInput() ?>
</div>

<div class="form-group">
	<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
	<?= Html::a('Отмена', ['/blog/blog/category/?category_url=' . $model->category->title_url],
			['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
