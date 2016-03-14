<?php

namespace common\modules\image\models;

use common\components\behaviors\TimestampUTCBehavior;
use common\exceptions\ImageException;
use common\modules\blog\models\BlogPost;
use Yii;
use yii\base\Exception;
use yii\caching\TagDependency;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Изображения
 *
 * @property int      $id                         Уникальынй идентификатор изображения
 * @property int      $related_entity_id          Идентификатор сущности, с которой связано изображение
 * @property int      $related_entity_item_id     Идентификатр объекта сущности, с которой связано изображение
 * @property string   $title                      Название изображения
 * @property bool     $is_main                    Главная картинка
 * @property bool     $is_need_watermark          Нужен водяной знак
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

	const ATTR_TITLE = 'title';

	/** Главная картинка */
	const ATTR_IS_MAIN = 'is_main';

	/** Нужен водяной знак */
	const ATTR_IS_NEED_WATERMARK = 'is_need_watermark';

	/** Дата-время создания изображения */
	const ATTR_INSERT_STAMP = 'insert_stamp';
	/** Отношение к Post */
	const REL_POST = 'post';

	/**
	 * Получить закэшированную модель изображения.
	 *
	 * @param int $imageId Id изображения
	 *
	 * @return Image|null
	 */
	public static function getCachedInstance($imageId) {
		$cacheKey = __METHOD__ . '-id=' . $imageId;

		$image = Yii::$app->cache->get($cacheKey);

		if ($image === false) {
			/** @var Image $image */
			$image = static::findOne($imageId);

			Yii::$app->cache->set($cacheKey, $image, 3600 * 6, new TagDependency(['tags' => [
				static::class,
			]]));
		}

		return $image;
	}

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

	public function rules() {
		return [
			[[static::ATTR_IS_NEED_WATERMARK], 'default', 'value' => true],
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

		try {
			$this->clearThumbs();
		}
		catch (ImageException $e) {
			throw new Exception('Не удалось вычистить тамбы изображения: ' . $e->getMessage(), 0, $e);
		}
	}

	/**
	 * Очистить тамбы изображения
	 *
	 * @throws ImageException
	 */
	public function clearThumbs() {
		/** @var \common\modules\image\Image $imageModule */
		$imageModule = Yii::$app->getModule('image');

		$imageModule->imageThumbCreator->clearThumbs($this->id);
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

		return $imageModule->imageThumbCreator->getImageThumbUrl($this->id, $format, $this->is_need_watermark);
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