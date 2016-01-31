<?php
use backend\modules\editor\Editor;
use backend\modules\pageBackend\models\PageForm;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/** @var View $this */

/** @var PageForm $model */
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

	<?= $form->field($model, PageForm::ATTR_TITLE)->textInput(['maxlength' => 100]) ?>

	<?php
	/** @var Editor $editorModule */
	$editorModule = Yii::$app->modules['editor']; ?>

	<?= $editorModule->getEditorWidget($form, $model, PageForm::ATTR_ID, PageForm::ATTR_CONTENT, PageForm::REL_IMAGES, true)->run() ?>

	<?= $form->field($model, PageForm::ATTR_IS_PUBLISHED)->checkbox() ?>
</div>

<div class="form-group">
	<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
	<?= Html::a('Отмена', ['/pageBackend/page/index'],
		['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
