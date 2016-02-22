<?php
use backend\modules\image\widgets\UploadImageWidget;
use common\base\View;
use yii\helpers\Url;

/** @var View $this */
/** @var UploadImageWidget $widget */
?>

<? $this->registerJs('$(\'#uploadModal\').uploadImage({
		showButtonId: \'imageUploadButton\',
		uploadUrl: "' . Url::to(['/image/upload/upload-image/']) . '",
		previewPaneId: "uploadedPreviewPane",
		editorsIds: ["' . implode('","', $widget->editorsIds) . '"],
		params: {
			related_entity_item_id: ' . $widget->relatedEntityItemId . '
		}
	});') ?>

<div class="modal fade upload-modal" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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