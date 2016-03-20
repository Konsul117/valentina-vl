<?php

namespace common\modules\page\models;

use common\components\behaviors\TimestampUTCBehavior;
use common\models\Entity;
use common\modules\image\models\Image;
use Yii;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;

/**
 * Модель "Страницы"
 *
 * @property int     $id              Уникальный идентификатор
 * @property string  $title           Название страницы
 * @property string  $title_url       Название ЧПУ страницы
 * @property string  $content         Контент страницы
 * @property bool    $is_published    Состояние опубликованности
 * @property string  $insert_stamp    Дата-время создания страницы
 * @property string  $update_stamp    Дата-время обновления страницы
 * @property Image   $mainImage       Главное изображение
 *
 * Отношения
 * @property Image[] $images          Изображения
 */
class Page extends ActiveRecord {

	/** Уникальный идентификатор */
	const ATTR_ID = 'id';

	/** Название страницы */
	const ATTR_TITLE = 'title';

	/** Название ЧПУ страницы */
	const ATTR_TITLE_URL = 'title_url';

	/** Контент страницы */
	const ATTR_CONTENT = 'content';

	/** Состояние опубликованности */
	const ATTR_IS_PUBLISHED = 'is_published';

	/** Дата-время создания страницы */
	const ATTR_INSERT_STAMP = 'insert_stamp';

	/** Дата-время обновления страницы */
	const ATTR_UPDATE_STAMP = 'update_stamp';

	/** Главная страница */
	const PAGE_ID_MAIN = 1;

	/** Страница контактов */
	const PAGE_ID_CONTACTS = 2;

	/** ЧПУ страницы контактов */
	const PAGE_URL_CONTACTS = 'contacts';
	/** Отношение к изображениям */
	const REL_IMAGES = 'images';
	const REL_MAIN_IMAGE = 'mainImage';

	/**
	 * @return int
	 */
	public static function getEntityId() {
		return Entity::ENTITY_PAGE_ID;
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			[
				'class'              => TimestampUTCBehavior::class,
				'createdAtAttribute' => static::ATTR_INSERT_STAMP,
				'updatedAtAttribute' => static::ATTR_UPDATE_STAMP,
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			static::ATTR_ID           => '№',
			static::ATTR_TITLE        => 'Название',
			static::ATTR_TITLE_URL    => 'ЧПУ',
			static::ATTR_CONTENT      => 'Контент',
			static::ATTR_IS_PUBLISHED => 'Опубликован',
			static::ATTR_INSERT_STAMP => 'Создано',
			static::ATTR_UPDATE_STAMP => 'Обновлено',
		];
	}

	public function afterSave($insert, $changedAttributes) {
		parent::afterSave($insert, $changedAttributes);

		TagDependency::invalidate(Yii::$app->cache, __CLASS__);
	}

	public function getImages() {
		return $this->hasMany(Image::class, [Image::ATTR_RELATED_ENTITY_ITEM_ID => static::ATTR_ID])
			->andWhere([Image::ATTR_RELATED_ENTITY_ID => Entity::ENTITY_PAGE_ID])
			->indexBy(Image::ATTR_ID);
	}

	public function getMainImage() {
		return $this->hasOne(Image::class, [Image::ATTR_RELATED_ENTITY_ITEM_ID => static::ATTR_ID])
			->andWhere([
				Image::ATTR_RELATED_ENTITY_ID => Entity::ENTITY_PAGE_ID,
				Image::ATTR_IS_MAIN           => true,
			]);
	}

}