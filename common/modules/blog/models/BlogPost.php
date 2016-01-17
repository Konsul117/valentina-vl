<?php

namespace common\modules\blog\models;

use common\components\TimestampUTCBehavior;
use common\exceptions\ModelSaveException;
use common\models\Image;
use yii\base\Exception;
use yii\db\ActiveRecord;

/**
 * Посты блога
 *
 * @property int           $id              Уникальный идентификатор поста
 * @property int           $category_id     Категория
 * @property string        $title           Заголовок поста
 * @property string        $title_url       Заголовок ЧПУ поста
 * @property string        $content         Контент
 * @property string        $tags            Теги
 * @property bool          $is_published    Состояние опубликованности
 * @property string        $insert_stamp    Дата-время создания поста
 * @property string        $update_stamp    Дата-время обновления поста
 *
 * Отношения
 * @property BlogCategory  $category        Категория
 * @property Image[]       $images          Изображения
 * @property BlogTag[] $tagsModels      Теги (модели)
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
			static::ATTR_ID           => '№',
			static::ATTR_CATEGORY_ID  => 'Категория',
			static::ATTR_TITLE        => 'Заголовок',
			static::ATTR_TITLE_URL    => 'ЧПУ',
			static::ATTR_CONTENT      => 'Контент',
			static::ATTR_TAGS         => 'Теги',
			static::ATTR_IS_PUBLISHED => 'Опубликован',
			static::ATTR_INSERT_STAMP => 'Создано',
			static::ATTR_UPDATE_STAMP => 'Обновлено',
		];
	}

	public function beforeSave($insert) {
		if (!parent::beforeSave($insert)) {
			return false;
		}

		if ($this->isNewRecord || ($this->oldAttributes[static::ATTR_TITLE] !== $this->title)) {
			$this->title_url = $this->generateTitleUrl($this->title);
		}

		return true;
	}

	public function afterSave($insert, $changedAttributes) {
		parent::afterSave($insert, $changedAttributes);

		try {
			BlogPostTag::bindPostTags($this);
		}
		catch (ModelSaveException $e) {
			throw new Exception('Исключение при сохранении тегов поста: ' . $e->getMessage(), 0, $e);
		}
	}

	public function generateTitleUrl($title) {
		$title = preg_replace('/\[([^\]]+)\]/u', '', $title);
		$title = preg_replace('/\(([^\)]+)\)/u', '', $title);

		$translit = [
			'/'  => '-',
			'\\' => '-',
			' '  => '-',
			'а'  => 'a',
			'б'  => 'b',
			'в'  => 'v',
			'г'  => 'g',
			'д'  => 'd',
			'е'  => 'e',
			'ё'  => 'yo',
			'ж'  => 'zh',
			'з'  => 'z',
			'и'  => 'i',
			'й'  => 'j',
			'к'  => 'k',
			'л'  => 'l',
			'м'  => 'm',
			'н'  => 'n',
			'о'  => 'o',
			'п'  => 'p',
			'р'  => 'r',
			'с'  => 's',
			'т'  => 't',
			'у'  => 'u',
			'ф'  => 'f',
			'х'  => 'x',
			'ц'  => 'c',
			'ч'  => 'ch',
			'ш'  => 'sh',
			'щ'  => 'shh',
			'ы'  => 'y',
			'э'  => 'e',
			'ю'  => 'yu',
			'я'  => 'ya',
			'ь'  => '',
			'ъ'  => '',
			'-'  => '-',
		];

		$title = mb_strtolower($title, 'UTF-8');

		$title = str_replace(array_keys($translit), array_values($translit), $title);

		$title = preg_replace('/[^\-_A-z0-9]/u', '', $title);
		$title = str_replace('-[]', '', $title);
		$title = str_replace('[', '', $title);
		$title = str_replace(']', '', $title);
		$title = trim($title, '-');
		$title = preg_replace('/-+/', '-', $title);

		return $title;
	}

	public function getCategory() {
		return $this->hasOne(BlogCategory::class, [BlogCategory::ATTR_ID => static::ATTR_CATEGORY_ID]);
	}

	public function getImages() {
		return $this->hasMany(Image::class, [Image::ATTR_RELATED_ENTITY_ITEM_ID => static::ATTR_ID]);
	}

	public function getTagsModels() {
		return $this->hasMany(BlogTag::class, [BlogTag::ATTR_ID => BlogPostTag::ATTR_TAG_ID])
			->viaTable(BlogPostTag::tableName(), [BlogPostTag::ATTR_POST_ID => static::ATTR_ID]);
	}

}