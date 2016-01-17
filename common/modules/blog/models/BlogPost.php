<?php

namespace common\modules\blog\models;

use common\components\TimestampUTCBehavior;
use common\models\Image;
use yii\db\ActiveRecord;

/**
 * Посты блога
 *
 * @property int          $id              Уникальный идентификатор поста
 * @property int          $category_id     Категория
 * @property string       $title           Заголовок поста
 * @property string       $title_url       Заголовок ЧПУ поста
 * @property string       $short_content   Краткое содержание
 * @property string       $content         Контент
 * @property string       $tags            Теги
 * @property bool         $is_published    Состояние опубликованности
 * @property string       $insert_stamp    Дата-время создания поста
 * @property string       $update_stamp    Дата-время обновления поста
 *
 * Отношения
 * @property BlogCategory $category        Категория
 * @property Image[]      $images          Изображения
 * @property BlogTag[]    $tagsModels      Теги (модели)
 */
class BlogPost extends ActiveRecord {

	/** Уникальный идентификатор поста */
	const ATTR_ID = 'id';

	/** Категория */
	const ATTR_CATEGORY_ID = 'category_id';

	/** Заголовок поста */
	const ATTR_TITLE = 'title';

	/** Заголовок ЧПУ поста */
	const ATTR_TITLE_URL = 'title_url';

	/** Контент */
	const ATTR_SHORT_CONTENT = 'short_content';

	/** Контент */
	const ATTR_CONTENT = 'content';

	/** Теги */
	const ATTR_TAGS = 'tags';

	/** Состояние опубликованности */
	const ATTR_IS_PUBLISHED = 'is_published';

	/** Дата-время создания поста */
	const ATTR_INSERT_STAMP = 'insert_stamp';

	/** Дата-время обновления поста */
	const ATTR_UPDATE_STAMP = 'update_stamp';

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
			static::ATTR_ID            => '№',
			static::ATTR_CATEGORY_ID   => 'Категория',
			static::ATTR_TITLE         => 'Заголовок',
			static::ATTR_TITLE_URL     => 'ЧПУ',
			static::ATTR_SHORT_CONTENT => 'Краткое содержание',
			static::ATTR_CONTENT       => 'Контент',
			static::ATTR_TAGS          => 'Теги',
			static::ATTR_IS_PUBLISHED  => 'Опубликован',
			static::ATTR_INSERT_STAMP  => 'Создано',
			static::ATTR_UPDATE_STAMP  => 'Обновлено',
		];
	}

	public function getCategory() {
		return $this->hasOne(BlogCategory::class, [BlogCategory::ATTR_ID => static::ATTR_CATEGORY_ID]);
	}

	public function getImages() {
		return $this->hasMany(Image::class,
			[Image::ATTR_RELATED_ENTITY_ITEM_ID => static::ATTR_ID])->indexBy(Image::ATTR_ID);
	}

	public function getTagsModels() {
		return $this->hasMany(BlogTag::class, [BlogTag::ATTR_ID => BlogPostTag::ATTR_TAG_ID])
			->viaTable(BlogPostTag::tableName(), [BlogPostTag::ATTR_POST_ID => static::ATTR_ID]);
	}

}