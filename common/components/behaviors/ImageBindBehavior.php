<?php

namespace common\components\behaviors;


use common\components\ErrorHelper;
use common\exceptions\ModelSaveException;
use common\modules\image\models\Image;
use phpQuery;
use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/**
 * Поведение для связывания сущности с изображениями
 */
class ImageBindBehavior extends Behavior {

	/**
	 * Поле, по которому можно получить массив моделей изображений
	 *
	 * @var string
	 */
	public $imagesField;

	/**
	 * Поля, по которым можно получить контент модели, в котором находятся ссылки на изображения
	 *
	 * @var string[]
	 */
	public $contentFields;

	/**
	 * Идентификатор типа сущности (@see Entity)
	 *
	 * @var int
	 */
	public $entityId;

	/**
	 * @inheritdoc
	 * @throws InvalidConfigException
	 */
	public function init() {
		parent::init();

		if ($this->imagesField === null) {
			throw new InvalidConfigException('Не указан параметр imagesField');
		}

		if ($this->contentFields === null) {
			throw new InvalidConfigException('Не указан параметр imagesField');
		}

		if ($this->entityId === null) {
			throw new InvalidConfigException('Не указан параметр entityId');
		}
	}

	public function events() {
		return [
			ActiveRecord::EVENT_AFTER_INSERT => [$this, 'afterInsertUpdate'],
			ActiveRecord::EVENT_AFTER_UPDATE => [$this, 'afterInsertUpdate'],
			ActiveRecord::EVENT_AFTER_DELETE => [$this, 'afterDelete'],
		];
	}

	public function afterInsertUpdate(Event $event) {
		$this->actualizePostImages($event->sender);
	}

	/**
	 * Актуализация изображений по картинкам, которые помещены в тело поста
	 * Обновление главного изображения поста
	 *
	 * @param ActiveRecord $model
	 *
	 * @throws InvalidConfigException
	 */
	protected function actualizePostImages($model) {
		//получаем присоединённые к посту изображения
		/** @var Image $images */
		$images = $model->{$this->imagesField};

		if (empty($images)) {
			return;
		}

		//массив спарсенных id изображений
		$imagesIds = [];

		//парсим id изображений в теле поста
		foreach($this->contentFields as $field) {
			$doc = phpQuery::newDocumentHTML($model->{$field});


			foreach ($doc->find('img[data-image-id]') as $imgEl) {
				$imageId = (int)$imgEl->getAttribute('data-image-id');
				if (!$imageId) {
					continue;
				}

				if (!in_array($imageId, $imagesIds)) {
					$imagesIds[] = $imageId;
				}
			}
		}

		//проходим по привязанным изображениям
		foreach ($images as $image) {
			//удаляем те картинки, которых нет в посте
			if (!in_array($image->id, $imagesIds)) {
				$image->delete();
			}
		}

		$model->refresh();

		if (empty($imagesIds)) {
			return;
		}

		reset($imagesIds);

		$firstImageId = current($imagesIds);

		if (isset($images[$firstImageId])) {
			$id      = $model->primaryKey;
			$command = Yii::$app->db->createCommand('UPDATE ' . Image::tableName()
				. ' SET ' . Image::ATTR_IS_MAIN . ' = 0'
				. ' WHERE ' . Image::ATTR_RELATED_ENTITY_ITEM_ID . ' = :post_id')
				->bindParam(':post_id', $id);

			$command->execute();
			$images[$firstImageId]->is_main = true;
			$images[$firstImageId]->save();
		}
	}

	public function afterDelete(Event $event) {
		$this->deleteRelatedImages($event->sender);
	}

	/**
	 * Удаление привязанных картинок
	 *
	 * @param ActiveRecord $model
	 */
	protected function deleteRelatedImages($model) {
		//получаем присоединённые к посту изображения
		/** @var Image $images */
		$images = $model->{$this->imagesField};
		//удаляем связанные картинки
		foreach ($images as $image) {
			$image->delete();
		}
	}

	/**
	 * Привязать изображения к сущности
	 *
	 * @param ActiveRecord $model
	 * @param array        $imagesIds массив id изображений для привязки
	 *
	 * @return bool
	 *
	 * @throws ModelSaveException
	 */
	public function bindImagesToPost($model, $imagesIds) {
		if ($model->isNewRecord) {
			throw new ModelSaveException('Невозможно привязать изображения: пост не сохранён');
		}

		if (empty($imagesIds)) {
			return false;
		}

		/** @var Image[] $imagesModels */
		$imagesModels = Image::findAll($imagesIds);

		if (empty($imagesModels)) {
			return false;
		}

		foreach ($imagesModels as $imagesModel) {
			$imagesModel->related_entity_id      = $this->entityId;
			$imagesModel->related_entity_item_id = $model->primaryKey;

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

		return true;
	}

}