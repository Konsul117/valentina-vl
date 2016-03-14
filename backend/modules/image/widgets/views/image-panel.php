<?php
use backend\modules\image\widgets\ImagePanelWidget;
use common\modules\image\models\Image;
use common\modules\image\models\ImageProvider;
use yii\helpers\Html;
use yii\web\View;

/** @var $this View */
/** @var $widget ImagePanelWidget */

?>

<div id="uploadedPreviewPane" class="preview-pane">
	<div class="form-group">
		<?= Html::button('Загрузка', ['id' => 'imageUploadButton', 'class' => 'btn btn-primary']) ?>
	</div>

	<?php if (!empty($widget->images)): ?>
		<?php foreach ($widget->images as $image): ?>
			<div class="image-item" data-role="image-item-params">
				<?php /** @var Image $image */ ?>
				<?= Html::img($image->getImageUrl(ImageProvider::FORMAT_THUMB), [
					'data-medium-url' => $image->getImageUrl(ImageProvider::FORMAT_MEDIUM),
					'data-image-id'   => $image->getIdent(),
				]) ?>
				<?= Html::checkbox('needWatermark[' . $image->getIdent() . ']', $image->is_need_watermark, [
					'uncheck' => 0,
					'label'   => 'Watermark',
				]) ?>
				<?= Html::hiddenInput('image-title[' . $image->getIdent() . ']', $image->title) ?>
			</div>
		<?php endforeach ?>
	<?php endif ?>
</div>