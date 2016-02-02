<?php

namespace common\modules\image\models;

use common\components\behaviors\TimestampUTCBehavior;
use common\exceptions\ImageException;
use common\modules\blog\models\BlogPost;
use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Изображения
 *
 * @property int      $id                         Уникальынй идентификатор изображения
 * @property int      $related_entity_id          Идентификатор сущности, с которой связано изображение
 * @property int      $related_entity_item_id     Идентификатр объекта сущности, с которой связано изображение
 * @property bool     $is_main                    Главная картинка
 * @property string   $insert_stamp               Дата-время создания изображения
 *
 * Отношения
 * @property-read BlogPost $post                       Пост блога, с котороым связано изображение
 */
class Image extends ActiveRecord implements ImageProvider {

	/** Уникальынй идентификатор изображения */
	const ATTR_ID = 'id';

	/** Идентификатор сущности, с которой связано изображение */
	const ATTR_RELATED_ENTITY_ID = 'related_entity_id';

	/** Идентификатр объекта сущности, с которой связано изображение */
	const ATTR_RELATED_ENTITY_ITEM_ID = 'related_entity_item_id';

	/** Главная картинка */
	const ATTR_IS_MAIN = 'is_main';

	/** Дата-время создания изображения */
	const ATTR_INSERT_STAMP = 'insert_stamp';
	/** Отношение к Post */
	const REL_POST = 'post';

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			[
				'class'              => TimestampUTCBehavior::class,
				'createdAtAttribute' => static::ATTR_INSERT_STAMP,
				'updatedAtAttribute' => false,
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function afterDelete() {
		parent::afterDelete();

		$imageFile = Yii::getAlias('@upload_images_path') . DIRECTORY_SEPARATOR . $this->id . '.jpg';

		if (file_exists($imageFile) && is_writable($imageFile)) {
			unlink($imageFile);
		}

		/** @var \common\modules\image\Image $imageModule */
		$imageModule = Yii::$app->getModule('image');;

		try {
			$imageModule->imageThumbCreator->clearThumbs($this->id);
		}
		catch (ImageException $e) {
			throw new Exception('Не удалось вычистить тамбы изображения: ' . $e->getMessage(), 0, $e);
		}
	}

	/**
	 * @param string $format
	 *
	 * @return string
	 *
	 * @throws ImageException
	 */
	public function getImageUrl($format) {
		if ($this->isNewRecord) {
			throw new ImageException('Изображение отсутствует');
		}

		/** @var \common\modules\image\Image $imageModule */
		$imageModule = Yii::$app->getModule('image');

		return $imageModule->imageThumbCreator->getImageThumbUrl($this->id, $format);
	}

	/**
	 * @inheritdoc
	 */
	public function getIdent() {
		return $this->id;
	}

	/**
	 * Связь с постом
	 *
	 * @return ActiveQuery
	 */
	public function getPost() {
		return $this->hasOne(BlogPost::class, [BlogPost::ATTR_ID => static::ATTR_RELATED_ENTITY_ITEM_ID]);
	}

}