<?php
use backend\modules\editor\widgets\EditorWidget;
use common\modules\image\models\ImageProvider;
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
					<?php /** @var \common\modules\image\models\Image $image */ ?>
					<?= Html::img($image->getImageUrl(ImageProvider::FORMAT_THUMB), [
							'data-medium-url' => $image->getImageUrl(ImageProvider::FORMAT_MEDIUM),
							'data-image-id'   => $image->getIdent(),
					]) ?>
					<?= Html::checkbox('needWatermark[' . $image->getIdent() . ']', $image->is_need_watermark, [
							'uncheck' => 0,
							'label'   => 'Watermark',
					]) ?>
				</div>
			<?php endforeach ?>
		<?php endif ?>

	</div>
<?php endif ?>

<?php $related_entity_item_id = $widget->model->{$widget->identAttribute};
$this->registerJs(
		'var initUpload = function($editor) {
		$editor.uploadImage({
	editor: tinyMCE.get(\'' . $textareaId . '\'),
	showButtonId: \'imageUploadButton\',
	uploadUrl: "' . Url::to(['/editor/upload/upload-image/']) . '",
	previewPaneId: "uploadedPreviewPane",
	params: {
		' . ($related_entity_item_id !== null ? ('related_entity_item_id: ' . $related_entity_item_id) : '') . '
	}
	});};
tinymce.init({
selector: \'#' . $textareaId . '\',
' . ($widget->uploadImages ?
				'init_instance_callback: function() {
initUpload($(\'#uploadModal_' . $textareaId . '\'));
},' : '')
		. 'height: 200,
plugins: [
    \'advlist autolink lists link image charmap print preview anchor\',
    \'searchreplace visualblocks code fullscreen\',
    \'insertdatetime media table contextmenu paste code\'
  ],
  toolbar: \'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image\',
  content_css: [
    \'//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css\',
    \'//www.tinymce.com/css/codepen.min.css\'
    ,\'' . $this->assetBundles[\backend\modules\editor\assets\TinyMCEInnerAsset::class]->baseUrl . '/css/editor_inner.css\'
  ],

	body_class: "mceContentBody"
});') ?>

<?php if ($widget->uploadImages): ?>
	<div class="modal fade upload-modal" id="uploadModal_<?= $textareaId ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

	<br />
	<br />

	<div class="form-group">
		<?= Html::button('Загрузка', ['id' => 'imageUploadButton', 'class' => 'btn btn-primary']) ?>
	</div>
<?php endif ?>