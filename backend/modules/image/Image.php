<?php

namespace backend\modules\image;
use backend\modules\image\widgets\ImagePanelWidget;
use backend\modules\image\widgets\UploadImageWidget;

/**
 * Расширение модуля работы с изображениями для бэкэнда
 */
class Image extends \common\modules\image\Image {

	/**
	 * Получение виджета загрузки изображений
	 *
	 * @param string[] $editorsIds          Идентификаторы редакторов
	 * @param int      $relatedEntityItemId Идентификатор связанной сущности (id поста, страницы и пр.)
	 *
	 * @return UploadImageWidget
	 */
	public function getUploadImageWidget($editorsIds, $relatedEntityItemId) {
		$widget                      = new UploadImageWidget();
		$widget->editorsIds          = $editorsIds;
		$widget->relatedEntityItemId = $relatedEntityItemId;

		return $widget;
	}

	/**
	 * Получение виджета панели изображений
	 *
	 * @param \common\modules\image\models\Image[] $images
	 *
	 * @return ImagePanelWidget
	 */
	public function getImagePanelWidget($images) {
		$widget = new ImagePanelWidget();
		$widget->images = $images;

		return $widget;
	}

}