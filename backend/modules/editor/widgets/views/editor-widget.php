<?php
use backend\modules\editor\widgets\EditorWidget;
use common\interfaces\ImageProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/** @var View $this */
/** @var EditorWidget $widget */

$textareaId = 'textarea' . $widget->contentAttribute;
?>

<?= $widget->form->field($widget->model, $widget->contentAttribute)->textarea(['id' => $textareaId]) ?>

<?php if ($widget->uploadImages): ?>
	<div id="uploadedPreviewPane" class="preview-pane">
		<p>Загруженные изображения: </p>

		<?php if (!empty($widget->model->{$widget->imagesAttribute})): ?>
			<?php foreach ($widget->model->{$widget->imagesAttribute} as $image): ?>
				<div class="image-item">
					<?php /** @var ImageProvider $image */ ?>
					<?= Html::img($image->getImageUrl(ImageProvider::FORMAT_THUMB), [
							'data-medium-url' => $image->getImageUrl(ImageProvider::FORMAT_MEDIUM),
							'data-image-id'   => $image->getIdent(),
					]) ?>
				</div>
			<?php endforeach ?>
		<?php endif ?>

	</div>
<?php endif ?>

<?php $this->registerJs('CKEDITOR.config.extraPlugins = \'justify\';') ?>

<?php $this->registerJs('CKEDITOR.replace(\'' . $textareaId . '\',{toolbar :
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
		],
		allowedContent: true
		});') ?>

<?php if ($widget->uploadImages): ?>
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

					<ul class="uploading-images list-unstyled">

					</ul>
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
	$related_entity_item_id = $widget->model->{$widget->identAttribute};
	$this->registerJs('$(\'#uploadModal\').uploadImage({
	editor: CKEDITOR.instances.' . $textareaId . ',
	showButtonId: \'imageUploadButton\',
	uploadUrl: "' . Url::to(['/editor/upload/upload-image/']) . '",
	previewPaneId: "uploadedPreviewPane",
	params: {
		' . ($related_entity_item_id !== null ? ('related_entity_item_id: ' . $related_entity_item_id) : '') . '
	}
	})');
	?>
<?php endif ?>