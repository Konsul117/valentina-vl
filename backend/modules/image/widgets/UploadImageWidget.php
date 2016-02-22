<?php

namespace backend\modules\image\widgets;

use backend\modules\image\assets\ImageUploadAsset;
use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Виджет загрузки изображений
 */
class UploadImageWidget extends Widget {

	/** @var string[] Идентификаторы редакторов */
	public $editorsIds = [];

	/** @var int Идентификатор связанной сущности (id поста, страницы и пр.) */
	public $relatedEntityItemId;

	/**
	 * @inheritdoc
	 * @throws InvalidConfigException
	 */
	public function run() {

		//подключаем ассеты
		ImageUploadAsset::register($this->view);

		if (count($this->editorsIds) === 0) {
			throw new InvalidConfigException('Не заданы идентификаторы редакторов');
		}

		return $this->render('upload-image', [
			'widget' => $this,
		]);
	}

}