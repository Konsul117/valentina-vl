<?php
use backend\modules\blog\models\BlogPostForm;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/** @var View $this */

/** @var BlogPostForm $model */
\app\assets\CKEditorAsset::register($this);
?>

<?php $form = ActiveForm::begin(['options' => ['id' => 'contactForm', 'autocomplete' => 'off']]); ?>

	<div class="form-group">

		<?= $form->field($model, BlogPostForm::ATTR_CATEGORY_ID)->hiddenInput()->label(false) ?>

		<?= $form->field($model, BlogPostForm::ATTR_TITLE)->textInput(['maxlength' => 100]) ?>

		<?= $form->field($model, BlogPostForm::ATTR_CONTENT)->textarea(['id' => 'contentTextarea']) ?>

		<?= $form->field($model, BlogPostForm::ATTR_IS_PUBLISHED)->checkbox() ?>

		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
		<?= Html::a('Отмена', ['/blog/blog/category/?category_url=' . $model->category->title_url], ['class' => 'btn btn-primary']) ?>
	</div>

<?php ActiveForm::end(); ?>

<?php $this->registerJs('CKEDITOR.config.extraPlugins = \'justify\';')?>

<?php $this->registerJs('CKEDITOR.replace(\'contentTextarea\',{toolbar :
		[
			{ name: \'document\', items : [ \'NewPage\',\'Preview\' ] },
		{ name: \'clipboard\', items : [ \'Cut\',\'Copy\',\'Paste\',\'PasteText\',\'PasteFromWord\',\'-\',\'Undo\',\'Redo\' ] },
		{ name: \'editing\', items : [ \'Find\',\'Replace\',\'-\',\'SelectAll\',\'-\',\'Scayt\' ] },
		{ name: \'insert\', items : [ \'Image\',\'Flash\',\'Table\',\'HorizontalRule\',\'Smiley\',\'SpecialChar\',\'PageBreak\'
                 ,\'Iframe\' ] },
                \'/\',
		{ name: \'styles\', items : [ \'Styles\',\'Format\' ] },
		{ name: \'basicstyles\', items : [ \'Bold\',\'Italic\',\'Strike\',\'-\',\'RemoveFormat\' ] },
		{ name: \'paragraph\', items : [ \'NumberedList\',\'BulletedList\',\'-\',\'Outdent\',\'Indent\',\'-\',\'Blockquote\',\'JustifyLeft\',\'JustifyCenter\',\'JustifyRight\', \'JustifyBlock\' ] },
		{ name: \'links\', items : [ \'Link\',\'Unlink\',\'Anchor\' ] },
		{ name: \'tools\', items : [ \'Maximize\',\'-\',\'About\' ] }
		]});')?>