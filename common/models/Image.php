<?php

namespace common\models;

use common\components\ErrorHelper;
use common\components\ImageThumbCreator;
use common\exceptions\ImageException;
use common\exceptions\ModelSaveException;
use common\interfaces\ImageProvider;
use Yii;
use yii\db\ActiveRecord;

/**
 * Изображения
 *
 * @property int $id                         Уникальынй идентификатор изображения
 * @property int $related_entity_item_id     Идентификатр объекта сущности, с которой связано изображение
 */
class Image extends ActiveRecord implements ImageProvider {

	/** Уникальынй идентификатор изображения */
	const ATTR_ID = 'id';

	/** Идентификатр объекта сущности, с которой связано изображение */
	const ATTR_RELATED_ENTITY_ITEM_ID = 'related_entity_item_id';

	/**
	 * Привязать изображения к сущности
	 *
	 * @param array $imagesIds       массив id изображений для привязки
	 * @param int   $relatedEntityId идентификатор связанной сущности
	 *
	 * @return bool
	 * @throws ModelSaveException
	 */
	public static function bindImagesToRelated(array $imagesIds, $relatedEntityId) {

		if (empty($imagesIds)) {
			return false;
		}

		/** @var Image[] $imagesModels */
		$imagesModels = static::findAll($imagesIds);

		if (empty($imagesModels)) {
			return false;
		}

		foreach ($imagesModels as $imagesModel) {
			if ($imagesModel->related_entity_item_id === null) {
				$imagesModel->related_entity_item_id = $relatedEntityId;

				$saveResult = $imagesModel->save();

				if ($saveResult === false) {
					$errors = $imagesModel->getErrors();

					if (!empty($errors)) {
						throw new ModelSaveException('Ошибки при сохранении модели Image: ' . ErrorHelper::getErrorsString($errors));
					}
					else {
						throw new ModelSaveException('Неизвестная ошибка при сохранении модели');
					}
				}
			}
		}

		return true;

	}

	/**
	 * @param string $format
	 * @return string
	 * @throws ImageException
	 */
	public function getImageUrl($format) {
		if ($this->isNewRecord) {
			throw new ImageException('Изображение отсутствует');
		}

		/** @var ImageThumbCreator $imageThumbCreator */
		$imageThumbCreator = Yii::$app->imageThumbCreator;

		return $imageThumbCreator->getImageThumbUrl($this->id, $format);
	}

}