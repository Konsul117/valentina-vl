<?php
use backend\modules\editor\widgets\EditorWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/** @var View $this */
/** @var EditorWidget $widget */
?>

<?= $widget->form->field($widget->model, $widget->attribute)->textarea(['id' => 'contentTextarea']) ?>

<?php $this->registerJs('CKEDITOR.config.extraPlugins = \'justify\';') ?>

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
		]});') ?>

<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Название модали</h4>
			</div>
			<div class="modal-body">
				<div id="uploadDropzone" class="dropzone">
					<div class="dz-default dz-message"><span>Перетащите изображения сюда</span></div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>

<div class="form-group">
	<?= Html::button('Загрузка', ['id' => 'imageUploadButton', 'class' => 'btn btn-primary']) ?>
</div>

<?php
$this->registerJs('$(\'#uploadModal\').uploadImage({editor: CKEDITOR.instances.contentTextarea, showButtonId: \'imageUploadButton\', uploadUrl: "' . Url::to(['/editor/upload/upload-image/']) . '"})');
?>
